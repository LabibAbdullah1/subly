<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDatabase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subdomain_id',
        'db_name',
        'db_user',
        'db_password',
    ];

    protected $hidden = [
        'db_password',
    ];

    protected function casts(): array
    {
        return [
            'db_password' => 'encrypted',
        ];
    }

    public function subdomain()
    {
        return $this->belongsTo(Subdomain::class);
    }
}
