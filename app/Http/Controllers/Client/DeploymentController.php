<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deployment;
use App\Models\Subdomain;
use Illuminate\Support\Facades\Auth;

class DeploymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'subdomain_id' => 'required|exists:subdomains,id',
            'zip_file' => 'required|file|mimes:zip|max:51200', // max 50MB
        ]);

        $user = $request->user();
        
        // Ensure user has an active plan (a successful payment)
        $hasActivePlan = $user->payments()->where('status', 'success')->exists();
        if (!$hasActivePlan) {
            return redirect()->route('client.plans.index')->withErrors(['subscription' => 'You must purchase a hosting plan before you can deploy any projects.']);
        }

        $subdomain = Subdomain::findOrFail($request->subdomain_id);

        if ($request->user()->cannot('update', $subdomain)) {
            abort(403);
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
