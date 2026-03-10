<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$logFile = __DIR__ . '/test_notif_log.txt';
function logMsg($msg) {
    global $logFile;
    file_put_contents($logFile, $msg . "\n", FILE_APPEND);
    echo $msg . "\n";
}

file_put_contents($logFile, "--- Start Test " . date('Y-m-d H:i:s') . " ---\n");

try {
    logMsg("Checking database connection...");
    DB::connection()->getPdo();
    logMsg("Connected successfully to: " . DB::connection()->getDatabaseName());

    logMsg("Checking if notifications table exists...");
    $exists = Schema::hasTable('notifications');
    logMsg("Table 'notifications' exists: " . ($exists ? "Yes" : "No"));

    $client = User::where('role', 'Client')->first();
    if (!$client) {
        logMsg("ERROR: No client found!");
        exit;
    }

    logMsg("Sending notification to User ID: {$client->id}");
    $client->notify(new GeneralNotification("Test at " . date('H:i:s')));
    
    logMsg("Checking database for entry...");
    $count = DB::table('notifications')->where('notifiable_id', $client->id)->count();
    logMsg("Notification count for user: " . $count);
    
    $latest = DB::table('notifications')->where('notifiable_id', $client->id)->latest()->first();
    if ($latest) {
        logMsg("SUCCESS: " . $latest->id);
    } else {
        logMsg("FAILURE: Not found");
    }

} catch (\Exception $e) {
    logMsg("EXCETPTION: " . $e->getMessage());
}
