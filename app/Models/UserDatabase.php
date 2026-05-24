<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

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

    protected function dbPassword(): Attribute
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
                    Crypt::decryptString($value);
                    return $value;
                } catch (DecryptException $e) {
                    return Crypt::encryptString($value);
                }
            }
        );
    }

    public function subdomain()
    {
        return $this->belongsTo(Subdomain::class);
    }
}
