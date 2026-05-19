<?php

namespace App\Services;

use App\Models\Subdomain;
use App\Models\UserDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ServerProvisioningService
{
    protected string $driver;
    protected string $apiUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->driver = config('services.hosting_panel.driver', 'log');
        $this->apiUrl = config('services.hosting_panel.url', 'https://panel.subly.my.id:8090/api');
        $this->apiKey = config('services.hosting_panel.api_key', '');
    }

    /**
     * Provision Virtual Host and Database for a Subdomain.
     */
    public function provisionSubdomain(Subdomain $subdomain): UserDatabase
    {
        // Check if database credentials already exist for this subdomain
        $database = $subdomain->userDatabases()->first();

        if (!$database) {
            // Check if this owner already has an existing database (to reuse user/password)
            $existingDatabase = UserDatabase::whereHas('subdomain', function ($query) use ($subdomain) {
                $query->where('user_id', $subdomain->user_id);
            })->first();

            $dbName = 'sublymyi_' . strtolower(Str::random(8));

            if ($existingDatabase) {
                // Reuse existing database user & password for this owner
                $dbUser = $existingDatabase->db_user;
                $dbPass = $existingDatabase->db_password;
            } else {
                // Create new database user & password
                $dbUser = 'sublymyi_' . strtolower(Str::random(8));
                $dbPass = Str::password(16, true, true, true, false);
            }

            $database = UserDatabase::create([
                'subdomain_id' => $subdomain->id,
                'db_name' => $dbName,
                'db_user' => $dbUser,
                'db_password' => $dbPass,
            ]);
        }

        Log::info("Starting server provisioning for subdomain: {$subdomain->full_domain}", [
            'driver' => $this->driver,
            'db_name' => $database->db_name,
        ]);

        if ($this->driver === 'cpanel') {
            $this->provisionCpanel($subdomain, $database);
        } elseif ($this->driver === 'cyberpanel') {
            $this->provisionCyberPanel($subdomain, $database);
        } elseif ($this->driver === 'aapanel') {
            $this->provisionAaPanel($subdomain, $database);
        } elseif ($this->driver === 'webhook') {
            $this->provisionWebhook($subdomain, $database);
        } else {
            // 'log' driver for local development simulation
            Log::info("SIMULATION [Log Driver]: Virtual host and database successfully created on server.", [
                'domain' => $subdomain->full_domain,
                'doc_root' => $subdomain->doc_root,
                'database' => $database->db_name,
            ]);
        }

        return $database;
    }

    protected function provisionCpanel(Subdomain $subdomain, UserDatabase $database): void
    {
        try {
            $cpanelUser = config('services.hosting_panel.username', 'cpaneluser');
            $rootDomain = config('services.hosting_panel.root_domain', 'subly.my.id');

            $headers = [
                'Authorization' => "cpanel {$cpanelUser}:{$this->apiKey}"
            ];

            // 1. Create Subdomain in cPanel
            // cPanel UAPI expects path relative to home directory (e.g. client/subdomain_name)
            $cleanDir = ltrim(str_replace(["/home/{$cpanelUser}/", "home/{$cpanelUser}/"], '', $subdomain->doc_root), '/');

            $subResponse = Http::withHeaders($headers)
                ->withoutVerifying()
                ->timeout(60)
                ->get("{$this->apiUrl}/execute/SubDomain/addsubdomain", [
                    'domain' => $subdomain->name,
                    'rootdomain' => $rootDomain,
                    'dir' => $cleanDir,
                ]);

            $status = $subResponse->json('status') ?? $subResponse->json('result.status');
            $errors = $subResponse->json('errors') ?? $subResponse->json('result.errors') ?? [];
            $firstErr = is_array($errors) ? ($errors[0] ?? null) : $errors;

            if (!$subResponse->successful() || $status === 0) {
                $err = $firstErr ?? $subResponse->body();
                Log::error("cPanel Subdomain creation failed: " . $err);
                throw new \Exception("Gagal membuat subdomain di cPanel: " . $err);
            }
            Log::info("cPanel Subdomain created: " . $subResponse->body());

            // 2. Create MySQL Database
            // In cPanel, database and user names must begin with cpanel_username + _
            $cleanDbName = str_replace(["{$cpanelUser}_", 'sublymyi_', 'subly_', 'usr_'], '', $database->db_name);
            $cleanUsrName = str_replace(["{$cpanelUser}_", 'sublymyi_', 'usr_', 'subly_'], '', $database->db_user);

            $dbName = substr("{$cpanelUser}_{$cleanDbName}", 0, 64);
            $dbUser = substr("{$cpanelUser}_{$cleanUsrName}", 0, 32);

            // Update local DB record with prefixed cPanel names
            $database->update([
                'db_name' => $dbName,
                'db_user' => $dbUser,
            ]);

            $dbCreateResponse = Http::withHeaders($headers)
                ->withoutVerifying()
                ->timeout(60)
                ->get("{$this->apiUrl}/execute/Mysql/create_database", [
                    'name' => $dbName,
                ]);

            $dbStatus = $dbCreateResponse->json('status') ?? $dbCreateResponse->json('result.status');
            $dbErrors = $dbCreateResponse->json('errors') ?? $dbCreateResponse->json('result.errors') ?? [];
            $dbFirstErr = is_array($dbErrors) ? ($dbErrors[0] ?? null) : $dbErrors;

            if (!$dbCreateResponse->successful() || $dbStatus === 0) {
                $err = $dbFirstErr ?? $dbCreateResponse->body();
                Log::error("cPanel Database creation failed: " . $err);
                throw new \Exception("Gagal membuat database di cPanel: " . $err);
            }
            Log::info("cPanel Database created: " . $dbCreateResponse->body());

            // 3. Create MySQL Database User
            // Check if this MySQL user already exists for another database of the same owner
            $userExistsOnServer = UserDatabase::where('db_user', $database->db_user)
                ->where('id', '!=', $database->id)
                ->exists();

            if (!$userExistsOnServer) {
                $usrCreateResponse = Http::withHeaders($headers)
                    ->withoutVerifying()
                    ->timeout(60)
                    ->get("{$this->apiUrl}/execute/Mysql/create_user", [
                        'name' => $dbUser,
                        'password' => $database->db_password,
                    ]);

                $usrStatus = $usrCreateResponse->json('status') ?? $usrCreateResponse->json('result.status');
                $usrErrors = $usrCreateResponse->json('errors') ?? $usrCreateResponse->json('result.errors') ?? [];
                $usrFirstErr = is_array($usrErrors) ? ($usrErrors[0] ?? null) : $usrErrors;

                if (!$usrCreateResponse->successful() || $usrStatus === 0) {
                    $err = $usrFirstErr ?? $usrCreateResponse->body();
                    Log::error("cPanel Database User creation failed: " . $err);
                    throw new \Exception("Gagal membuat user database di cPanel: " . $err);
                }
                Log::info("cPanel Database User created: " . $usrCreateResponse->body());
            } else {
                Log::info("cPanel Database User already exists on server, skipping user creation.", [
                    'db_user' => $dbUser,
                ]);
            }

            // 4. Assign All Privileges (Link User to Database)
            $privResponse = Http::withHeaders($headers)
                ->withoutVerifying()
                ->timeout(60)
                ->get("{$this->apiUrl}/execute/Mysql/set_privileges_on_database", [
                    'user' => $dbUser,
                    'database' => $dbName,
                    'privileges' => 'ALL PRIVILEGES',
                ]);

            $privStatus = $privResponse->json('status') ?? $privResponse->json('result.status');
            $privErrors = $privResponse->json('errors') ?? $privResponse->json('result.errors') ?? [];
            $privFirstErr = is_array($privErrors) ? ($privErrors[0] ?? null) : $privErrors;

            if (!$privResponse->successful() || $privStatus === 0) {
                $err = $privFirstErr ?? $privResponse->body();
                Log::error("cPanel Database Privileges assignment failed: " . $err);
                throw new \Exception("Gagal memberikan hak akses database di cPanel: " . $err);
            }
            Log::info("cPanel Database Privileges assigned: " . $privResponse->body());

            // 5. Create default index.html for new subdomain
            try {
                $defaultHtml = $this->getDefaultHtmlTemplate($subdomain->full_domain);
                Http::withHeaders($headers)
                    ->withoutVerifying()
                    ->timeout(30)
                    ->get("{$this->apiUrl}/execute/Fileman/save_file_content", [
                        'dir' => $cleanDir,
                        'file' => 'index.html',
                        'content' => $defaultHtml,
                    ]);
                Log::info("Default index.html created for subdomain: {$subdomain->full_domain}");
            } catch (\Exception $e) {
                Log::error("Failed to create default index.html: " . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error("cPanel provisioning failed: " . $e->getMessage());
            throw $e;
        }
    }

    protected function provisionCyberPanel(Subdomain $subdomain, UserDatabase $database): void
    {
        try {
            // CyberPanel API endpoint for website creation
            $response = Http::timeout(15)->post("{$this->apiUrl}/createWebsite", [
                'apiKey' => $this->apiKey,
                'domainName' => $subdomain->full_domain,
                'phpSelection' => 'PHP 8.2',
                'docRoot' => $subdomain->doc_root,
                'ssl' => 1,
            ]);

            if ($response->successful()) {
                Log::info("CyberPanel VHost created: " . $response->body());
            }

            // CyberPanel API endpoint for database creation
            $dbResponse = Http::timeout(15)->post("{$this->apiUrl}/createDatabase", [
                'apiKey' => $this->apiKey,
                'domainName' => $subdomain->full_domain,
                'dbName' => $database->db_name,
                'dbUser' => $database->db_user,
                'dbPassword' => $database->db_password,
            ]);

            if ($dbResponse->successful()) {
                Log::info("CyberPanel Database provisioned: " . $dbResponse->body());
            }
        } catch (\Exception $e) {
            Log::error("CyberPanel provisioning failed: " . $e->getMessage());
        }
    }

    protected function provisionAaPanel(Subdomain $subdomain, UserDatabase $database): void
    {
        try {
            // aaPanel API request signature & timestamp
            $requestTime = time();
            $token = md5($requestTime . '' . md5($this->apiKey));

            $response = Http::timeout(15)->post("{$this->apiUrl}/site?action=AddSite", [
                'request_time' => $requestTime,
                'request_token' => $token,
                'webname' => json_encode(['domain' => $subdomain->full_domain, 'domainlist' => []]),
                'path' => '/www/wwwroot/' . $subdomain->name,
                'type_id' => 0,
                'type' => 'PHP',
                'version' => '82',
                'port' => '80',
                'ps' => 'Subly Managed App',
            ]);

            if ($response->successful()) {
                Log::info("aaPanel VHost created: " . $response->body());
            }

            $dbResponse = Http::timeout(15)->post("{$this->apiUrl}/database?action=AddDatabase", [
                'request_time' => $requestTime,
                'request_token' => $token,
                'name' => $database->db_name,
                'db_user' => $database->db_user,
                'password' => $database->db_password,
                'address' => 'localhost',
                'ps' => 'Subly Managed DB',
            ]);

            if ($dbResponse->successful()) {
                Log::info("aaPanel Database provisioned: " . $dbResponse->body());
            }
        } catch (\Exception $e) {
            Log::error("aaPanel provisioning failed: " . $e->getMessage());
        }
    }

    protected function provisionWebhook(Subdomain $subdomain, UserDatabase $database): void
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->timeout(15)->post($this->apiUrl, [
                'event' => 'subdomain.provision',
                'subdomain' => [
                    'id' => $subdomain->id,
                    'name' => $subdomain->name,
                    'full_domain' => $subdomain->full_domain,
                    'doc_root' => $subdomain->doc_root,
                ],
                'database' => [
                    'db_name' => $database->db_name,
                    'db_user' => $database->db_user,
                    'db_password' => $database->db_password,
                ],
            ]);

            if ($response->successful()) {
                Log::info("Webhook provisioning triggered successfully.");
            }
        } catch (\Exception $e) {
            Log::error("Webhook provisioning failed: " . $e->getMessage());
        }
    }

    /**
     * Suspend a subdomain by writing suspended landing page and .htaccess redirect.
     */
    public function suspendSubdomain(Subdomain $subdomain): void
    {
        if ($this->driver !== 'cpanel') {
            Log::info("SIMULATION: Subdomain {$subdomain->full_domain} suspended.");
            return;
        }

        try {
            $cpanelUser = config('services.hosting_panel.username', 'cpaneluser');
            $headers = [
                'Authorization' => "cpanel {$cpanelUser}:{$this->apiKey}"
            ];

            $cleanDir = ltrim(str_replace(["/home/{$cpanelUser}/", "home/{$cpanelUser}/"], '', $subdomain->doc_root), '/');

            // 1. Write suspended index.html
            $suspendedHtml = $this->getSuspendedHtmlTemplate($subdomain->full_domain);
            Http::withHeaders($headers)
                ->withoutVerifying()
                ->timeout(30)
                ->get("{$this->apiUrl}/execute/Fileman/save_file_content", [
                    'dir' => $cleanDir,
                    'file' => 'index.html',
                    'content' => $suspendedHtml,
                ]);

            // 2. Write .htaccess to redirect all requests to index.html
            $htaccess = "DirectoryIndex index.html\nRewriteEngine On\nRewriteCond %{REQUEST_URI} !/index.html$\nRewriteRule ^(.*)$ /index.html [R=302,L]\n";
            Http::withHeaders($headers)
                ->withoutVerifying()
                ->timeout(30)
                ->get("{$this->apiUrl}/execute/Fileman/save_file_content", [
                    'dir' => $cleanDir,
                    'file' => '.htaccess',
                    'content' => $htaccess,
                ]);

            Log::info("Subdomain {$subdomain->full_domain} suspended successfully on cPanel.");
        } catch (\Exception $e) {
            Log::error("Failed to suspend subdomain on cPanel: " . $e->getMessage());
        }
    }

    protected function getDefaultHtmlTemplate(string $domainName): string
    {
        return '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subdomain Aktif - Subly</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: \'Inter\', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #0B0F19;
            color: #F3F4F6;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
        }
        .container {
            max-width: 600px;
            padding: 2.5rem;
            background: rgba(17, 24, 39, 0.7);
            border: 1px solid rgba(94, 106, 210, 0.2);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 50px rgba(94, 106, 210, 0.1);
            backdrop-filter: blur(12px);
        }
        .icon {
            width: 80px;
            height: 80px;
            background: rgba(94, 106, 210, 0.1);
            border: 1px solid rgba(94, 106, 210, 0.3);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .icon svg {
            width: 40px;
            height: 40px;
            color: #5E6AD2;
        }
        h1 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: #FFFFFF;
        }
        p {
            color: #9CA3AF;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .btn {
            display: inline-block;
            background: #5E6AD2;
            color: #FFFFFF;
            padding: 0.8rem 1.8rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(94, 106, 210, 0.4);
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(94, 106, 210, 0.6);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h1>Subdomain Anda Telah Aktif!</h1>
        <p>Selamat! Subdomain <strong>' . htmlspecialchars($domainName) . '</strong> berhasil dibuat dan siap digunakan. Silakan unggah file proyek Anda untuk mulai membangun website Anda.</p>
        <a href="https://subly.my.id/dashboard" class="btn">Kembali ke Dashboard</a>
    </div>
</body>
</html>';
    }

    protected function getSuspendedHtmlTemplate(string $domainName): string
    {
        return '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subdomain Ditangguhkan - Subly</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: \'Inter\', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #0F0A0A;
            color: #F3F4F6;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
        }
        .container {
            max-width: 600px;
            padding: 2.5rem;
            background: rgba(24, 15, 15, 0.7);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 50px rgba(239, 68, 68, 0.1);
            backdrop-filter: blur(12px);
        }
        .icon {
            width: 80px;
            height: 80px;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .icon svg {
            width: 40px;
            height: 40px;
            color: #EF4444;
        }
        h1 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: #FFFFFF;
        }
        p {
            color: #D1D5DB;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .btn {
            display: inline-block;
            background: #EF4444;
            color: #FFFFFF;
            padding: 0.8rem 1.8rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.6);
        }
        .contact-info {
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: #9CA3AF;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h1>Subdomain Ditangguhkan (Suspended)</h1>
        <p>Masa aktif subdomain <strong>' . htmlspecialchars($domainName) . '</strong> telah berakhir. Silakan lakukan perpanjangan layanan untuk mengaktifkannya kembali.</p>
        <a href="https://subly.my.id/dashboard" class="btn">Perpanjang Layanan Sekarang</a>
        <div class="contact-info">
            Butuh bantuan? Silakan hubungi admin atau customer service kami.
        </div>
    </div>
</body>
</html>';
    }
}
