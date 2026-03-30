<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::latest()->paginate(10);
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', array_keys(Plan::TYPES)),
            'price' => 'required|integer|min:0',
            'duration_months' => 'required|integer|min:1',
            'max_storage_mb' => 'required|integer|min:0',
            'max_databases' => 'required|integer|min:0',
            'description' => 'required|string',
            'is_active' => 'sometimes|boolean',
        ]);

        Plan::create([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'duration_months' => $request->duration_months,
            'max_storage_mb' => $request->max_storage_mb,
            'max_databases' => $request->max_databases,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);
        return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully.');
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', array_keys(Plan::TYPES)),
            'price' => 'required|integer|min:0',
            'duration_months' => 'required|integer|min:1',
            'max_storage_mb' => 'required|integer|min:0',
            'max_databases' => 'required|integer|min:0',
            'description' => 'required|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $plan->update($data);
        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted successfully.');
    }
}
