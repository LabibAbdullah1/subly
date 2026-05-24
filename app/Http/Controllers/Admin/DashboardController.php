<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Deployment;
use App\Models\Chat;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'Client')->count();
        $queuedDeployments = Deployment::where('status', 'queued')->count();
        $unreadChats = Chat::where('is_admin', false)->where('is_read', false)->count();
        $totalRevenue = Payment::where('status', 'success')->sum('amount');
        
        $recentDeployments = Deployment::with('subdomain.user')->latest()->take(5)->get();
        $recentChats = Chat::with('user')->where('is_admin', false)->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalUsers', 'queuedDeployments', 'unreadChats', 'totalRevenue', 'recentDeployments', 'recentChats'));
    }
}
