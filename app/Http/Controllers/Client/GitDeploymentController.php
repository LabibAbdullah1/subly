<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Subdomain;
use App\Services\GitDeploymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GitDeploymentController extends Controller
{
    protected GitDeploymentService $gitService;

    public function __construct(GitDeploymentService $gitService)
    {
        $this->gitService = $gitService;
    }

    /**
     * Connect a GitHub repository and perform the initial code import.
     */
    public function connect(Request $request, Subdomain $subdomain)
    {
        if ($subdomain->user_id != Auth::id()) {
            abort(403, 'Aksi tidak sah.');
        }

        if ($subdomain->status !== 'active' || $subdomain->isExpired()) {
            return redirect()->back()->withErrors(['git_url' => 'Subdomain Anda tidak aktif atau sudah kedaluwarsa.']);
        }

        $request->validate([
            'git_url' => 'required|url|string',
            'git_branch' => 'required|string|max:100',
            'git_token' => 'nullable|string|max:255',
        ]);

        try {
            // Save temporary git settings to test deployment
            $subdomain->update([
                'git_url' => $request->git_url,
                'git_branch' => $request->git_branch,
                'git_token' => $request->git_token,
                'git_connected_at' => now(),
            ]);

            // Execute deployment
            $this->gitService->deploy($subdomain);

            return redirect()->back()->with('success', 'Repositori GitHub berhasil dihubungkan dan kode berhasil diimpor pertama kali!');
            
        } catch (\Exception $e) {
            Log::error("Gagal menghubungkan repositori GitHub: " . $e->getMessage());

            // Reset Git settings on failure to avoid broken states
            $subdomain->update([
                'git_url' => null,
                'git_branch' => null,
                'git_token' => null,
                'git_last_commit' => null,
                'git_connected_at' => null,
            ]);

            return redirect()->back()->withErrors(['git_url' => 'Gagal menghubungkan repositori: ' . $e->getMessage()]);
        }
    }

    /**
     * Pull the latest changes from the connected GitHub repository (Git Pull).
     */
    public function pull(Subdomain $subdomain)
    {
        if ($subdomain->user_id != Auth::id()) {
            abort(403, 'Aksi tidak sah.');
        }

        if ($subdomain->status !== 'active' || $subdomain->isExpired()) {
            return redirect()->back()->withErrors(['git_pull' => 'Subdomain Anda tidak aktif atau sudah kedaluwarsa.']);
        }

        if (empty($subdomain->git_url)) {
            return redirect()->back()->withErrors(['git_pull' => 'Belum ada repositori yang terhubung ke subdomain ini.']);
        }

        try {
            $this->gitService->deploy($subdomain);

            return redirect()->back()->with('success', 'Kode proyek berhasil diperbarui langsung dari GitHub (Git Pull sukses)!');
            
        } catch (\Exception $e) {
            Log::error("Gagal melakukan Git Pull untuk subdomain {$subdomain->full_domain}: " . $e->getMessage());
            return redirect()->back()->withErrors(['git_pull' => 'Gagal menarik kode terbaru: ' . $e->getMessage()]);
        }
    }

    /**
     * Disconnect the GitHub repository from the subdomain.
     */
    public function disconnect(Subdomain $subdomain)
    {
        if ($subdomain->user_id != Auth::id()) {
            abort(403, 'Aksi tidak sah.');
        }

        try {
            $subdomain->update([
                'git_url' => null,
                'git_branch' => null,
                'git_token' => null,
                'git_last_commit' => null,
                'git_connected_at' => null,
            ]);

            return redirect()->back()->with('success', 'Koneksi repositori GitHub berhasil diputuskan.');
            
        } catch (\Exception $e) {
            Log::error("Gagal memutuskan repositori GitHub: " . $e->getMessage());
            return redirect()->back()->withErrors(['git_disconnect' => 'Gagal memutuskan repositori: ' . $e->getMessage()]);
        }
    }
}
