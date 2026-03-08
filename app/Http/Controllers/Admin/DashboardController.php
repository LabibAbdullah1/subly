<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Deployment;
use App\Models\Report;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'Client')->count();
        $queuedDeployments = Deployment::where('status', 'queued')->count();
        $openTickets = Report::where('status', 'open')->count();
        $totalRevenue = Payment::where('status', 'success')->sum('amount');
        
        $recentDeployments = Deployment::with('subdomain.user')->latest()->take(5)->get();
        $recentTickets = Report::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalUsers', 'queuedDeployments', 'openTickets', 'totalRevenue', 'recentDeployments', 'recentTickets'));
    }
}
