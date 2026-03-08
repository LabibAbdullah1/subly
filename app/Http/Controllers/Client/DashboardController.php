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
        
        // Get the active plan and subscription details
        $payment = $user->payments()->where('status', 'success')->latest()->first();
        $plan = $payment ? $payment->plan : null;

        $subdomains = $user->subdomains()->with(['deployments', 'userDatabases'])->get();
        
        $reports = $user->reports()->latest()->get();
        
        $feedback = class_exists(\App\Models\Feedback::class) ? $user->feedback()->latest()->first() : null;

        return view('client.dashboard', compact('user', 'plan', 'payment', 'subdomains', 'reports', 'feedback'));
    }
}
