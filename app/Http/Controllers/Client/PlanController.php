<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        // Get all active plans available for purchase
        $plans = Plan::where('is_active', true)->orderBy('price', 'asc')->get();
        return view('client.plans.index', compact('plans'));
    }
}
