<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Subdomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SubdomainController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        // Check available subdomain slots (payments without a linked subdomain)
        $unsignedPayments = $user->payments()
            ->with('plan')
            ->where('status', 'success')
            ->whereNull('subdomain_id')
            ->get()
            ->filter(function ($p) {
                return $p->plan;
            });

        if ($unsignedPayments->isEmpty()) {
            return redirect()->route('client.index')->with('error', 'You do not have any free hosting plan slots. Please purchase another plan to deploy more subdomains.');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:63',
                'alpha_dash:ascii', // Only letters, numbers, dashes, and underscores
                'unique:subdomains,name',
            ],
        ], [
            'name.unique' => 'This subdomain is already taken. Please try another one.',
            'name.alpha_dash' => 'The subdomain name may only contain letters, numbers, dashes, and underscores.',
        ]);

        $subdomainName = strtolower($validated['name']);
        $fullDomain = $subdomainName . config('app.subdomain_suffix');
        $docRoot = config('app.doc_root_prefix') . $subdomainName;

        $paymentId = $request->input('payment_id');
        $paymentToUse = null;
        
        if ($paymentId) {
            $paymentToUse = $unsignedPayments->firstWhere('id', $paymentId);
        }
        
        if (!$paymentToUse) {
            $paymentToUse = $unsignedPayments->first();
        }
        
        $plan = $paymentToUse->plan;
        
        $subdomain = Subdomain::create([
            'user_id' => $user->id,
            'name' => $subdomainName,
            'full_domain' => $fullDomain,
            'doc_root' => $docRoot,
            'status' => 'active',
            'expired_at' => now()->addMonths($plan->duration_months),
        ]);

        $paymentToUse->update(['subdomain_id' => $subdomain->id]);

        // Automatically provision virtual host and MySQL database
        app(\App\Services\ServerProvisioningService::class)->provisionSubdomain($subdomain);

        return redirect()->route('client.index')->with('success', "Congratulations! Your subdomain '{$fullDomain}' has been claimed successfully and server database provisioned.");
    }

    public function destroy(Subdomain $subdomain)
    {
        if ($subdomain->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete all payments explicitly linked to this subdomain so the plan is removed
        $subdomain->payments()->delete();

        // Deprovision from cPanel (delete subdomain, database, files)
        try {
            app(\App\Services\ServerProvisioningService::class)->deprovisionSubdomain($subdomain);
        } catch (\Exception $e) {
            Log::error("Failed to deprovision subdomain from cPanel during user cancellation: " . $e->getMessage());
        }

        // The Subdomain model has SoftDeletes, so this will soft delete it.
        $subdomain->delete();

        return redirect()->route('client.index')->with('success', 'Subdomain and its active plan have been successfully cancelled and removed.');
    }

    public function renew(Subdomain $subdomain)
    {
        if ($subdomain->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Find the active plan for this subdomain by checking the latest successful payment
        $payment = $subdomain->payments()->with('plan')->where('status', 'success')->latest()->first();

        // Fallback: If no payment is explicitly linked (e.g. older legacy plans before update), 
        // try to find any successful payment the user has, or abort if none are found.
        if (!$payment) {
            $payment = Auth::user()->payments()->with('plan')->where('status', 'success')->latest()->first();
        }

        if (!$payment || !$payment->plan) {
            return redirect()->route('client.index')->with('error', 'Could not determine the active plan for this subdomain.');
        }

        $plan = $payment->plan;

        return view('client.subdomains.renew', compact('subdomain', 'plan'));
    }
}
