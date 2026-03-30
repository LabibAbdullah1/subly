<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use HasFactory, SoftDeletes;
    
    public const TYPES = [
        'PHP' => [
            'label' => 'PHP Hosting',
            'color' => 'bg-blue-500/20 text-blue-400 border border-blue-500/30'
        ],
        'NodeJS' => [
            'label' => 'NodeJS Hosting',
            'color' => 'bg-green-500/20 text-green-400 border border-green-500/30'
        ],
        'Fullstack' => [
            'label' => 'Fullstack (PHP + Node)',
            'color' => 'bg-purple-500/20 text-purple-400 border border-purple-500/30'
        ],
        'Python' => [
            'label' => 'Python Hosting',
            'color' => 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30'
        ],
        'Laravel' => [
            'label' => 'Laravel Hosting',
            'color' => 'bg-red-500/20 text-red-400 border border-red-500/30'
        ],
    ];

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
