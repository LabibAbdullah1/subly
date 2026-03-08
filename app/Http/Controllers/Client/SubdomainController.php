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

        // Check if user already has a subdomain
        if ($user->subdomains()->count() > 0) {
            return redirect()->route('client.index')->with('error', 'You already have an active subdomain.');
        }

        // Check if user has a successful payment
        $payment = $user->payments()->where('status', 'success')->latest()->first();
        if (!$payment) {
            return redirect()->route('client.index')->with('error', 'Please complete a payment first to claim your subdomain.');
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

        Subdomain::create([
            'user_id' => $user->id,
            'name' => $subdomainName,
            'full_domain' => $fullDomain,
            'doc_root' => $docRoot,
            'status' => 'active',
        ]);

        return redirect()->route('client.index')->with('success', "Congratulations! Your subdomain '{$fullDomain}' has been claimed successfully.");
    }
}
