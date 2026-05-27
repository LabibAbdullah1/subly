<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Subdomain;
use App\Models\SubdomainEnv;
use App\Services\ServerProvisioningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubdomainEnvController extends Controller
{
    protected ServerProvisioningService $provisioningService;

    public function __construct(ServerProvisioningService $provisioningService)
    {
        $this->provisioningService = $provisioningService;
    }

    /**
     * Update environment variables from the interactive key-value form.
     */
    public function update(Request $request, Subdomain $subdomain)
    {
        if ($subdomain->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'keys' => 'nullable|array',
            'keys.*' => 'nullable|string|regex:/^[A-Z_][A-Z0-9_]*$/i|max:255',
            'values' => 'nullable|array',
            'values.*' => 'nullable|string|max:1000',
            'secrets' => 'nullable|array',
        ]);

        try {
            $keys = $request->input('keys', []);
            $values = $request->input('values', []);
            $secrets = $request->input('secrets', []); // Indexes that are secrets

            // Hapus envs lama terlebih dahulu
            $subdomain->envs()->delete();

            // Insert envs baru yang valid
            foreach ($keys as $index => $key) {
                if (empty($key)) continue;

                $val = $values[$index] ?? '';
                $isSecret = isset($secrets[$index]) && $secrets[$index] == '1';

                SubdomainEnv::create([
                    'subdomain_id' => $subdomain->id,
                    'key' => strtoupper(trim($key)),
                    'value' => $val,
                    'is_secret' => $isSecret,
                ]);
            }

            // Jalankan sinkronisasi ke cPanel server
            $this->provisioningService->syncEnvironment($subdomain);

            return redirect()->back()->with('success', 'Environment variables updated and synchronized to server successfully.');
        } catch (\Exception $e) {
            Log::error("Failed updating env for subdomain {$subdomain->full_domain}: " . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Gagal memperbarui environment: ' . $e->getMessage()]);
        }
    }

    /**
     * Update environment variables from raw text copy-paste.
     */
    public function updateRaw(Request $request, Subdomain $subdomain)
    {
        if ($subdomain->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'raw_env' => 'nullable|string|max:10000',
        ]);

        try {
            $rawText = $request->input('raw_env', '');
            $lines = explode("\n", $rawText);
            
            // Hapus envs lama
            $subdomain->envs()->delete();

            foreach ($lines as $line) {
                $line = trim($line);
                
                // Lewati baris kosong atau baris komentar
                if (empty($line) || str_starts_with($line, '#')) {
                    continue;
                }

                // Parse FORMAT KEY=VALUE
                if (str_contains($line, '=')) {
                    [$key, $value] = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value);

                    // Bersihkan tanda kutip luar dari value jika ada
                    if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
                        $value = substr($value, 1, -1);
                    } elseif (str_starts_with($value, "'") && str_ends_with($value, "'")) {
                        $value = substr($value, 1, -1);
                    }

                    // Validasi key agar sesuai regex
                    if (preg_match('/^[A-Z_][A-Z0-9_]*$/i', $key)) {
                        SubdomainEnv::create([
                            'subdomain_id' => $subdomain->id,
                            'key' => strtoupper($key),
                            'value' => $value,
                            'is_secret' => false, // Default false for raw upload, can be customized later
                        ]);
                    }
                }
            }

            // Jalankan sinkronisasi ke cPanel server
            $this->provisioningService->syncEnvironment($subdomain);

            return redirect()->back()->with('success', 'Raw environment variables successfully parsed and synchronized to server.');
        } catch (\Exception $e) {
            Log::error("Failed updating raw env for subdomain {$subdomain->full_domain}: " . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Gagal memproses file .env mentah: ' . $e->getMessage()]);
        }
    }
}
