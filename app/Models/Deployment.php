<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deployment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subdomain_id',
        'zip_path',
        'zip_size',
        'extracted_size',
        'version',
        'status',
        'notes',
        'admin_note',
        'deployed_at',
    ];

    protected $casts = [
        'deployed_at' => 'datetime',
    ];

    public function subdomain()
    {
        return $this->belongsTo(Subdomain::class);
    }
}
