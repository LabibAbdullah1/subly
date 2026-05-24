<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\AdminPaymentNotification;
use App\Models\Plan;
use App\Models\Payment;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function process(Request $request, Plan $plan)
    {
        $user = Auth::user();
        $voucherCode = $request->input('voucher_code');
        $renewSubdomainId = $request->input('renew');
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

        // Anti-Spam: Check for existing pending payment for this plan/subdomain
        $existingPayment = Payment::where('user_id', $user->id)
            ->where('plan_id', $plan->id)
            ->where('subdomain_id', $renewSubdomainId)
            ->where('status', 'pending')
            ->first();

        if ($existingPayment) {
            return redirect()->route('client.checkout.qris', $existingPayment)->with('info', 'Anda memiliki pembayaran tertunda untuk item ini.');
        }

        // Create a unique transaction ID
        $orderId = 'PAY-' . time() . '-' . $user->id;

        if ($grossAmount <= 0) {
            // Free plan due to 100% discount, skip payment
            $payment = Payment::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'voucher_id' => $voucher ? $voucher->id : null,
                'subdomain_id' => $renewSubdomainId,
                'transaction_id' => $orderId,
                'amount' => 0,
                'status' => 'success',
            ]);

            if ($voucher && $voucher->usage_limit !== null) {
                $voucher->decrement('usage_limit');
            }

            // Immediately extend subdomain if this is a renewal
            if ($renewSubdomainId) {
                $subdomainToRenew = \App\Models\Subdomain::find($renewSubdomainId);
                if ($subdomainToRenew && $subdomainToRenew->user_id == $user->id) {
                    $currentExpiry = $subdomainToRenew->expired_at && $subdomainToRenew->expired_at->isFuture() 
                        ? $subdomainToRenew->expired_at 
                        : now();
                    $subdomainToRenew->update([
                        'expired_at' => $currentExpiry->addMonths($plan->duration_months)
                    ]);
                }
            }

            return redirect()->route('client.checkout.success')->with('success', 'Plan purchased successfully using a voucher!');
        }

        // Manual QRIS logic: Reuse last unique code for user to prevent "fishing" for smaller codes
        $lastPaymentWithCode = Payment::where('user_id', $user->id)
            ->whereNotNull('unique_code')
            ->latest()
            ->first();

        $uniqueCode = $lastPaymentWithCode ? $lastPaymentWithCode->unique_code : rand(100, 999);
        $totalAmount = $grossAmount + $uniqueCode;

        // Save pending payment record
        $payment = Payment::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'voucher_id' => $voucher ? $voucher->id : null,
            'subdomain_id' => $renewSubdomainId,
            'transaction_id' => $orderId,
            'amount' => $totalAmount,
            'unique_code' => $uniqueCode,
            'status' => 'pending',
        ]);

        // Notify admin via email about new payment
        $this->notifyAdminPayment($payment);

        return redirect()->route('client.checkout.qris', $payment);
    }

    public function qris(Payment $payment)
    {
        if ($payment->user_id != auth()->id()) {
            abort(403);
        }

        if ($payment->status !== 'pending') {
            return redirect()->route('client.index')->with('info', 'This payment has already been processed.');
        }

        return view('client.checkout.qris', compact('payment'));
    }

    public function success(Request $request)
    {
        return redirect()->route('client.index')->with('success', 'Payment successful! Your hosting plan is now active.');
    }

    /**
     * Send email notification to admin about a payment event.
     */
    private function notifyAdminPayment(Payment $payment): void
    {
        try {
            $payment->load(['user', 'plan', 'subdomain']);
            $adminEmail = config('mail.admin_email', env('MAIL_FROM_ADDRESS'));
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new AdminPaymentNotification($payment));
            }
        } catch (\Exception $e) {
            \Log::error('Admin payment email failed: ' . $e->getMessage());
        }
    }

    public function cancel(Payment $payment)
    {
        if ($payment->user_id != auth()->id()) {
            abort(403);
        }

        if ($payment->status === 'pending') {
            $payment->update(['status' => 'failed']);
        }

        return redirect()->route('client.plans.index')->with('success', 'Pembayaran dibatalkan. Silakan pilih paket lain.');
    }

    public function status(Payment $payment)
    {
        if ($payment->user_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json(['status' => $payment->status]);
    }
}
