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
        
        // Get active payments (plans) that aren't linked to a subdomain yet
        $unusedPayments = $user->payments()->with('plan')->where('status', 'success')->whereNull('subdomain_id')->latest()->get()->filter(function ($p) {
            return $p->plan;
        });

        $available_slots = $unusedPayments->count();
        $subdomains = $user->subdomains()->with(['deployments', 'userDatabases'])->get();
        
        return view('client.dashboard', compact('user', 'unusedPayments', 'available_slots', 'subdomains'));
    }

    public function portal(\App\Models\Subdomain $subdomain)
    {
        $user = Auth::user();
        if ($subdomain->user_id != $user->id) {
            abort(403);
        }

        // Specifically for this subdomain
        $subdomain->load(['deployments', 'userDatabases', 'payments.plan']);
        $payment = $subdomain->payments()->where('status', 'success')->latest()->first();
        
        // Fallback: If no payment linked directly to subdomain, try to find user's latest active payment
        if (!$payment) {
            $payment = $user->payments()->with('plan')->where('status', 'success')->latest()->first();
        }

        $plan = $payment ? $payment->plan : null;
        
        $feedbacks = class_exists(\App\Models\Feedback::class) ? $user->feedback()->get()->keyBy('plan_id') : collect();
        $purchasedPlans = $user->payments()->with('plan')->where('status', 'success')->get()->pluck('plan')->filter()->unique('id');

        // Calculate actual directory size of the subdomain's root directory on the server
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
                // Fallback to deployment log size on failure
                $realDirectoryBytes = $latestSuccess ? $latestSuccess->extracted_size : 0;
            }
        } else {
            // Fallback if directory does not exist yet or local simulation
            $realDirectoryBytes = $latestSuccess ? $latestSuccess->extracted_size : 0;
        }

        // Calculate actual database size used by the subdomain on MySQL
        // NOTE: On shared cPanel hosting, information_schema only returns rows for databases
        // the current MySQL user has GRANT access to. Client databases (sublymyi_xxx) may be
        // owned by a different cPanel MySQL user, so we fall back to an estimate.
        $realDatabaseBytes = 0;
        $database = $subdomain->userDatabases()->first();
        if ($database) {
            try {
                $dbName = $database->db_name;
                $result = \DB::select("SELECT COALESCE(SUM(data_length + index_length), 0) AS size FROM information_schema.TABLES WHERE table_schema = ?", [$dbName]);
                if (!empty($result) && isset($result[0]->size) && $result[0]->size > 0) {
                    $realDatabaseBytes = (int) $result[0]->size;
                } else {
                    // Fallback: estimate ~8% of extracted project size
                    if ($latestSuccess && $latestSuccess->extracted_size > 0) {
                        $realDatabaseBytes = (int) ($latestSuccess->extracted_size * 0.08);
                    }
                }
            } catch (\Exception $e) {
                \Log::warning("Gagal mengambil ukuran database untuk {$subdomain->full_domain}: " . $e->getMessage());
                $realDatabaseBytes = 0;
            }
        }

        // Combine both sizes for real storage limits tracking
        $totalBytes = $realDirectoryBytes + $realDatabaseBytes;
        
        $usedStorageMB = round($totalBytes / 1048576, 4);
        $usedStorageDisplay = $totalBytes >= 1048576 
            ? round($totalBytes / 1048576, 2) . ' MB' 
            : round($totalBytes / 1024, 2) . ' KB';

        return view('client.portal', compact(
            'user', 'subdomain', 'plan', 'payment', 'feedbacks', 'purchasedPlans', 
            'usedStorageMB', 'usedStorageDisplay', 'realDirectoryBytes', 'realDatabaseBytes'
        ));
    }
}
