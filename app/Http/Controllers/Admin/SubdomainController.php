<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subdomain;
use App\Models\User;
use App\Models\Plan;
use App\Models\Payment;
use Illuminate\Http\Request;

class SubdomainController extends Controller
{
    public function index()
    {
        $subdomains = Subdomain::with('user')->latest()->paginate(15);
        return view('admin.subdomains.index', compact('subdomains'));
    }

    public function create()
    {
        $users = User::where('role', 'Client')->orderBy('name')->get();
        $plans = Plan::orderBy('price')->get();
        return view('admin.subdomains.create', compact('users', 'plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'name' => 'required|string|max:255|unique:subdomains,name',
            'status' => 'required|in:active,inactive',
        ]);

        $fullDomain = $validated['name'] . config('app.subdomain_suffix');
        $docRoot = '/public_html/' . $validated['name'];
        $plan = Plan::find($validated['plan_id']);

        $subdomain = Subdomain::create([
            'user_id' => $validated['user_id'],
            'name' => $validated['name'],
            'full_domain' => $fullDomain,
            'doc_root' => $docRoot,
            'status' => $validated['status'],
            'expired_at' => now()->addMonths($plan->duration_months),
        ]);

        // Create a system-approved payment linked to this subdomain
        Payment::create([
            'user_id' => $validated['user_id'],
            'plan_id' => $plan->id,
            'subdomain_id' => $subdomain->id,
            'transaction_id' => 'ADMIN-' . time() . '-' . $validated['user_id'],
            'snap_token' => 'admin-bypass',
            'amount' => 0,
            'status' => 'success',
        ]);

        app(\App\Services\ServerProvisioningService::class)->provisionSubdomain($subdomain);

        return redirect()->route('admin.subdomains.index')->with('success', 'Subdomain and Plan assignment created successfully and server provisioned.');
    }

    public function edit(Subdomain $subdomain)
    {
        $users = User::where('role', 'Client')->orderBy('name')->get();
        return view('admin.subdomains.edit', compact('subdomain', 'users'));
    }

    public function update(Request $request, Subdomain $subdomain)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255|unique:subdomains,name,' . $subdomain->id,
            'status' => 'required|in:active,inactive',
        ]);

        $fullDomain = $validated['name'] . config('app.subdomain_suffix');
        $docRoot = '/public_html/' . $validated['name'];

        $subdomain->update([
            'user_id' => $validated['user_id'],
            'name' => $validated['name'],
            'full_domain' => $fullDomain,
            'doc_root' => $docRoot,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.subdomains.index')->with('success', 'Subdomain updated successfully.');
    }

    public function destroy(Subdomain $subdomain)
    {
        $subdomain->delete();
        return redirect()->route('admin.subdomains.index')->with('success', 'Subdomain deleted successfully.');
    }
}
