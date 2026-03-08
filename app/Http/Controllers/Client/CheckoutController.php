<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Payment;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function process(Request $request, Plan $plan)
    {
        $user = Auth::user();
        $voucherCode = $request->input('voucher_code');
        $voucher = null;
        $discount = 0;
        $originalPrice = (int) $plan->price;

        if ($voucherCode) {
            $voucher = Voucher::where('code', $voucherCode)
                ->where(function($query) {
                    $query->whereNull('expires_at')->orWhere('expires_at', '>', Carbon::now());
                })
                ->where(function($query) {
                    $query->whereNull('usage_limit')->orWhere('usage_limit', '>', 0);
                })
                ->first();

            if ($voucher) {
                if ($voucher->type === 'fixed') {
                    $discount = (int) $voucher->reward_amount;
                } elseif ($voucher->type === 'percent') {
                    $discount = (int) ($originalPrice * ($voucher->reward_amount / 100));
                }
            }
        }

        $grossAmount = max(0, $originalPrice - $discount);

        // Create a unique transaction ID
        $orderId = 'PAY-' . time() . '-' . $user->id;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => $plan->id,
                    'price' => $originalPrice,
                    'quantity' => 1,
                    'name' => $plan->name,
                ]
            ],
        ];

        // Add discount as a negative item if applicable
        if ($discount > 0) {
            $params['item_details'][] = [
                'id' => 'DISC-' . ($voucher ? $voucher->id : 'VOUCH'),
                'price' => -$discount,
                'quantity' => 1,
                'name' => 'Voucher Discount: ' . ($voucher ? $voucher->code : ''),
            ];
        }

        try {
            $snapToken = Snap::getSnapToken($params);

            // Save pending payment record
            Payment::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'voucher_id' => $voucher ? $voucher->id : null,
                'transaction_id' => $orderId,
                'snap_token' => $snapToken,
                'amount' => $grossAmount,
                'status' => 'pending',
            ]);

            // If it's a valid voucher with a limit, we theoretically should decrement it on successful payment
            // but we can track that in the webhook or here. Usually on settlement.

            return view('client.checkout.snap', compact('snapToken', 'plan', 'grossAmount', 'discount', 'originalPrice'));
        } catch (\Exception $e) {
            return back()->with('error', 'Could not initialize payment: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        return redirect()->route('client.index')->with('success', 'Payment successful! Your hosting plan is now active.');
    }

    public function webhook(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionStatus = $request->transaction_status;
        $orderId = $request->order_id;

        $payment = Payment::where('transaction_id', $orderId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $payment->update(['status' => 'success']);
            
            // Decrement voucher usage if applicable
            if ($payment->voucher_id) {
                $payment->voucher->decrement('usage_limit');
            }
        } elseif ($transactionStatus == 'pending') {
            $payment->update(['status' => 'pending']);
        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            $payment->update(['status' => 'failed']);
        }

        return response()->json(['message' => 'Webhook processed']);
    }
}
