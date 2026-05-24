<?php

use App\Models\UserDatabase;
use Illuminate\Support\Facades\Crypt;

uses(Tests\TestCase::class);

test('that true is true', function () {
    expect(true)->toBeTrue();
});

test('db_password gets encrypted when set, and decrypted when accessed', function () {
    $db = new UserDatabase();
    
    // Set a plain password
    $db->db_password = 'my_secure_password_123';
    
    // Retrieve password - should be decrypted automatically
    expect($db->db_password)->toBe('my_secure_password_123');
    
    // The raw attribute in the attributes array should be encrypted and different
    $rawAttribute = $db->getAttributes()['db_password'] ?? null;
    expect($rawAttribute)->not->toBe('my_secure_password_123');
    expect(Crypt::decryptString($rawAttribute))->toBe('my_secure_password_123');
});

test('db_password gracefully returns raw text if decryption fails (e.g. legacy/invalid data)', function () {
    $db = new UserDatabase();
    
    // Force a raw plain-text password into the model's attributes array
    // (simulating a database row that contains unencrypted password)
    $db->setRawAttributes([
        'db_password' => 'legacy_plain_password_abc'
    ]);
    
    // Retrieve password - should not throw DecryptException but gracefully return the raw value
    expect($db->db_password)->toBe('legacy_plain_password_abc');
});
