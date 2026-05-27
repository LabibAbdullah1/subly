<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class SubdomainEnv extends Model
{
    use HasFactory;

    protected $fillable = [
        'subdomain_id',
        'key',
        'value',
        'is_secret',
    ];

    protected $casts = [
        'is_secret' => 'boolean',
    ];

    /**
     * Get and Set the encrypted environment variable value.
     */
    protected function value(): Attribute
    {
        return Attribute::make(
            get: function (?string $value) {
                if (empty($value)) {
                    return $value;
                }
                try {
                    return Crypt::decryptString($value);
                } catch (DecryptException $e) {
                    return $value;
                }
            },
            set: function (?string $value) {
                if (empty($value)) {
                    return null;
                }
                try {
                    // Check if already encrypted
                    Crypt::decryptString($value);
                    return $value;
                } catch (DecryptException $e) {
                    return Crypt::encryptString($value);
                }
            }
        );
    }

    /**
     * Relationship to the Subdomain.
     */
    public function subdomain()
    {
        return $this->belongsTo(Subdomain::class);
    }
}
