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
        $pendingDeployments = Deployment::with(['subdomain' => function($q) {
                $q->withTrashed()->with('user');
            }])
            ->whereIn('status', ['queued', 'processing'])
            ->latest()
            ->get();

        $completedDeployments = Deployment::with(['subdomain' => function($q) {
                $q->withTrashed()->with('user');
            }])
            ->whereIn('status', ['success', 'error'])
            ->latest()
            ->paginate(15);
            
        return view('admin.deployments.index', compact('pendingDeployments', 'completedDeployments'));
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

    public function extractAndDeploy(Deployment $deployment)
    {
        if (!$deployment->subdomain) {
            return redirect()->back()->withErrors(['error' => 'Subdomain associated with this deployment no longer exists.']);
        }

        try {
            // Mark deployment as successful
            $deployment->update([
                'status' => 'success',
                'deployed_at' => now(),
                'admin_note' => 'Disetujui admin untuk diekstrak secara manual di cPanel.',
            ]);

            return redirect()->back()->with('success', "Deployment v{$deployment->version} berhasil disetujui! Status diubah menjadi Sukses. File ZIP aman berada di cPanel untuk Anda ekstrak secara manual.");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => "Gagal menyetujui deployment: " . $e->getMessage()]);
        }
    }

    public function download(Deployment $deployment)
    {
        if (!$deployment->zip_path || !\Storage::exists($deployment->zip_path)) {
            return redirect()->back()->withErrors(['error' => 'Deployment file not found on server.']);
        }

        $extension = pathinfo($deployment->zip_path, PATHINFO_EXTENSION);
        $filename = ($deployment->subdomain->name ?? 'deployment') . "_v" . $deployment->version . "." . $extension;

        return \Storage::download($deployment->zip_path, $filename);
    }

    public function setupDatabase(Deployment $deployment, \App\Services\ServerProvisioningService $provisioningService)
    {
        if (!$deployment->subdomain) {
            return redirect()->back()->with('error', 'Subdomain associated with this deployment no longer exists.');
        }

        $database = $provisioningService->provisionSubdomain($deployment->subdomain);

        return redirect()->back()->with('success', "Database '{$database->db_name}' successfully provisioned and configured on server.");
    }

    public function destroy(Deployment $deployment)
    {
        if ($deployment->zip_path && \Storage::exists($deployment->zip_path)) {
            \Storage::delete($deployment->zip_path);
        }
        
        $deployment->forceDelete();
        
        return redirect()->back()->with('success', 'Deployment record and associated files deleted successfully.');
    }
}
