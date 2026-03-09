<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'is_active',
        'price',
        'duration_months',
        'max_storage_mb',
        'max_databases',
        'description',
    ];

    protected $casts = [
        'duration_months' => 'integer',
        'max_storage_mb' => 'integer',
        'max_databases' => 'integer',
        'is_active' => 'boolean',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
