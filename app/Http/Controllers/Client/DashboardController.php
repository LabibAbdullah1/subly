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
            return $p->plan && $p->created_at->addMonths($p->plan->duration_months)->isFuture();
        });

        $available_slots = $unusedPayments->count();
        $payment = $unusedPayments->first();
        $plan = $payment ? $payment->plan : null;

        $subdomains = $user->subdomains()->with(['deployments', 'userDatabases'])->get();
        
        $reports = $user->reports()->latest()->get();
        
        $feedback = class_exists(\App\Models\Feedback::class) ? $user->feedback()->latest()->first() : null;

        return view('client.dashboard', compact('user', 'plan', 'payment', 'unusedPayments', 'available_slots', 'subdomains', 'reports', 'feedback'));
    }
}
