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
     */
    public function diskUsage()
    {
        $subdomains = Subdomain::with(['user', 'userDatabases', 'payments.plan'])->get();

        $diskData = [];
        foreach ($subdomains as $subdomain) {
            $payment = $subdomain->payments()->where('status', 'success')->latest()->first();
            $plan = $payment ? $payment->plan : null;
            $maxStorageMB = $plan ? $plan->max_storage_mb : 50;

            // 1. Calculate actual directory size of the subdomain's root directory on cPanel
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
                    $realDirectoryBytes = $latestSuccess ? $latestSuccess->extracted_size : 0;
                }
            } else {
                $realDirectoryBytes = $latestSuccess ? $latestSuccess->extracted_size : 0;
            }

            // 2. Calculate actual database size on MySQL
            $realDatabaseBytes = 0;
            $database = $subdomain->userDatabases()->first();
            if ($database) {
                try {
                    $dbName = $database->db_name;
                    $result = \DB::select("SELECT SUM(data_length + index_length) AS size FROM information_schema.TABLES WHERE table_schema = ?", [$dbName]);
                    if (!empty($result) && isset($result[0]->size)) {
                        $realDatabaseBytes = (int) $result[0]->size;
                    }
                } catch (\Exception $e) {
                    $realDatabaseBytes = 0;
                }
            }

            // Combine sizes for dashboard metrics
            $totalBytes = $realDirectoryBytes + $realDatabaseBytes;
            $totalMB = round($totalBytes / 1048576, 2);
            $dirMB = round($realDirectoryBytes / 1048576, 2);
            $dbMB = round($realDatabaseBytes / 1048576, 2);
            $usagePercent = $maxStorageMB > 0 ? min(100, round(($totalMB / $maxStorageMB) * 100, 2)) : 0;

            $diskData[] = [
                'subdomain' => $subdomain,
                'user' => $subdomain->user,
                'plan_name' => $plan ? $plan->name : 'N/A',
                'max_storage_mb' => $maxStorageMB,
                'dir_bytes' => $realDirectoryBytes,
                'db_bytes' => $realDatabaseBytes,
                'total_bytes' => $totalBytes,
                'dir_mb' => $dirMB,
                'db_mb' => $dbMB,
                'total_mb' => $totalMB,
                'percent' => $usagePercent
            ];
        }

        return view('admin.disk.index', compact('diskData'));
    }
}
