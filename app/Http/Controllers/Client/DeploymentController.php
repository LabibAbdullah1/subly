<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deployment;
use App\Models\Subdomain;
use Illuminate\Support\Facades\Auth;

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
        // Explicitly check for upload errors before validation
        if ($request->hasFile('zip_file') && !$request->file('zip_file')->isValid()) {
            $error = $request->file('zip_file')->getError();
            $errorMessage = match ($error) {
                UPLOAD_ERR_INI_SIZE => 'File too large for server configuration (php.ini). Please check upload_max_filesize and post_max_size.',
                UPLOAD_ERR_PARTIAL => 'File upload was incomplete.',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                default => 'Upload failed with error code: ' . $error,
            };
            
            \Log::error('Deployment upload failed pre-validation', ['error' => $error, 'user' => Auth::id()]);
            return redirect()->back()->withErrors(['zip_file' => $errorMessage]);
        }

        \Log::info('Deployment upload attempt', [
            'user' => Auth::id(),
            'has_file' => $request->hasFile('zip_file'),
            'file_name' => $request->file('zip_file') ? $request->file('zip_file')->getClientOriginalName() : 'none',
            'mime' => $request->file('zip_file') ? $request->file('zip_file')->getMimeType() : 'none',
        ]);

        $request->validate([
            'subdomain_id' => 'required|exists:subdomains,id',
            'zip_file' => 'required|file|max:51200', // max 50MB
        ]);

        $user = $request->user();
        
        // Rate Limiting: Max 3 deployments per day
        $deploymentsToday = Deployment::whereHas('subdomain', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereDate('created_at', now()->toDateString())
            ->count();

        if ($deploymentsToday >= 3) {
            return redirect()->back()->withErrors(['zip_file' => 'Daily deployment limit reached! (Max 3 deployments per day)']);
        }

        $subdomain = Subdomain::findOrFail($request->subdomain_id);

        if ($request->user()->cannot('update', $subdomain)) {
            abort(403);
        }

        // Find the specific plan for this subdomain to check limits
        $payment = $subdomain->payments()->where('status', 'success')->latest()->first();
        
        // Fallback: If no payment linked directly to subdomain, find user's latest active payment
        if (!$payment) {
            $payment = $user->payments()->with('plan')->where('status', 'success')->latest()->first();
        }

        $plan = $payment ? $payment->plan : null;

        if (!$plan) {
            return redirect()->back()->withErrors(['zip_file' => 'Could not detect an active subscription for this subdomain. Please ensure your plan is still active.']);
        }

        // Storage limit from plan
        if ($request->file('zip_file')->getSize() > ($plan->max_storage_mb * 1024 * 1024)) {
            return redirect()->back()->withErrors(['zip_file' => "File exceeds your plan storage limit of {$plan->max_storage_mb} MB."]);
        }

        // Spam prevention: Delete previous queued or processing deployments for this subdomain
        $oldDeployments = $subdomain->deployments()
            ->whereIn('status', ['queued', 'processing'])
            ->get();

        foreach ($oldDeployments as $old) {
            if ($old->zip_path && \Storage::exists($old->zip_path)) {
                \Storage::delete($old->zip_path);
            }
            $old->delete();
        }

        $path = $request->file('zip_file')->store('uploads/zips');

        Deployment::create([
            'subdomain_id' => $subdomain->id,
            'zip_path' => $path,
            'version' => $subdomain->deployments()->count() + 1,
            'status' => 'queued',
        ]);

        return redirect()->back()->with('success', 'Deployment ZIP uploaded successfully. It is now queued.');
    }
}
