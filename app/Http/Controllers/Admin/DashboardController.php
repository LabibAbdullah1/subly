<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Deployment;
use App\Models\Chat;
use App\Models\Payment;

use App\Models\Subdomain;
use App\Models\UserDatabase;

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

    /**
     * Display live disk and database resource monitoring for all client subdomains.
     * - Directory size: PHP recursive filesystem scan (reads actual files on server, same as before)
     * - Database size: cPanel UAPI Mysql::list_databases → information_schema fallback
     */
    public function diskUsage(\App\Services\ServerProvisioningService $provisioningService)
    {
        $subdomains = Subdomain::with(['user', 'userDatabases', 'payments.plan'])->get();

        $diskData = [];
        foreach ($subdomains as $subdomain) {
            $payment = $subdomain->payments()->where('status', 'success')->latest()->first();
            $plan = $payment ? $payment->plan : null;
            // Use per-subdomain override if admin has set one, else fall back to plan quota
            $maxStorageMB = $subdomain->storage_override_mb
                ?? ($plan ? $plan->max_storage_mb : 50);

            // 1. Directory size — PHP recursive scan of client's actual doc_root directory
            $realDirectoryBytes = 0;
            $latestSuccess = $subdomain->deployments()->where('status', 'success')->latest()->first();
            $docRoot = $subdomain->doc_root;

            if (is_dir($docRoot)) {
                try {
                    $files = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($docRoot, \RecursiveDirectoryIterator::SKIP_DOTS),
                        \RecursiveIteratorIterator::LEAVES_ONLY
                    );
                    foreach ($files as $file) {
                        if ($file->isFile()) {
                            $realDirectoryBytes += $file->getSize();
                        }
                    }
                } catch (\Exception $e) {
                    // Fallback to last deployment extracted size on scan failure
                    $realDirectoryBytes = $latestSuccess ? ($latestSuccess->extracted_size ?? 0) : 0;
                }
            } else {
                // Directory not yet created (pending deployment)
                $realDirectoryBytes = $latestSuccess ? ($latestSuccess->extracted_size ?? 0) : 0;
            }

            // 2. Database size — cPanel UAPI (Mysql::list_databases → information_schema fallback)
            $realDatabaseBytes = 0;
            $dbSizeIsLive = false;
            $database = $subdomain->userDatabases()->first();
            if ($database && !empty($database->db_name)) {
                // Primary: cPanel UAPI gives exact DB disk usage per database
                $realDatabaseBytes = $provisioningService->getCpanelDatabaseSize($database->db_name);
                if ($realDatabaseBytes > 0) {
                    $dbSizeIsLive = true;
                } else {
                    // Fallback: information_schema (works when Subly MySQL user has GRANT access)
                    try {
                        $result = \DB::select(
                            "SELECT COALESCE(SUM(data_length + index_length), 0) AS size FROM information_schema.TABLES WHERE table_schema = ?",
                            [$database->db_name]
                        );
                        if (!empty($result) && isset($result[0]->size) && $result[0]->size > 0) {
                            $realDatabaseBytes = (int) $result[0]->size;
                            $dbSizeIsLive = true;
                        }
                    } catch (\Exception $e) {
                        \Log::warning("information_schema fallback failed for {$subdomain->full_domain}: " . $e->getMessage());
                    }
                }
            }

            // 3. Combine
            $totalBytes   = $realDirectoryBytes + $realDatabaseBytes;
            $totalMB      = round($totalBytes / 1048576, 2);
            $dirMB        = round($realDirectoryBytes / 1048576, 2);
            $dbMB         = round($realDatabaseBytes / 1048576, 2);
            $usagePercent = $maxStorageMB > 0 ? min(100, round(($totalMB / $maxStorageMB) * 100, 2)) : 0;

            $diskData[] = [
                'subdomain'      => $subdomain,
                'user'           => $subdomain->user,
                'plan_name'      => $plan ? $plan->name : 'N/A',
                'max_storage_mb' => $maxStorageMB,
                'dir_bytes'      => $realDirectoryBytes,
                'db_bytes'       => $realDatabaseBytes,
                'total_bytes'    => $totalBytes,
                'dir_mb'         => $dirMB,
                'db_mb'          => $dbMB,
                'total_mb'       => $totalMB,
                'percent'        => $usagePercent,
                'db_size_live'   => $dbSizeIsLive,
            ];
        }

        return view('admin.disk.index', compact('diskData'));
    }
}
