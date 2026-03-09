<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Subdomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                return $p->plan && $p->created_at->addMonths($p->plan->duration_months)->isFuture();
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

        $paymentToUse = $unsignedPayments->first();
        $plan = $paymentToUse->plan;
        
        $subdomain = Subdomain::create([
            'user_id' => $user->id,
            'name' => $subdomainName,
            'full_domain' => $fullDomain,
            'doc_root' => $docRoot,
            'status' => 'active',
            'expired_at' => $paymentToUse->created_at->addMonths($plan->duration_months),
        ]);

        $paymentToUse->update(['subdomain_id' => $subdomain->id]);

        return redirect()->route('client.index')->with('success', "Congratulations! Your subdomain '{$fullDomain}' has been claimed successfully.");
    }

    public function destroy(Subdomain $subdomain)
    {
        if ($subdomain->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete all payments explicitly linked to this subdomain so the plan is removed
        $subdomain->payments()->delete();

        // The Subdomain model has SoftDeletes, so this will soft delete it.
        // It's also linked via foreign keys so cascades might happen depending on DB schema, 
        // but Laravel handles soft delete relationships mostly manually. 
        // We leave deployments alone as they have their own SoftDeletes and can be cleaned by Admin.
        $subdomain->delete();

        return redirect()->route('client.index')->with('success', 'Subdomain and its active plan have been successfully cancelled and removed.');
    }

    public function renew(Subdomain $subdomain)
    {
        if ($subdomain->user_id !== Auth::id()) {
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
