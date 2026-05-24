<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestCpanelConnection extends Command
{
    protected $signature = 'cpanel:test {--create : Uji coba membuat subdomain, database, user mysql, dan permission di cPanel} {--cleanup : Hapus subdomain, database, dan user hasil uji coba}';
    protected $description = 'Test connection to cPanel UAPI and provision/cleanup test subdomain/database';

    public function handle()
    {
        $this->info('Menguji koneksi ke cPanel UAPI...');

        $driver = config('services.hosting_panel.driver');
        $url = rtrim(config('services.hosting_panel.url'), '/');
        $username = config('services.hosting_panel.username');
        $apiKey = config('services.hosting_panel.api_key');
        $domain = config('services.hosting_panel.root_domain');

        $this->line("Driver: <fg=cyan>{$driver}</>");
        $this->line("URL: <fg=cyan>{$url}</>");
        $this->line("Username: <fg=cyan>{$username}</>");
        $this->line("Root Domain: <fg=cyan>{$domain}</>");

        if ($driver !== 'cpanel') {
            $this->warn("Driver saat ini diatur ke '{$driver}'. Ubah HOSTING_PANEL_DRIVER=cpanel di .env jika ingin menggunakan cPanel.");
            return;
        }

        if (empty($apiKey) || $apiKey === 'CPANEL_API_TOKEN') {
            $this->error("API Key cPanel belum diatur di .env (HOSTING_PANEL_KEY)!");
            return;
        }

        $headers = [
            'Authorization' => "cpanel {$username}:{$apiKey}"
        ];

        // --- OPTION: CLEANUP ---
        if ($this->option('cleanup')) {
            $this->info("\n--- MEMBERSIHKAN SUBDOMAIN & DATABASE HASIL UJI COBA CPANEL ---");

            $testSubName = 'testcli';
            $testSubdomain = "{$testSubName}.{$domain}";
            $testDbName = "{$username}_testdb";
            $testDbUser = "{$username}_testusr";

            // 1. Delete Subdomain
            $this->line("1. Menghapus subdomain <fg=cyan>{$testSubdomain}</>...");
            try {
                $subResponse = Http::withHeaders($headers)
                    ->withoutVerifying()
                    ->timeout(60)
                    ->get("{$url}/json-api/cpanel", [
                        'cpanel_jsonapi_user' => $username,
                        'cpanel_jsonapi_apiversion' => '2',
                        'cpanel_jsonapi_module' => 'SubDomain',
                        'cpanel_jsonapi_func' => 'delsubdomain',
                        'domain' => $testSubdomain,
                    ]);

                // cPanel API 2 returns root error key under "cpanelresult.error" or "cpanelresult.data.reason"
                $subStatus = $subResponse->json('cpanelresult.data.result') ?? $subResponse->json('status') ?? 0;
                $err = $subResponse->json('cpanelresult.data.reason') ?? $subResponse->body();

                if ($subResponse->successful() && ($subStatus == 1 || $subStatus == '1')) {
                    $this->info("   [SUKSES] Subdomain berhasil dihapus.");
                } elseif (str_contains($err, 'does not belong to') || str_contains($err, 'not belong to') || str_contains($err, 'tidak dimiliki')) {
                    $underscoreSubdomain = "{$testSubName}_{$domain}";
                    $this->warn("   [MENCOBA FORMAT UNDERSCORE] Gagal dengan format dot. Mencoba menghapus dengan format: {$underscoreSubdomain}...");
                    
                    $retryResponse = Http::withHeaders($headers)
                        ->withoutVerifying()
                        ->timeout(60)
                        ->get("{$url}/json-api/cpanel", [
                            'cpanel_jsonapi_user' => $username,
                            'cpanel_jsonapi_apiversion' => '2',
                            'cpanel_jsonapi_module' => 'SubDomain',
                            'cpanel_jsonapi_func' => 'delsubdomain',
                            'domain' => $underscoreSubdomain,
                        ]);

                    $retryStatus = $retryResponse->json('cpanelresult.data.result') ?? $retryResponse->json('status') ?? 0;
                    if ($retryResponse->successful() && ($retryStatus == 1 || $retryStatus == '1')) {
                        $this->info("   [SUKSES] Subdomain berhasil dihapus menggunakan format underscore.");
                    } else {
                        $err2 = $retryResponse->json('cpanelresult.data.reason') ?? $retryResponse->body();
                        $this->error("   [GAGAL] " . $err2);
                    }
                } else {
                    $this->error("   [GAGAL] " . $err);
                }
            } catch (\Exception $e) {
                $this->warn("   [TIMEOUT / ERROR] Gagal menghapus subdomain (mungkin server overload): " . $e->getMessage());
            }

            // 2. Delete Database
            $this->line("2. Menghapus database MySQL <fg=cyan>{$testDbName}</>...");
            try {
                $dbResponse = Http::withHeaders($headers)
                    ->withoutVerifying()
                    ->timeout(60)
                    ->get("{$url}/execute/Mysql/delete_database", [
                        'name' => $testDbName,
                    ]);

                $dbStatus = $dbResponse->json('status') ?? $dbResponse->json('result.status');
                if ($dbResponse->successful() && $dbStatus === 1) {
                    $this->info("   [SUKSES] Database berhasil dihapus.");
                } else {
                    $err = ($dbResponse->json('errors') ?? $dbResponse->json('result.errors') ?? [])[0] ?? $dbResponse->body();
                    $this->error("   [GAGAL] " . $err);
                }
            } catch (\Exception $e) {
                $this->warn("   [ERROR] Gagal menghapus database: " . $e->getMessage());
            }

            // 3. Delete Database User
            $this->line("3. Menghapus MySQL User <fg=cyan>{$testDbUser}</>...");
            try {
                $usrResponse = Http::withHeaders($headers)
                    ->withoutVerifying()
                    ->timeout(60)
                    ->get("{$url}/execute/Mysql/delete_user", [
                        'name' => $testDbUser,
                    ]);

                $usrStatus = $usrResponse->json('status') ?? $usrResponse->json('result.status');
                if ($usrResponse->successful() && $usrStatus === 1) {
                    $this->info("   [SUKSES] MySQL User berhasil dihapus.");
                    $this->info("\nPembersihan selesai! Semua resource uji coba telah dibersihkan.");
                } else {
                    $err = ($usrResponse->json('errors') ?? $usrResponse->json('result.errors') ?? [])[0] ?? $usrResponse->body();
                    $this->error("   [GAGAL] " . $err);
                }
            } catch (\Exception $e) {
                $this->warn("   [ERROR] Gagal menghapus user: " . $e->getMessage());
            }

            return;
        }

        // --- OPTION: CREATE / PROVISION ---
        if ($this->option('create')) {
            $this->info("\n--- PENGUJIAN PROVISIONING CPANEL (MEMBUAT SUBDOMAIN & DATABASE) ---");

            $testSubName = 'testcli';
            $testSubdomain = "{$testSubName}.{$domain}";
            $testDir = "client/{$testSubName}";
            $testDbName = "{$username}_testdb";
            $testDbUser = "{$username}_testusr";
            $testDbPass = 'P@ssw0rdTesting123!';

            // 1. Create Subdomain
            $this->line("1. Mencoba membuat subdomain <fg=cyan>{$testSubdomain}</> di folder <fg=cyan>{$testDir}</>...");
            try {
                $subResponse = Http::withHeaders($headers)
                    ->withoutVerifying()
                    ->timeout(60)
                    ->get("{$url}/execute/SubDomain/addsubdomain", [
                        'domain' => $testSubName,
                        'rootdomain' => $domain,
                        'dir' => $testDir,
                    ]);

                $subStatus = $subResponse->json('status') ?? $subResponse->json('result.status');
                if ($subResponse->successful() && $subStatus === 1) {
                    $this->info("   [SUKSES] Subdomain berhasil dibuat.");
                } else {
                    $err = ($subResponse->json('errors') ?? $subResponse->json('result.errors') ?? [])[0] ?? $subResponse->body();
                    $this->error("   [GAGAL] " . $err);
                }
            } catch (\Exception $e) {
                $this->warn("   [TIMEOUT / PERINGATAN] Pembuatan subdomain mungkin lambat/sudah ada: " . $e->getMessage());
            }

            // 2. Create Database
            $this->line("2. Mencoba membuat database MySQL <fg=cyan>{$testDbName}</>...");
            try {
                $dbResponse = Http::withHeaders($headers)
                    ->withoutVerifying()
                    ->timeout(60)
                    ->get("{$url}/execute/Mysql/create_database", [
                        'name' => $testDbName,
                    ]);

                $dbStatus = $dbResponse->json('status') ?? $dbResponse->json('result.status');
                if ($dbResponse->successful() && $dbStatus === 1) {
                    $this->info("   [SUKSES] Database berhasil dibuat.");
                } else {
                    $err = ($dbResponse->json('errors') ?? $dbResponse->json('result.errors') ?? [])[0] ?? $dbResponse->body();
                    $this->error("   [GAGAL] " . $err);
                }
            } catch (\Exception $e) {
                $this->warn("   [ERROR] " . $e->getMessage());
            }

            // 3. Create Database User
            $this->line("3. Mencoba membuat MySQL User <fg=cyan>{$testDbUser}</>...");
            try {
                $usrResponse = Http::withHeaders($headers)
                    ->withoutVerifying()
                    ->timeout(60)
                    ->get("{$url}/execute/Mysql/create_user", [
                        'name' => $testDbUser,
                        'password' => $testDbPass,
                    ]);

                $usrStatus = $usrResponse->json('status') ?? $usrResponse->json('result.status');
                if ($usrResponse->successful() && $usrStatus === 1) {
                    $this->info("   [SUKSES] MySQL User berhasil dibuat.");
                } else {
                    $err = ($usrResponse->json('errors') ?? $usrResponse->json('result.errors') ?? [])[0] ?? $usrResponse->body();
                    $this->error("   [GAGAL] " . $err);
                }
            } catch (\Exception $e) {
                $this->warn("   [ERROR] " . $e->getMessage());
            }

            // 4. Assign Privileges
            $this->line("4. Mencoba menghubungkan User ke Database...");
            try {
                $privResponse = Http::withHeaders($headers)
                    ->withoutVerifying()
                    ->timeout(60)
                    ->get("{$url}/execute/Mysql/set_privileges_on_database", [
                        'user' => $testDbUser,
                        'database' => $testDbName,
                        'privileges' => 'ALL PRIVILEGES',
                    ]);

                $privStatus = $privResponse->json('status') ?? $privResponse->json('result.status');
                if ($privResponse->successful() && $privStatus === 1) {
                    $this->info("   [SUKSES] Hak akses berhasil diberikan.");
                    $this->info("\nUji coba provisioning selesai! Silakan jalankan `php artisan cpanel:test --cleanup` untuk menghapusnya.");
                } else {
                    $err = ($privResponse->json('errors') ?? $privResponse->json('result.errors') ?? [])[0] ?? $privResponse->body();
                    $this->error("   [GAGAL] " . $err);
                }
            } catch (\Exception $e) {
                $this->warn("   [ERROR] " . $e->getMessage());
            }

            return;
        }

        // --- DEFAULT: LIST MAIN DOMAIN ---
        $this->info("\nMencoba terhubung ke endpoint cPanel UAPI (DomainInfo/list_domains)...");

        try {
            $response = Http::withHeaders($headers)
                ->withoutVerifying()
                ->timeout(15)
                ->get("{$url}/execute/DomainInfo/list_domains");

            $status = $response->json('status') ?? $response->json('result.status');
            $errors = $response->json('errors') ?? $response->json('result.errors') ?? [];
            $firstError = is_array($errors) ? ($errors[0] ?? null) : $errors;

            if ($response->successful() && $status === 1) {
                $this->info("KONEKSI BERHASIL! Autentikasi cPanel UAPI valid.");
                $mainDomain = $response->json('data.main_domain') ?? $response->json('result.data.main_domain') ?? 'OK';
                $this->line("Main Domain di cPanel: <fg=green>{$mainDomain}</>");
            } else {
                $this->error("KONEKSI GAGAL!");
                $this->error("HTTP Status: " . $response->status());
                $this->error("Respons cPanel: " . ($firstError ?? $response->body()));
            }
        } catch (\Exception $e) {
            $this->error("TERJADI ERROR SAAT MENGHUBUNGI SERVER:");
            $this->error($e->getMessage());
        }
    }
}
