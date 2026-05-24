<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'is_admin',
        'message',
        'image_path',
        'is_read',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'is_read' => 'boolean',
    ];

    protected static function booted()
    {
        static::deleted(function ($chat) {
            if ($chat->image_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($chat->image_path);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
