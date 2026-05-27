<?php

namespace App\Services;

use App\Models\Subdomain;
use App\Models\Deployment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class GitDeploymentService
{
    protected ServerProvisioningService $provisioningService;

    public function __construct(ServerProvisioningService $provisioningService)
    {
        $this->provisioningService = $provisioningService;
    }

    /**
     * Parse GitHub Repository URL to get owner and repo name.
     */
    public function parseGithubUrl(string $url): array
    {
        if (preg_match('/github\.com\/([^\/]+)\/([^\/\.]+)/i', $url, $matches)) {
            return [
                'owner' => $matches[1],
                'repo' => $matches[2]
            ];
        }
        
        throw new \Exception("URL GitHub tidak valid. Harap gunakan format: https://github.com/username/repository");
    }

    /**
     * Download and deploy code from GitHub for a subdomain.
     */
    public function deploy(Subdomain $subdomain): Deployment
    {
        if (empty($subdomain->git_url) || empty($subdomain->git_branch)) {
            throw new \Exception("Repositori GitHub belum dikonfigurasi untuk subdomain ini.");
        }

        // Increase execution time and memory limits for large repositories
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $parsed = $this->parseGithubUrl($subdomain->git_url);
        $owner = $parsed['owner'];
        $repo = $parsed['repo'];
        $branch = $subdomain->git_branch;
        $token = $subdomain->git_token; // Encrypted automatically by Subdomain model cast

        // 1. Get latest commit info from GitHub (Optional/Informative)
        $commitMessage = 'Impor Git (Terbaru)';
        $commitHash = null;
        try {
            $apiClient = Http::withHeaders([
                'User-Agent' => 'Subly-Git-Engine',
                'Accept' => 'application/vnd.github+json'
            ]);
            
            if (!empty($token)) {
                $apiClient = $apiClient->withToken($token);
            }
            
            $apiResponse = $apiClient->timeout(10)->get("https://api.github.com/repos/{$owner}/{$repo}/commits/{$branch}");
            if ($apiResponse->successful()) {
                $commitHash = substr($apiResponse->json('sha'), 0, 7);
                $commitMessage = $apiResponse->json('commit.message');
                // Shorten message if it's too long
                if (strlen($commitMessage) > 100) {
                    $commitMessage = substr($commitMessage, 0, 97) . '...';
                }
            }
        } catch (\Exception $e) {
            Log::warning("Gagal mengambil info commit terbaru dari GitHub: " . $e->getMessage());
        }

        // 2. Download zipball from GitHub
        $downloadUrl = "https://api.github.com/repos/{$owner}/{$repo}/zipball/{$branch}";
        Log::info("Mengunduh repository dari GitHub: {$downloadUrl}");

        $downloadClient = Http::withHeaders([
            'User-Agent' => 'Subly-Git-Engine',
        ]);
        
        if (!empty($token)) {
            $downloadClient = $downloadClient->withToken($token);
        }

        $response = $downloadClient->timeout(120)->get($downloadUrl);

        if ($response->status() === 404) {
            throw new \Exception("Repositori atau Branch '{$branch}' tidak ditemukan. Jika repositori bersifat privat, pastikan Personal Access Token (PAT) Anda valid.");
        }
        
        if ($response->status() === 401) {
            throw new \Exception("Akses ditolak. Token Akses GitHub Anda tidak valid.");
        }

        if (!$response->successful()) {
            throw new \Exception("Gagal mengunduh file kode dari GitHub (HTTP Status: " . $response->status() . ")");
        }

        // 3. Local Temporary Folders
        $tempDir = storage_path('app/git_temp/' . Str::random(12));
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $tempZipPath = $tempDir . '/downloaded.zip';
        file_put_contents($tempZipPath, $response->body());
        
        $zipSize = filesize($tempZipPath);

        // 4. Extract and Repack
        $extractedFolder = $tempDir . '/extracted';
        mkdir($extractedFolder, 0777, true);

        $zip = new ZipArchive();
        if ($zip->open($tempZipPath) === true) {
            $zip->extractTo($extractedFolder);
            $zip->close();
        } else {
            $this->cleanUpLocal($tempDir);
            throw new \Exception("Gagal membaca file arsip ZIP dari GitHub.");
        }

        // Find the single top-level folder inside GitHub zipballs (format: owner-repo-hash)
        $subdirs = array_filter(glob($extractedFolder . '/*'), 'is_dir');
        if (empty($subdirs)) {
            $this->cleanUpLocal($tempDir);
            throw new \Exception("Arsip ZIP dari GitHub kosong atau tidak valid.");
        }
        $projectSourceRoot = reset($subdirs);

        // Calculate extracted size and check for security rules (forbidden extensions)
        $extractedSize = 0;
        $forbiddenExtensions = ['exe', 'bat', 'sh', 'bin', 'msi', 'cgi'];
        $filesIterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($projectSourceRoot, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($filesIterator as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $extractedSize += filesize($filePath);
                
                // Security check (ignoring vendor & node_modules)
                $relativePath = substr($filePath, strlen($projectSourceRoot) + 1);
                if (!str_contains($relativePath, 'node_modules/') && !str_contains($relativePath, 'vendor/')) {
                    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                    if (in_array($extension, $forbiddenExtensions)) {
                        $this->cleanUpLocal($tempDir);
                        throw new \Exception("Pelanggaran Keamanan: Ditemukan tipe file dilarang ({$relativePath}) di dalam repositori.");
                    }
                }
            }
        }

        // 5. Repack to a clean structure zip
        $cleanZipPath = $tempDir . '/repacked_deployment.zip';
        $cleanZip = new ZipArchive();
        
        if ($cleanZip->open($cleanZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($filesIterator as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($projectSourceRoot) + 1);
                    $cleanZip->addFile($filePath, $relativePath);
                }
            }
            $cleanZip->close();
        } else {
            $this->cleanUpLocal($tempDir);
            throw new \Exception("Gagal mengompres ulang berkas proyek.");
        }

        // 6. Deploy ZIP to Hosting Panel (cPanel)
        try {
            $cpanelFileName = "github_deployment_v" . (time()) . ".zip";
            Log::info("Mengunggah repacked GitHub ZIP ke cPanel...");
            
            // Upload
            $this->provisioningService->uploadFileToSubdomain($subdomain, $cleanZipPath, $cpanelFileName);
            
            // Extract
            Log::info("Mengekstrak GitHub ZIP di server cPanel...");
            $this->provisioningService->extractZipInSubdomain($subdomain, $cpanelFileName);
            
            // Delete ZIP
            Log::info("Menghapus file ZIP cPanel...");
            $this->provisioningService->deleteFileInSubdomain($subdomain, $cpanelFileName);
            
            // Sync Environment variables (.env & .htaccess)
            Log::info("Memicu sinkronisasi Environment Variables (.env)...");
            $this->provisioningService->syncEnvironment($subdomain);
            
        } catch (\Exception $e) {
            $this->cleanUpLocal($tempDir);
            throw new \Exception("Gagal menaruh kode ke server cPanel: " . $e->getMessage());
        }

        // 7. Cleanup local temp files
        $this->cleanUpLocal($tempDir);

        // 8. Create Successful Deployment Log in Database
        $version = $subdomain->deployments()->count() + 1;
        $notes = "GitHub Pull - Commit: " . ($commitHash ? $commitHash . ' | ' : '') . $commitMessage;
        
        $deployment = Deployment::create([
            'subdomain_id' => $subdomain->id,
            'zip_path' => 'git_deployments/' . $subdomain->name . '_' . time() . '.zip', // Dummy local path since it is processed on cPanel
            'zip_size' => $zipSize,
            'extracted_size' => $extractedSize,
            'version' => $version,
            'status' => 'success',
            'notes' => $notes,
            'deployed_at' => now(),
        ]);

        // 9. Update Subdomain git states
        $subdomain->update([
            'git_last_commit' => $commitHash ? "{$commitHash} - {$commitMessage}" : $commitMessage,
            'git_connected_at' => $subdomain->git_connected_at ?? now(),
        ]);

        return $deployment;
    }

    /**
     * Helper to clean up local temp folder.
     */
    protected function cleanUpLocal(string $dir): void
    {
        if (!file_exists($dir)) {
            return;
        }
        $this->deleteDirectory($dir);
    }

    /**
     * Delete directory recursively.
     */
    protected function deleteDirectory(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            (is_dir($path)) ? $this->deleteDirectory($path) : @unlink($path);
        }
        return @rmdir($dir);
    }
}
