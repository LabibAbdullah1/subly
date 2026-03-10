<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

// Simulate Laravel environment for validation test
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$data = [
    'target' => 'all',
    'user_id' => '', // This is what comes from the form when not selected
    'message' => 'Test message'
];

$rules = [
    'target' => 'required|string',
    'user_id' => 'nullable|required_if:target,specific|exists:users,id',
    'message' => 'required|string|max:1000',
];

$validator = Validator::make($data, $rules);

if ($validator->fails()) {
    echo "FAILED: " . json_encode($validator->errors()->toArray()) . "\n";
} else {
    echo "PASSED\n";
}
