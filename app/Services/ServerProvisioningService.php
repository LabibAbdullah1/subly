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
        // Check if database credentials already exist
        $database = $subdomain->userDatabases()->first();

        if (!$database) {
            $dbName = 'sublymyi_' . strtolower(Str::random(8));
            $dbUser = 'sublymyi_' . strtolower(Str::random(8));
            $dbPass = Str::password(16, true, true, true, false);

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
                ->timeout(20)
                ->get("{$this->apiUrl}/execute/SubDomain/addsubdomain", [
                    'domain' => $subdomain->name,
                    'rootdomain' => $rootDomain,
                    'dir' => $cleanDir,
                ]);

            if ($subResponse->successful()) {
                Log::info("cPanel Subdomain created: " . $subResponse->body());
            } else {
                Log::error("cPanel Subdomain creation failed: " . $subResponse->body());
            }

            // 2. Create MySQL Database
            // In cPanel, database and user names must begin with cpanel_username + _
            $cleanDbName = str_replace('subly_', '', $database->db_name);
            $cleanUsrName = str_replace('usr_', '', $database->db_user);

            $dbName = substr("{$cpanelUser}_{$cleanDbName}", 0, 64);
            $dbUser = substr("{$cpanelUser}_{$cleanUsrName}", 0, 32);

            // Update local DB record with prefixed cPanel names
            $database->update([
                'db_name' => $dbName,
                'db_user' => $dbUser,
            ]);

            $dbCreateResponse = Http::withHeaders($headers)
                ->withoutVerifying()
                ->timeout(20)
                ->get("{$this->apiUrl}/execute/Mysql/create_database", [
                    'name' => $dbName,
                ]);

            if ($dbCreateResponse->successful()) {
                Log::info("cPanel Database created: " . $dbCreateResponse->body());
            }

            // 3. Create MySQL Database User
            $usrCreateResponse = Http::withHeaders($headers)
                ->withoutVerifying()
                ->timeout(20)
                ->get("{$this->apiUrl}/execute/Mysql/create_user", [
                    'name' => $dbUser,
                    'password' => $database->db_password,
                ]);

            if ($usrCreateResponse->successful()) {
                Log::info("cPanel Database User created: " . $usrCreateResponse->body());
            }

            // 4. Assign All Privileges (Link User to Database)
            $privResponse = Http::withHeaders($headers)
                ->withoutVerifying()
                ->timeout(20)
                ->get("{$this->apiUrl}/execute/Mysql/set_privileges_on_database", [
                    'user' => $dbUser,
                    'database' => $dbName,
                    'privileges' => 'ALL PRIVILEGES',
                ]);

            if ($privResponse->successful()) {
                Log::info("cPanel Database Privileges assigned: " . $privResponse->body());
            }
        } catch (\Exception $e) {
            Log::error("cPanel provisioning failed: " . $e->getMessage());
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
}
