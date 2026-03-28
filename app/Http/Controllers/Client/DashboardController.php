<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get active payments (plans) that haven't expired and aren't linked to a subdomain yet
        $unusedPayments = $user->payments()->with('plan')->where('status', 'success')->whereNull('subdomain_id')->latest()->get()->filter(function ($p) {
            return $p->plan && $p->created_at->addMonths((int) $p->plan->duration_months)->isFuture();
        });

        $available_slots = $unusedPayments->count();
        $subdomains = $user->subdomains()->with(['deployments', 'userDatabases'])->get();
        
        return view('client.dashboard', compact('user', 'unusedPayments', 'available_slots', 'subdomains'));
    }

    public function portal(\App\Models\Subdomain $subdomain)
    {
        $user = Auth::user();
        if ($subdomain->user_id != $user->id) {
            abort(403);
        }

        // Specifically for this subdomain
        $subdomain->load(['deployments', 'userDatabases', 'payments.plan']);
        $payment = $subdomain->payments()->where('status', 'success')->latest()->first();
        
        // Fallback: If no payment linked directly to subdomain, try to find user's latest active payment
        if (!$payment) {
            $payment = $user->payments()->with('plan')->where('status', 'success')->latest()->first();
        }

        $plan = $payment ? $payment->plan : null;
        
        $feedbacks = class_exists(\App\Models\Feedback::class) ? $user->feedback()->get()->keyBy('plan_id') : collect();
        $purchasedPlans = $user->payments()->with('plan')->where('status', 'success')->get()->pluck('plan')->filter()->unique('id');

        $usedStorageBytes = 0;
        foreach ($subdomain->deployments as $deployment) {
            if ($deployment->zip_path && \Storage::exists($deployment->zip_path)) {
                $usedStorageBytes += \Storage::size($deployment->zip_path);
            }
        }
        
        // Convert to MB but keep precision for small files
        $usedStorageMB = round($usedStorageBytes / 1048576, 4);
        $usedStorageDisplay = $usedStorageBytes >= 1048576 
            ? round($usedStorageBytes / 1048576, 2) . ' MB' 
            : round($usedStorageBytes / 1024, 2) . ' KB';

        return view('client.portal', compact('user', 'subdomain', 'plan', 'payment', 'feedbacks', 'purchasedPlans', 'usedStorageMB', 'usedStorageDisplay'));
    }
}
