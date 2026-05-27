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

    public function extractAndDeploy(Deployment $deployment, \App\Services\ServerProvisioningService $provisioningService)
    {
        if (!$deployment->subdomain) {
            return redirect()->back()->withErrors(['error' => 'Subdomain associated with this deployment no longer exists.']);
        }

        // Meningkatkan batas eksekusi
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        try {
            $subdomain = $deployment->subdomain;
            $zipFileName = "deployment_v" . $deployment->version . ".zip";

            // 1. Pemicu ekstraksi ZIP di cPanel
            \Log::info("Automated Deploy: Mengekstrak zip '{$zipFileName}' di subdomain '{$subdomain->full_domain}'...");
            $provisioningService->extractZipInSubdomain($subdomain, $zipFileName);

            // 2. Hapus file ZIP sementara di cPanel
            \Log::info("Automated Deploy: Menghapus zip '{$zipFileName}' dari cPanel...");
            $provisioningService->deleteFileInSubdomain($subdomain, $zipFileName);

            // 3. Deteksi tipe plan
            $payment = $subdomain->payments()->where('status', 'success')->latest()->first();
            if (!$payment) {
                $payment = $subdomain->user->payments()->with('plan')->where('status', 'success')->latest()->first();
            }
            $planType = $payment && $payment->plan ? $payment->plan->type : 'PHP';

            $postDeployNote = "Otomatis diekstrak dan dikonfigurasi oleh sistem Subly.";

            // 4. Operasi khusus pasca-deploy untuk NodeJS
            if (in_array($planType, ['NodeJS', 'Fullstack'])) {
                // Jalankan npm install
                try {
                    \Log::info("Automated Deploy: Menjalankan 'npm install' untuk '{$subdomain->full_domain}'...");
                    $provisioningService->installNpmModules($subdomain);
                } catch (\Exception $npmEx) {
                    \Log::warning("NPM install warn saat deploy: " . $npmEx->getMessage());
                    $postDeployNote .= " (Peringatan: npm install gagal dijalankan otomatis)";
                }

                // Restart aplikasi Node.js
                try {
                    \Log::info("Automated Deploy: Merestart NodeJS App untuk '{$subdomain->full_domain}'...");
                    $provisioningService->restartNodeJsApp($subdomain);
                } catch (\Exception $restartEx) {
                    \Log::warning("NodeJS App restart warn saat deploy: " . $restartEx->getMessage());
                }
            }

            // 5. Sinkronisasi variabel lingkungan (.env dan .htaccess)
            try {
                \Log::info("Automated Deploy: Mensinkronkan variabel lingkungan untuk '{$subdomain->full_domain}'...");
                $provisioningService->syncEnvironment($subdomain);
            } catch (\Exception $envEx) {
                \Log::warning("Environment sync warn saat deploy: " . $envEx->getMessage());
            }

            // 6. Tandai deployment sebagai sukses
            $deployment->update([
                'status' => 'success',
                'deployed_at' => now(),
                'admin_note' => $postDeployNote,
            ]);

            return redirect()->back()->with('success', "Deployment v{$deployment->version} berhasil diekstrak, dependencies dipasang, env disinkronkan, dan aplikasi dideploy secara otomatis!");
        } catch (\Exception $e) {
            \Log::error("Failed automated deployment: " . $e->getMessage());
            return redirect()->back()->withErrors(['error' => "Gagal mendeploy secara otomatis: " . $e->getMessage()]);
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
