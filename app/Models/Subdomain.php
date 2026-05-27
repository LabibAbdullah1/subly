<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subdomain extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'full_domain',
        'doc_root',
        'status',
        'nodejs_version',
        'nodejs_startup_file',
        'nodejs_mode',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

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

