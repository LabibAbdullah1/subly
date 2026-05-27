<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\AdminDeploymentNotification;
use Illuminate\Http\Request;
use App\Models\Deployment;
use App\Models\Subdomain;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class DeploymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $deployments = Deployment::whereHas('subdomain', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('subdomain')
            ->latest()
            ->paginate(15);

        return view('client.deployments.index', compact('deployments'));
    }

    public function store(Request $request)
    {
        // Increase limits for processing
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        if ($request->hasFile('zip_file') && !$request->file('zip_file')->isValid()) {
            return redirect()->back()->withErrors(['zip_file' => 'Upload failed. File might exceed server limits.']);
        }

        $request->validate([
            'subdomain_id' => 'required|exists:subdomains,id',
            'zip_file' => 'required|file|max:102400', // max 100MB (limit for store method)
            'notes' => 'nullable|string|max:255',
        ]);

        $subdomain = Subdomain::findOrFail($request->subdomain_id);
        if ($subdomain->user_id != Auth::id()) abort(403);
        if ($subdomain->status !== 'active' || $subdomain->isExpired()) {
            return redirect()->back()->withErrors(['subdomain_id' => 'This subdomain is currently inactive or expired. Please renew your plan.']);
        }

        try {
            $this->validateAndDeploy(
                $subdomain, 
                $request->file('zip_file')->getRealPath(), 
                $request->file('zip_file')->getClientOriginalName(),
                $request->notes
            );
            return redirect()->back()->with('success', 'Deployment ZIP uploaded successfully. It is now queued.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['zip_file' => $e->getMessage()]);
        }
    }

    public function uploadChunk(Request $request)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(600);

        $request->validate([
            'subdomain_id' => 'required|exists:subdomains,id',
            'chunk' => 'required|file',
            'upload_id' => 'required|string',
            'chunk_index' => 'required|integer',
            'total_chunks' => 'required|integer',
            'file_name' => 'required|string',
            'notes' => 'nullable|string|max:255',
        ]);

        $subdomain = Subdomain::findOrFail($request->subdomain_id);
        if ($subdomain->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        if ($subdomain->status !== 'active' || $subdomain->isExpired()) {
            return response()->json(['error' => 'Subdomain is currently inactive or expired. Please renew your plan.'], 403);
        }

        $uploadId = preg_replace('/[^A-Za-z0-9_\-]/', '', $request->upload_id);
        $tempDir = storage_path('app/chunks/' . $uploadId);
        
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $chunkPath = $tempDir . '/chunk_' . $request->chunk_index;
        $request->file('chunk')->move($tempDir, 'chunk_' . $request->chunk_index);

        // Check if all chunks are uploaded
        $chunks = glob($tempDir . '/chunk_*');
        if (count($chunks) == $request->total_chunks) {
            $finalPath = storage_path('app/chunks/' . $uploadId . '.zip');
            $out = fopen($finalPath, 'wb');
            
            for ($i = 0; $i < $request->total_chunks; $i++) {
                $chunkFile = $tempDir . '/chunk_' . $i;
                if (!file_exists($chunkFile)) {
                    fclose($out);
                    return response()->json(['error' => 'Missing chunk ' . $i], 400);
                }
                $in = fopen($chunkFile, 'rb');
                stream_copy_to_stream($in, $out);
                fclose($in);
            }
            fclose($out);

            // Cleanup
            $this->deleteDirectory($tempDir);

            try {
                $this->validateAndDeploy($subdomain, $finalPath, $request->file_name, $request->notes);
                if (file_exists($finalPath)) unlink($finalPath);
                return response()->json(['success' => true, 'message' => 'Upload complete and validated.']);
            } catch (\Exception $e) {
                if (file_exists($finalPath)) unlink($finalPath);
                return response()->json(['error' => $e->getMessage()], 422);
            }
        }

        return response()->json(['success' => true, 'message' => 'Chunk uploaded']);
    }

    private function validateAndDeploy(Subdomain $subdomain, $filePath, $originalName, $notes = null)
    {
        $user = Auth::user();
        
        // Plan Validation
        $payment = $subdomain->payments()->where('status', 'success')->latest()->first() 
                   ?? $user->payments()->with('plan')->where('status', 'success')->latest()->first();
        $plan = $payment ? $payment->plan : null;

        if (!$plan) {
            throw new \Exception('Could not detect an active subscription for this subdomain.');
        }

        // Storage Check
        $usedStorageBytes = 0;
        foreach ($subdomain->deployments as $deployment) {
            if ($deployment->zip_path && Storage::exists($deployment->zip_path)) {
                $usedStorageBytes += $deployment->zip_size > 0 ? $deployment->zip_size : Storage::size($deployment->zip_path);
            }
        }

        $zipSize = filesize($filePath);
        $extractedSize = 0;
        $forbiddenExtensions = ['exe', 'bat', 'sh', 'bin', 'msi', 'cgi'];
        
        $zip = new ZipArchive();
        if ($zip->open($filePath) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                $filePathInZip = $stat['name'];
                $extractedSize += $stat['size'];

                // Skip security check for files inside standard library folders 
                // as they often contain necessary binaries/scripts (e.g., node_modules/.bin)
                if (str_contains($filePathInZip, 'node_modules/') || str_contains($filePathInZip, 'vendor/')) {
                    continue;
                }

                $extension = strtolower(pathinfo($filePathInZip, PATHINFO_EXTENSION));
                if (in_array($extension, $forbiddenExtensions)) {
                    $zip->close();
                    throw new \Exception("Security error: Forbidden file type found inside ZIP ({$filePathInZip}).");
                }
            }
            $zip->close();
        } else {
            throw new \Exception('Could not read ZIP file metadata.');
        }

        $limitMB = $subdomain->storage_override_mb ?? $plan->max_storage_mb;
        if (($usedStorageBytes + $extractedSize) > ($limitMB * 1024 * 1024)) {
            $usedMB = round($usedStorageBytes / 1048576, 2);
            $newMB = round($extractedSize / 1048576, 2);
            throw new \Exception("Storage limit exceeded. Limit: $limitMB MB. Current: $usedMB MB. New site: $newMB MB.");
        }

        // Cleanup old queued/processing
        $subdomain->deployments()->whereIn('status', ['queued', 'processing'])->get()->each(function ($old) {
            if ($old->zip_path && Storage::exists($old->zip_path)) Storage::delete($old->zip_path);
            $old->delete();
        });

        // Storage Retention Policy: Keep only the last 2 successful deployments for rollback
        $successfulDeployments = $subdomain->deployments()
            ->where('status', 'success')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($successfulDeployments->count() >= 2) {
            $olderDeployments = $successfulDeployments->slice(2);
            foreach ($olderDeployments as $oldSuccess) {
                if ($oldSuccess->zip_path && Storage::exists($oldSuccess->zip_path)) {
                    Storage::delete($oldSuccess->zip_path);
                }
                $oldSuccess->delete();
            }
        }

        // Store file
        $newFileName = time() . '_' . preg_replace('/[^A-Za-z0-9\._\-]/', '', $originalName);
        $path = Storage::putFileAs('uploads/zips', new \Illuminate\Http\File($filePath), $newFileName);

        $deployment = Deployment::create([
            'subdomain_id' => $subdomain->id,
            'zip_path' => $path,
            'zip_size' => $zipSize,
            'extracted_size' => $extractedSize,
            'version' => $subdomain->deployments()->count() + 1,
            'status' => 'queued',
            'notes' => $notes,
        ]);

        // Automatically upload the ZIP file to cPanel subdomain root directory for the admin
        try {
            $localFullPath = Storage::path($path);
            $cpanelFileName = "deployment_v" . $deployment->version . ".zip";
            app(\App\Services\ServerProvisioningService::class)->uploadFileToSubdomain($subdomain, $localFullPath, $cpanelFileName);
        } catch (\Exception $e) {
            Log::error("Failed to automatically copy deployment ZIP to cPanel for {$subdomain->full_domain}: " . $e->getMessage());
        }

        // Notify admin via email about new deployment
        try {
            $deployment->load(['subdomain.user']);
            $adminEmail = config('mail.admin_email', env('MAIL_FROM_ADDRESS'));
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new AdminDeploymentNotification($deployment));
            }
        } catch (\Exception $e) {
            Log::error('Admin deployment email failed: ' . $e->getMessage());
        }
    }

    private function deleteDirectory($dir) {
        if (!file_exists($dir)) return true;
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->deleteDirectory("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}
