<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with(['user', 'plan', 'voucher'])
            ->latest()
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['user', 'plan', 'voucher']);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Confirm the payment and activate/extend the subdomain.
     */
    public function confirm(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'This payment has already been processed.');
        }

        $payment->update(['status' => 'success']);

        // Decrement voucher usage if applicable
        if ($payment->voucher_id) {
            $payment->voucher->decrement('usage_limit');
        }

        // Extend the subdomain if this was a renewal or activation
        if ($payment->subdomain_id) {
            $subdomain = \App\Models\Subdomain::find($payment->subdomain_id);
            if ($subdomain) {
                $currentExpiry = $subdomain->expired_at && $subdomain->expired_at->isFuture() 
                    ? $subdomain->expired_at 
                    : now();
                $subdomain->update([
                    'expired_at' => $currentExpiry->addMonths($payment->plan->duration_months)
                ]);
            }
        }

        // Send automated chat notification
        \App\Models\Chat::create([
            'user_id' => $payment->user_id,
            'is_admin' => true,
            'message' => "Pembayaran terverifikasi! Subdomain " . ($payment->subdomain ? $payment->subdomain->name : 'Anda') . " sudah aktif hingga " . ($payment->subdomain ? $payment->subdomain->expired_at->format('d M Y') : 'periode selanjutnya') . ". Terima kasih.",
            'is_read' => false,
        ]);

        return back()->with('success', 'Payment confirmed and service activated!');
    }
}
