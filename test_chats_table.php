<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $count = \Illuminate\Support\Facades\DB::table('chats')->count();
    echo "SUCCESS: Chats table exists. Count: " . $count;
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
