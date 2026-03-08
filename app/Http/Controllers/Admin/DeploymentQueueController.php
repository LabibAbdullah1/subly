<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deployment;
use App\Models\UserDatabase;
use Illuminate\Http\Request;

class DeploymentQueueController extends Controller
{
    public function index()
    {
        $deployments = Deployment::with('subdomain.user')
            ->orderByRaw("CASE status WHEN 'queued' THEN 1 WHEN 'processing' THEN 2 WHEN 'error' THEN 3 WHEN 'success' THEN 4 ELSE 5 END")
            ->latest()
            ->paginate(15);
            
        return view('admin.deployments.index', compact('deployments'));
    }

    public function updateStatus(Request $request, Deployment $deployment)
    {
        $request->validate([
            'status' => 'required|in:queued,processing,success,error',
            'admin_note' => 'nullable|string'
        ]);

        $deployment->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
            'deployed_at' => $request->status === 'success' ? now() : $deployment->deployed_at,
        ]);

        return redirect()->back()->with('success', 'Deployment status updated successfully.');
    }

    public function setupDatabase(Request $request, Deployment $deployment)
    {
        $request->validate([
            'db_name' => 'required|string|max:255',
            'db_user' => 'required|string|max:255',
            'db_password' => 'required|string|max:255',
        ]);

        UserDatabase::updateOrCreate(
            ['subdomain_id' => $deployment->subdomain_id],
            [
                'db_name' => $request->db_name,
                'db_user' => $request->db_user,
                'db_password' => $request->db_password,
            ]
        );

        return redirect()->back()->with('success', 'Database credentials saved for this subdomain.');
    }
}
