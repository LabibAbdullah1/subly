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
                'name' => $dbName,
            ]);
            Log::info("cPanel Database created: {$dbName}");

            // 3. Create MySQL Database User
            // Check if this MySQL user already exists for another database of the same owner
            $userExistsOnServer = UserDatabase::where('db_user', $database->db_user)
                ->where('id', '!=', $database->id)
                ->exists();

            if (!$userExistsOnServer) {
                $this->callCpanelApi('Mysql', 'create_user', [
                    'name' => $dbUser,
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
                'user' => $dbUser,
                'database' => $dbName,
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
                    try {
                        $this->callCpanelApi('SubDomain', 'delsubdomain', [
                            'domain' => $underscoreDomain,
                        ]);
                        Log::info("cPanel Subdomain deleted (underscore format): {$underscoreDomain}");
                    } catch (\Exception $e2) {
                        Log::error("Failed to delete subdomain in underscore format: " . $e2->getMessage());
                    }
                } else {
                    Log::error("Failed to delete subdomain: " . $e->getMessage());
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
                try {
                    $otherUses = UserDatabase::where('db_user', $database->db_user)
                        ->where('id', '!=', $database->id)
                        ->exists();

                    if (!$otherUses) {
                        $this->callCpanelApi('Mysql', 'delete_user', [
                            'name' => $database->db_user,
                        ]);
                        Log::info("cPanel Database User deleted: {$database->db_user}");
                    } else {
                        Log::info("Database user {$database->db_user} is still used by other subdomains, skipping user deletion.");
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to delete cPanel Database User: " . $e->getMessage());
                }

                // Delete database record permanently
                $database->forceDelete();
            }

            // 4. Delete Directory Files
            try {
                if ($subdomain->doc_root && str_contains($subdomain->doc_root, "/home/{$cpanelUser}/")) {
                    Log::info("Deleting document root folder recursively: {$subdomain->doc_root}");
                    $this->deleteDirectoryRecursively($subdomain->doc_root);
                }
            } catch (\Exception $e) {
                Log::error("Failed to delete document root folder: " . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error("cPanel deprovisioning failed for subdomain {$subdomain->full_domain}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Suspend a subdomain by writing suspended landing page and .htaccess redirect.
     */
    public function suspendSubdomain(Subdomain $subdomain, string $reason = 'inactive'): void
    {
        if ($this->driver !== 'cpanel') {
            Log::info("SIMULATION: Subdomain {$subdomain->full_domain} suspended. Reason: {$reason}");
            return;
        }

        try {
            $cpanelUser = config('services.hosting_panel.username', 'cpaneluser');
            $cleanDir = ltrim(str_replace(["/home/{$cpanelUser}/", "home/{$cpanelUser}/"], '', $subdomain->doc_root), '/');

            // 1. Rename existing index.html and .htaccess to .bak if they exist to protect user assets
            try {
                $this->callCpanelApi('Fileman', 'rename', [
                    'dir' => $cleanDir,
                    'file' => 'index.html',
                    'newname' => 'index.html.bak',
                ]);
                Log::info("cPanel: Backed up existing index.html to index.html.bak for {$subdomain->full_domain}");
            } catch (\Exception $e) {
                // Fail silently if file doesn't exist
            }

            try {
                $this->callCpanelApi('Fileman', 'rename', [
                    'dir' => $cleanDir,
                    'file' => '.htaccess',
                    'newname' => '.htaccess.bak',
                ]);
                Log::info("cPanel: Backed up existing .htaccess to .htaccess.bak for {$subdomain->full_domain}");
            } catch (\Exception $e) {
                // Fail silently if file doesn't exist
            }

            // 2. Write suspended index.html
            $suspendedHtml = $this->getSuspendedHtmlTemplate($subdomain->full_domain, $reason);
            $this->callCpanelApi('Fileman', 'save_file_content', [
                'dir' => $cleanDir,
                'file' => 'index.html',
                'content' => $suspendedHtml,
            ]);

            // 3. Write .htaccess to redirect all requests to index.html
            $htaccess = "DirectoryIndex index.html\nRewriteEngine On\nRewriteCond %{REQUEST_URI} !/index.html$\nRewriteRule ^(.*)$ /index.html [R=302,L]\n";
            $this->callCpanelApi('Fileman', 'save_file_content', [
                'dir' => $cleanDir,
                'file' => '.htaccess',
                'content' => $htaccess,
            ]);

            Log::info("Subdomain {$subdomain->full_domain} suspended successfully on cPanel. Reason: {$reason}");
        } catch (\Exception $e) {
            Log::error("Failed to suspend subdomain on cPanel: " . $e->getMessage());
        }
    }

    /**
     * Unsuspend a subdomain by removing the .htaccess and suspended index.html from cPanel.
     */
    public function unsuspendSubdomain(Subdomain $subdomain): void
    {
        if ($this->driver !== 'cpanel') {
            Log::info("SIMULATION: Subdomain {$subdomain->full_domain} unsuspended.");
            return;
        }

        try {
            $cpanelUser = config('services.hosting_panel.username', 'cpaneluser');
            $cleanDir = ltrim(str_replace(["/home/{$cpanelUser}/", "home/{$cpanelUser}/"], '', $subdomain->doc_root), '/');

            // 1. Delete suspended index.html
            try {
                $this->callCpanelApi('Fileman', 'unlink', [
                    'dir' => $cleanDir,
                    'file' => 'index.html',
                ]);
                Log::info("cPanel: Deleted suspended index.html for {$subdomain->full_domain}");
            } catch (\Exception $e) {
                Log::warning("Failed to delete suspended index.html for {$subdomain->full_domain}: " . $e->getMessage());
            }

            // 2. Delete .htaccess redirect
            try {
                $this->callCpanelApi('Fileman', 'unlink', [
                    'dir' => $cleanDir,
                    'file' => '.htaccess',
                ]);
                Log::info("cPanel: Deleted suspended .htaccess redirect for {$subdomain->full_domain}");
            } catch (\Exception $e) {
                Log::warning("Failed to delete suspended .htaccess for {$subdomain->full_domain}: " . $e->getMessage());
            }

            // 3. Restore backup index.html.bak and .htaccess.bak if they exist
            $restoredIndex = false;
            try {
                $this->callCpanelApi('Fileman', 'rename', [
                    'dir' => $cleanDir,
                    'file' => 'index.html.bak',
                    'newname' => 'index.html',
                ]);
                $restoredIndex = true;
                Log::info("cPanel: Restored index.html from index.html.bak for {$subdomain->full_domain}");
            } catch (\Exception $e) {
                // Fail silently if backup doesn't exist
            }

            try {
                $this->callCpanelApi('Fileman', 'rename', [
                    'dir' => $cleanDir,
                    'file' => '.htaccess.bak',
                    'newname' => '.htaccess',
                ]);
                Log::info("cPanel: Restored .htaccess from .htaccess.bak for {$subdomain->full_domain}");
            } catch (\Exception $e) {
                // Fail silently if backup doesn't exist
            }

            // 4. Restore default index.html template only if there are no successful deployments AND we didn't restore a backup
            $hasDeployments = $subdomain->deployments()->where('status', 'success')->exists();
            if (!$hasDeployments && !$restoredIndex) {
                try {
                    $defaultHtml = $this->getDefaultHtmlTemplate($subdomain->full_domain);
                    $this->callCpanelApi('Fileman', 'save_file_content', [
                        'dir' => $cleanDir,
                        'file' => 'index.html',
                        'content' => $defaultHtml,
                    ]);
                    Log::info("cPanel: Restored default index.html for {$subdomain->full_domain}");
                } catch (\Exception $e) {
                    Log::error("Failed to restore default index.html: " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error("cPanel unsuspension failed for subdomain {$subdomain->full_domain}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get the actual database disk usage in bytes from cPanel UAPI.
     * Uses Mysql::list_databases which returns disk_usage for each DB.
     * Falls back to 0 on any failure.
     */
    public function getCpanelDatabaseSize(string $dbName): int
    {
        $cpanelUser = config('services.hosting_panel.username', 'sublymyi');
        $apiUrl = config('services.hosting_panel.url', 'https://tersius.kencang.id:2083');
        $apiKey = config('services.hosting_panel.api_key', '');
        $driver = config('services.hosting_panel.driver', 'log');

        if ($driver !== 'cpanel' || empty($apiKey)) {
            return 0;
        }

        $headers = ['Authorization' => "cpanel {$cpanelUser}:{$apiKey}"];

        // Try HTTP UAPI first: Mysql::list_databases returns disk_usage per DB
        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders($headers)
                ->withoutVerifying()
                ->timeout(10)
                ->get("{$apiUrl}/execute/Mysql/list_databases");

            if ($response->successful() && $response->json('status') == 1) {
                $databases = $response->json('data') ?? [];
                foreach ($databases as $db) {
                    // cPanel returns disk_usage in bytes; db name may have cpanel prefix
                    if (isset($db['database']) && $db['database'] === $dbName) {
                        return (int) ($db['disk_usage'] ?? 0);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning("cPanel HTTP Mysql::list_databases failed: " . $e->getMessage());
        }

        // Fallback: Try CLI uapi
        try {
            $binaries = ['uapi', '/usr/bin/uapi', '/usr/local/cpanel/bin/uapi'];
            foreach ($binaries as $bin) {
                $cmd = "{$bin} --output=json Mysql list_databases";
                $res = shell_exec($cmd);
                if ($res) {
                    $output = json_decode($res, true);
                    if (isset($output['status']) && $output['status'] == 1) {
                        $databases = $output['data'] ?? [];
                        foreach ($databases as $db) {
                            if (isset($db['database']) && $db['database'] === $dbName) {
                                return (int) ($db['disk_usage'] ?? 0);
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning("cPanel CLI Mysql::list_databases failed: " . $e->getMessage());
        }

        return 0;
    }

    /**
     * Get actual directory size in bytes from cPanel UAPI Fileman::list_files with du.
     * Falls back to 0 if unavailable.
     */
    public function getCpanelDirectorySize(string $docRoot): int
    {
        $cpanelUser = config('services.hosting_panel.username', 'sublymyi');
        $apiUrl = config('services.hosting_panel.url', 'https://tersius.kencang.id:2083');
        $apiKey = config('services.hosting_panel.api_key', '');
        $driver = config('services.hosting_panel.driver', 'log');

        if ($driver !== 'cpanel' || empty($apiKey)) {
            // Local fallback for dev/log mode
            if (is_dir($docRoot)) {
                try {
                    $bytes = 0;
                    $files = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($docRoot, \RecursiveDirectoryIterator::SKIP_DOTS),
                        \RecursiveIteratorIterator::LEAVES_ONLY
                    );
                    foreach ($files as $file) {
                        if ($file->isFile()) {
                            $bytes += $file->getSize();
                        }
                    }
                    return $bytes;
                } catch (\Exception $e) {
                    return 0;
                }
            }
            return 0;
        }

        $headers = ['Authorization' => "cpanel {$cpanelUser}:{$apiKey}"];

        // Strip home prefix to get relative path for cPanel Fileman API
        $homePrefix = "/home/{$cpanelUser}/";
        $relDir = str_starts_with($docRoot, $homePrefix)
            ? substr($docRoot, strlen($homePrefix))
            : ltrim($docRoot, '/');

        // Try HTTP UAPI: Fileman::get_file_information with type check
        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders($headers)
                ->withoutVerifying()
                ->timeout(10)
                ->get("{$apiUrl}/execute/Fileman/get_file_information", [
                    'path' => $relDir,
                ]);

            if ($response->successful() && $response->json('status') == 1) {
                $data = $response->json('data') ?? [];
                // get_file_information returns 'size' for the directory (du output in bytes)
                if (isset($data['size']) && $data['size'] > 0) {
                    return (int) $data['size'];
                }
            }
        } catch (\Exception $e) {
            Log::warning("cPanel HTTP Fileman::get_file_information failed for {$relDir}: " . $e->getMessage());
        }

        // Fallback: local recursive scan (works on shared host where PHP can read the dir)
        if (is_dir($docRoot)) {
            try {
                $bytes = 0;
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($docRoot, \RecursiveDirectoryIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );
                foreach ($files as $file) {
                    if ($file->isFile()) {
                        $bytes += $file->getSize();
                    }
                }
                return $bytes;
            } catch (\Exception $e) {
                Log::warning("Local directory scan failed for {$docRoot}: " . $e->getMessage());
            }
        }

        return 0;
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

        $isApi2 = ($module === 'SubDomain' && $function === 'delsubdomain') || ($module === 'Lvemanager');

        // 1. Try HTTP API first
        try {
            Log::info("Calling cPanel HTTP API: {$module}/{$function} (API2: " . ($isApi2 ? 'yes' : 'no') . ")", [
                'module' => $module,
                'function' => $function,
                'params' => array_merge($params, isset($params['password']) ? ['password' => '***'] : [], isset($params['content']) ? ['content' => '...length ' . strlen($params['content'])] : [])
            ]);

            if ($isApi2) {
                // cPanel API 2 HTTP Endpoint
                $api2Params = array_merge([
                    'cpanel_jsonapi_user' => $cpanelUser,
                    'cpanel_jsonapi_apiversion' => '2',
                    'cpanel_jsonapi_module' => $module,
                    'cpanel_jsonapi_func' => $function,
                ], $params);

                $response = Http::withHeaders($headers)
                    ->withoutVerifying()
                    ->timeout(15)
                    ->get("{$this->apiUrl}/json-api/cpanel", $api2Params);

                if ($response->successful()) {
                    $status = $response->json('cpanelresult.data.result') ?? $response->json('status') ?? 0;
                    if ($status == 1 || $status == '1') {
                        return [
                            'success' => true,
                            'data' => $response->json()
                        ];
                    }
                }
                $err = $response->json('cpanelresult.data.reason') ?? $response->json('cpanelresult.error') ?? ($response->json('errors') ?? [])[0] ?? $response->body();
            } else {
                // UAPI HTTP Endpoint
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
            }

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

        // 2. Fallback: Try local CLI (uapi or cpapi2 command)
        try {
            $binaries = $isApi2
                ? ['cpapi2', '/usr/bin/cpapi2', '/usr/local/cpanel/bin/cpapi2']
                : ['uapi', '/usr/bin/uapi', '/usr/local/cpanel/bin/uapi'];
            $output = null;
            $commandRun = '';

            foreach ($binaries as $bin) {
                // Securely construct the command with escapeshellarg
                if ($isApi2) {
                    $cmd = "{$bin} --user=" . escapeshellarg($cpanelUser) . " " . escapeshellarg($module) . " " . escapeshellarg($function);
                } else {
                    $cmd = "{$bin} --output=json " . escapeshellarg($module) . " " . escapeshellarg($function);
                }
                foreach ($params as $key => $value) {
                    $cmd .= " " . escapeshellarg($key) . "=" . escapeshellarg($value);
                }

                // Execute command
                Log::info("Running cPanel local CLI: {$cmd}");
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
                $status = $isApi2
                    ? ($output['cpanelresult']['data']['result'] ?? $output['status'] ?? 0)
                    : ($output['status'] ?? $output['result']['status'] ?? 0);

                if ($status == 1 || $status == '1') {
                    Log::info("cPanel local CLI succeeded using: {$commandRun}");
                    return [
                        'success' => true,
                        'data' => $output
                    ];
                }

                $err = $isApi2
                    ? ($output['cpanelresult']['data']['reason'] ?? $output['cpanelresult']['error'] ?? json_encode($output))
                    : (($output['errors'] ?? $output['result']['errors'] ?? [])[0] ?? json_encode($output));

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

            throw new \Exception("Local CLI binary not found, returned empty output, or shell_exec is disabled.");
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

    protected function getSuspendedHtmlTemplate(string $domainName, string $reason = 'inactive'): string
    {
        $title = $reason === 'inactive' ? 'Subdomain Nonaktif (Inactive)' : 'Subdomain Ditangguhkan (Suspended)';

        if ($reason === 'inactive') {
            $message = 'Subdomain <strong>' . htmlspecialchars($domainName) . '</strong> saat ini sedang dinonaktifkan oleh administrator. Silakan hubungi admin untuk informasi lebih lanjut.';
            $buttonText = 'Hubungi Admin';
            $buttonUrl = 'https://subly.my.id/dashboard/chat';
        } else {
            $message = 'Masa aktif subdomain <strong>' . htmlspecialchars($domainName) . '</strong> telah berakhir. Silakan lakukan perpanjangan layanan untuk mengaktifkannya kembali.';
            $buttonText = 'Perpanjang Layanan Sekarang';
            $buttonUrl = 'https://subly.my.id/dashboard';
        }

        return '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $title . ' - Subly</title>
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
        <h1>' . $title . '</h1>
        <p>' . $message . '</p>
        <a href="' . $buttonUrl . '" class="btn">' . $buttonText . '</a>
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

    /**
     * Delete directory recursively using native PHP functions.
     */
    protected function deleteDirectoryRecursively(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path)) {
                $this->deleteDirectoryRecursively($path);
            } else {
                @unlink($path);
            }
        }
        return @rmdir($dir);
    }

    /**
     * Upload a file to the subdomain's document root on cPanel.
     */
    public function uploadFileToSubdomain(Subdomain $subdomain, string $localFilePath, string $targetFileName): void
    {
        if ($this->driver !== 'cpanel') {
            Log::info("SIMULATION: Uploaded file {$targetFileName} to subdomain {$subdomain->full_domain}");
            return;
        }

        try {
            $cpanelUser = config('services.hosting_panel.username', 'sublymyi');
            $cleanDir = ltrim(str_replace(["/home/{$cpanelUser}/", "home/{$cpanelUser}/"], '', $subdomain->doc_root), '/');

            // Use direct cPanel HTTP POST multi-part upload
            $url = rtrim(config('services.hosting_panel.url'), '/');
            $apiKey = config('services.hosting_panel.api_key');
            $headers = [
                'Authorization' => "cpanel {$cpanelUser}:{$apiKey}"
            ];

            Log::info("Uploading file {$targetFileName} to cPanel UAPI Fileman...");
            $response = Http::withHeaders($headers)
                ->withoutVerifying()
                ->attach('file-0', file_get_contents($localFilePath), $targetFileName)
                ->post("{$url}/execute/Fileman/upload_files", [
                    'dir' => $cleanDir,
                    'overwrite' => 1
                ]);

            $status = $response->json('status') ?? $response->json('result.status');
            if ($response->successful() && $status == 1) {
                Log::info("Successfully uploaded file {$targetFileName} to cPanel document root for {$subdomain->full_domain}");
            } else {
                $err = ($response->json('errors') ?? $response->json('result.errors') ?? [])[0] ?? $response->body();
                throw new \Exception("cPanel upload API error: " . $err);
            }
        } catch (\Exception $e) {
            Log::error("Failed to upload file to cPanel: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Extract a ZIP file in the subdomain's document root on cPanel.
     */
    public function extractZipInSubdomain(Subdomain $subdomain, string $fileName): void
    {
        if ($this->driver !== 'cpanel') {
            Log::info("SIMULATION: Extracted file {$fileName} in subdomain {$subdomain->full_domain}");
            return;
        }

        $cpanelUser = config('services.hosting_panel.username', 'sublymyi');
        $cleanDir = ltrim(str_replace(["/home/{$cpanelUser}/", "home/{$cpanelUser}/"], '', $subdomain->doc_root), '/');

        // Layer 1: Try direct local filesystem extraction (suPHP / LSAPI running on same server)
        $targetDir = $subdomain->doc_root;
        $zipFilePath = $targetDir . '/' . $fileName;

        if (is_dir($targetDir) && is_writable($targetDir) && file_exists($zipFilePath)) {
            Log::info("Local filesystem is accessible and writable. Extracting zip locally via ZipArchive...");
            $zip = new \ZipArchive();
            if ($zip->open($zipFilePath) === true) {
                $zip->extractTo($targetDir);
                $zip->close();
                Log::info("Successfully extracted {$fileName} locally via ZipArchive in {$targetDir}");
                return;
            } else {
                throw new \Exception("Gagal membuka atau membaca file arsip ZIP untuk diekstrak secara lokal.");
            }
        }

        // Layer 2: Fallback to cPanel UAPI Fileman/extract if remote or not local
        Log::info("Local filesystem not writable/accessible. Falling back to cPanel UAPI Fileman/extract...");
        $this->callCpanelApi('Fileman', 'extract', [
            'dir' => $cleanDir,
            'file' => $fileName,
            'to_dir' => $cleanDir,
        ]);

        Log::info("Successfully triggered cPanel UAPI extract for {$fileName} in {$cleanDir}");
    }

    /**
     * Delete a file in the subdomain's document root on cPanel.
     */
    public function deleteFileInSubdomain(Subdomain $subdomain, string $fileName): void
    {
        if ($this->driver !== 'cpanel') {
            Log::info("SIMULATION: Deleted file {$fileName} in subdomain {$subdomain->full_domain}");
            return;
        }

        $cpanelUser = config('services.hosting_panel.username', 'sublymyi');
        $cleanDir = ltrim(str_replace(["/home/{$cpanelUser}/", "home/{$cpanelUser}/"], '', $subdomain->doc_root), '/');

        // Layer 1: Try direct local filesystem deletion
        $targetDir = $subdomain->doc_root;
        $filePath = $targetDir . '/' . $fileName;

        if (file_exists($filePath) && is_writable($filePath)) {
            Log::info("Deleting file locally: {$filePath}");
            @unlink($filePath);
            return;
        }

        // Layer 2: Fallback to cPanel UAPI Fileman/unlink if remote
        try {
            $this->callCpanelApi('Fileman', 'unlink', [
                'dir' => $cleanDir,
                'file' => $fileName,
            ]);
            Log::info("Successfully deleted file {$fileName} in {$cleanDir}");
        } catch (\Exception $e) {
            Log::warning("Failed to delete file {$fileName} in cPanel: " . $e->getMessage());
        }
    }

    /**
     * Sync environment variables based on the subdomain's plan type.
     */
    public function syncEnvironment(Subdomain $subdomain): void
    {
        // 1. Get the subdomain's plan type
        $payment = $subdomain->payments()->where('status', 'success')->latest()->first();
        if (!$payment) {
            $payment = $subdomain->user->payments()->with('plan')->where('status', 'success')->latest()->first();
        }
        $planType = $payment && $payment->plan ? $payment->plan->type : 'PHP';

        // 2. Fetch the environment variables from database
        $variables = $subdomain->envs()->pluck('value', 'key')->toArray();

        Log::info("Syncing environment variables for subdomain: {$subdomain->full_domain}", [
            'plan_type' => $planType,
            'variables_count' => count($variables),
        ]);

        // 3. Write variables according to plan type
        if (in_array($planType, ['NodeJS', 'Fullstack'])) {
            // Write to both .htaccess and .env for Node.js / Fullstack apps
            $this->writeToHtaccessEnv($subdomain, $variables);
            $this->writeToEnvFile($subdomain, $variables);
        } else {
            // Write only to .env for PHP / Laravel / Python / others
            $this->writeToEnvFile($subdomain, $variables);
        }
    }

    /**
     * Write environment variables to the .env file in the subdomain's document root.
     */
    protected function writeToEnvFile(Subdomain $subdomain, array $variables): void
    {
        if ($this->driver !== 'cpanel') {
            Log::info("SIMULATION: Syncing environment to .env for {$subdomain->full_domain}");
            return;
        }

        try {
            $cpanelUser = config('services.hosting_panel.username', 'sublymyi');
            $cleanDir = ltrim(str_replace(["/home/{$cpanelUser}/", "home/{$cpanelUser}/"], '', $subdomain->doc_root), '/');

            // Format as standard .env file content
            $envContent = "# SUBLY ENVIRONMENT VARIABLES - GENERATED AUTOMATICALLY\n";
            foreach ($variables as $key => $value) {
                // Ensure value is double quoted to handle spaces and symbols safely
                $envContent .= "{$key}=\"{$value}\"\n";
            }

            Log::info("Writing .env file on cPanel for {$subdomain->full_domain}");
            $this->callCpanelApi('Fileman', 'save_file_content', [
                'dir' => $cleanDir,
                'file' => '.env',
                'content' => $envContent,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to sync .env to cPanel: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Write environment variables to the .htaccess file in the subdomain's document root (for Passenger Node.js app).
     */
    protected function writeToHtaccessEnv(Subdomain $subdomain, array $variables): void
    {
        if ($this->driver !== 'cpanel') {
            Log::info("SIMULATION: Syncing environment to .htaccess for {$subdomain->full_domain}");
            return;
        }

        try {
            $cpanelUser = config('services.hosting_panel.username', 'sublymyi');
            $cleanDir = ltrim(str_replace(["/home/{$cpanelUser}/", "home/{$cpanelUser}/"], '', $subdomain->doc_root), '/');

            // 1. Get existing .htaccess content
            $existingHtaccess = "";
            try {
                $response = $this->callCpanelApi('Fileman', 'get_file_content', [
                    'dir' => $cleanDir,
                    'file' => '.htaccess',
                ]);
                
                if ($response['success']) {
                    $existingHtaccess = $response['data']['result']['data']['content'] 
                        ?? $response['data']['cpanelresult']['data']['content'] 
                        ?? $response['data']['content'] 
                        ?? "";
                }
            } catch (\Exception $e) {
                Log::warning("No existing .htaccess found or unable to read it: " . $e->getMessage() . ". Starting fresh.");
                $existingHtaccess = "";
            }

            // 2. Generate new CloudLinux/LiteSpeed Env block
            $newEnvBlock = "# DO NOT REMOVE OR MODIFY. CLOUDLINUX ENV VARS CONFIGURATION BEGIN\n";
            $newEnvBlock .= "<IfModule Litespeed>\n";
            foreach ($variables as $key => $value) {
                $newEnvBlock .= "SetEnv {$key} \"{$value}\"\n";
            }
            $newEnvBlock .= "</IfModule>\n";
            $newEnvBlock .= "# DO NOT REMOVE OR MODIFY. CLOUDLINUX ENV VARS CONFIGURATION END";

            // 3. Regex replacement or append
            $pattern = '/# DO NOT REMOVE OR MODIFY\. CLOUDLINUX ENV VARS CONFIGURATION BEGIN.*# DO NOT REMOVE OR MODIFY\. CLOUDLINUX ENV VARS CONFIGURATION END/s';

            if (preg_match($pattern, $existingHtaccess)) {
                $updatedHtaccess = preg_replace($pattern, $newEnvBlock, $existingHtaccess);
            } else {
                $updatedHtaccess = rtrim($existingHtaccess) . "\n\n" . $newEnvBlock . "\n";
            }

            Log::info("Writing .htaccess env block on cPanel for {$subdomain->full_domain}");
            $this->callCpanelApi('Fileman', 'save_file_content', [
                'dir' => $cleanDir,
                'file' => '.htaccess',
                'content' => $updatedHtaccess,
            ]);

            // 4. Touch tmp/restart.txt to restart Passenger
            try {
                $this->callCpanelApi('Fileman', 'save_file_content', [
                    'dir' => $cleanDir . '/tmp',
                    'file' => 'restart.txt',
                    'content' => 'restart_at_' . time(),
                ]);
                Log::info("Successfully triggered Passenger Node.js restart via tmp/restart.txt");
            } catch (\Exception $e) {
                Log::warning("Skipped touching tmp/restart.txt (might be PHP or directory does not exist): " . $e->getMessage());
            }

        } catch (\Exception $e) {
            Log::error("Failed to sync .htaccess env to cPanel: " . $e->getMessage());
            throw $e;
        }
    }
}
