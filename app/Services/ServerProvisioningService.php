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
                $dbPass = $this->generateSafeDbPassword();
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

            // 1. Create Subdomain in cPanel
            // cPanel UAPI expects path relative to home directory (e.g. client/subdomain_name)
            $cleanDir = ltrim(str_replace(["/home/{$cpanelUser}/", "home/{$cpanelUser}/"], '', $subdomain->doc_root), '/');

            $this->callCpanelApi('SubDomain', 'addsubdomain', [
                'domain' => $subdomain->name,
                'rootdomain' => $rootDomain,
                'dir' => $cleanDir,
            ]);
            Log::info("cPanel Subdomain created: {$subdomain->name}");

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

            $this->callCpanelApi('Mysql', 'create_database', [
                'name' => $cleanDbName,
            ]);
            Log::info("cPanel Database created: {$dbName}");

            // 3. Create MySQL Database User
            // Check if this MySQL user already exists for another database of the same owner
            $userExistsOnServer = UserDatabase::where('db_user', $database->db_user)
                ->where('id', '!=', $database->id)
                ->exists();

            if (!$userExistsOnServer) {
                $this->callCpanelApi('Mysql', 'create_user', [
                    'name' => $cleanUsrName,
                    'password' => $database->db_password,
                ]);
                Log::info("cPanel Database User created: {$dbUser}");
            } else {
                Log::info("cPanel Database User already exists on server, skipping user creation.", [
                    'db_user' => $dbUser,
                ]);
            }

            // 4. Assign All Privileges (Link User to Database)
            $this->callCpanelApi('Mysql', 'set_privileges_on_database', [
                'user' => $cleanUsrName,
                'database' => $cleanDbName,
                'privileges' => 'ALL',
            ]);
            Log::info("cPanel Database Privileges assigned.");

            // 5. Create default index.html for new subdomain
            try {
                $defaultHtml = $this->getDefaultHtmlTemplate($subdomain->full_domain);
                $this->callCpanelApi('Fileman', 'save_file_content', [
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
     * Deprovision Virtual Host, Database, and files for a Subdomain when unsubscribed/deleted.
     */
    public function deprovisionSubdomain(Subdomain $subdomain): void
    {
        if ($this->driver !== 'cpanel') {
            Log::info("SIMULATION: Subdomain {$subdomain->full_domain} deprovisioned.");
            return;
        }

        try {
            $cpanelUser = config('services.hosting_panel.username', 'sublymyi');
            $rootDomain = config('services.hosting_panel.root_domain', 'subly.my.id');

            // 1. Delete Subdomain from cPanel
            try {
                // Try dot format first
                $this->callCpanelApi('SubDomain', 'delsubdomain', [
                    'domain' => $subdomain->full_domain,
                ]);
                Log::info("cPanel Subdomain deleted (dot format): {$subdomain->full_domain}");
            } catch (\Exception $e) {
                if (str_contains($e->getMessage(), 'does not belong to') || str_contains($e->getMessage(), 'tidak dimiliki')) {
                    // Try underscore format for addon domains
                    $underscoreDomain = "{$subdomain->name}_{$rootDomain}";
                    Log::info("Retrying subdomain deletion with underscore format: {$underscoreDomain}");
                    $this->callCpanelApi('SubDomain', 'delsubdomain', [
                        'domain' => $underscoreDomain,
                    ]);
                    Log::info("cPanel Subdomain deleted (underscore format): {$underscoreDomain}");
                } else {
                    throw $e;
                }
            }

            // 2. Delete MySQL Database
            $database = $subdomain->userDatabases()->first();
            if ($database) {
                try {
                    $this->callCpanelApi('Mysql', 'delete_database', [
                        'name' => $database->db_name,
                    ]);
                    Log::info("cPanel Database deleted: {$database->db_name}");
                } catch (\Exception $e) {
                    Log::error("Failed to delete cPanel Database: " . $e->getMessage());
                }

                // 3. Delete MySQL User (only if no other subdomains of the same owner are using it)
                $otherUses = UserDatabase::where('db_user', $database->db_user)
                    ->where('id', '!=', $database->id)
                    ->exists();

                if (!$otherUses) {
                    try {
                        $this->callCpanelApi('Mysql', 'delete_user', [
                            'name' => $database->db_user,
                        ]);
                        Log::info("cPanel Database User deleted: {$database->db_user}");
                    } catch (\Exception $e) {
                        Log::error("Failed to delete cPanel Database User: " . $e->getMessage());
                    }
                } else {
                    Log::info("Database user {$database->db_user} is still used by other subdomains, skipping user deletion.");
                }

                // Delete database record
                $database->delete();
            }

            // 4. Delete Directory Files
            if ($subdomain->doc_root && str_starts_with($subdomain->doc_root, "/home/{$cpanelUser}/")) {
                Log::info("Deleting document root folder: {$subdomain->doc_root}");
                shell_exec("rm -rf " . escapeshellarg($subdomain->doc_root));
            }
        } catch (\Exception $e) {
            Log::error("cPanel deprovisioning failed for subdomain {$subdomain->full_domain}: " . $e->getMessage());
            throw $e;
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
            $cleanDir = ltrim(str_replace(["/home/{$cpanelUser}/", "home/{$cpanelUser}/"], '', $subdomain->doc_root), '/');

            // 1. Write suspended index.html
            $suspendedHtml = $this->getSuspendedHtmlTemplate($subdomain->full_domain);
            $this->callCpanelApi('Fileman', 'save_file_content', [
                'dir' => $cleanDir,
                'file' => 'index.html',
                'content' => $suspendedHtml,
            ]);

            // 2. Write .htaccess to redirect all requests to index.html
            $htaccess = "DirectoryIndex index.html\nRewriteEngine On\nRewriteCond %{REQUEST_URI} !/index.html$\nRewriteRule ^(.*)$ /index.html [R=302,L]\n";
            $this->callCpanelApi('Fileman', 'save_file_content', [
                'dir' => $cleanDir,
                'file' => '.htaccess',
                'content' => $htaccess,
            ]);

            Log::info("Subdomain {$subdomain->full_domain} suspended successfully on cPanel.");
        } catch (\Exception $e) {
            Log::error("Failed to suspend subdomain on cPanel: " . $e->getMessage());
        }
    }

    /**
     * Call cPanel API via HTTP, falling back to local CLI (uapi) if HTTP times out or fails.
     */
    protected function callCpanelApi(string $module, string $function, array $params): array
    {
        $cpanelUser = config('services.hosting_panel.username', 'sublymyi');
        $headers = [
            'Authorization' => "cpanel {$cpanelUser}:{$this->apiKey}"
        ];

        // 1. Try HTTP API first
        try {
            Log::info("Calling cPanel HTTP API: {$module}/{$function}", [
                'module' => $module,
                'function' => $function,
                'params' => array_merge($params, isset($params['password']) ? ['password' => '***'] : [], isset($params['content']) ? ['content' => '...length ' . strlen($params['content'])] : [])
            ]);

            $response = Http::withHeaders($headers)
                ->withoutVerifying()
                ->timeout(10) // Lower timeout to quickly trigger CLI fallback if blocked
                ->get("{$this->apiUrl}/execute/{$module}/{$function}", $params);

            if ($response->successful()) {
                $status = $response->json('status') ?? $response->json('result.status');
                if ($status == 1) {
                    return [
                        'success' => true,
                        'data' => $response->json()
                    ];
                }
            }
            $err = ($response->json('errors') ?? $response->json('result.errors') ?? [])[0] ?? $response->body();
            
            // Check if it's already exists
            if (preg_match('/(already exists|already configured|already exist|does exist)/i', $err)) {
                Log::info("cPanel HTTP API returned resource already exists, treating as success: {$err}");
                return [
                    'success' => true,
                    'data' => $response->json(),
                    'warning' => 'already_exists'
                ];
            }

            Log::warning("cPanel HTTP API returned error for {$module}/{$function}: " . $err . ". Trying local CLI fallback...");
        } catch (\Exception $e) {
            Log::warning("cPanel HTTP API connection failed for {$module}/{$function}: " . $e->getMessage() . ". Trying local CLI fallback...");
        }

        // 2. Fallback: Try local CLI (uapi command)
        try {
            $binaries = ['uapi', '/usr/bin/uapi', '/usr/local/cpanel/bin/uapi'];
            $output = null;
            $commandRun = '';
            
            foreach ($binaries as $bin) {
                // Securely construct the command with escapeshellarg
                $cmd = "{$bin} --output=json " . escapeshellarg($module) . " " . escapeshellarg($function);
                foreach ($params as $key => $value) {
                    $cmd .= " " . escapeshellarg($key) . "=" . escapeshellarg($value);
                }
                
                // Execute command
                Log::info("Running cPanel local CLI: {$bin} --output=json {$module} {$function}");
                $res = shell_exec($cmd);
                if ($res) {
                    $output = json_decode($res, true);
                    if ($output) {
                        $commandRun = $bin;
                        break;
                    }
                }
            }

            if ($output) {
                $status = $output['status'] ?? $output['result']['status'] ?? 0;
                if ($status == 1) {
                    Log::info("cPanel local CLI succeeded using: {$commandRun}");
                    return [
                        'success' => true,
                        'data' => $output
                    ];
                }
                
                $err = ($output['errors'] ?? $output['result']['errors'] ?? [])[0] ?? json_encode($output);
                if (preg_match('/(already exists|already configured|already exist|does exist)/i', $err)) {
                    Log::info("cPanel local CLI returned resource already exists, treating as success: {$err}");
                    return [
                        'success' => true,
                        'data' => $output,
                        'warning' => 'already_exists'
                    ];
                }
                
                throw new \Exception($err);
            }
            
            throw new \Exception("Local uapi binary not found, returned empty output, or shell_exec is disabled.");
        } catch (\Exception $e) {
            Log::error("cPanel CLI fallback failed: " . $e->getMessage());
            throw new \Exception("Koneksi cPanel gagal (HTTP Timeout & CLI Fallback Error: " . $e->getMessage() . ")");
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

    /**
     * Generate a secure database password without problematic symbols.
     */
    protected function generateSafeDbPassword(): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ($i = 0; $i < 20; $i++) {
            $password .= $chars[rand(0, strlen($chars) - 1)];
        }
        // Append digits to satisfy strength requirements
        return $password . rand(100, 999);
    }
}
