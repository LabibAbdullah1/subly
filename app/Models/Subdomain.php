<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class Subdomain extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'full_domain',
        'doc_root',
        'status',
        'expired_at',
        'git_url',
        'git_branch',
        'git_token',
        'git_last_commit',
        'git_connected_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'git_connected_at' => 'datetime',
    ];

    /**
     * Get and Set the encrypted git token.
     */
    protected function gitToken(): Attribute
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userDatabases()
    {
        return $this->hasMany(UserDatabase::class);
    }

    public function envs()
    {
        return $this->hasMany(SubdomainEnv::class);
    }

    public function deployments()
    {
        return $this->hasMany(Deployment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if the subdomain's active period has expired in real-time.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expired_at && $this->expired_at->isPast();
    }
}

