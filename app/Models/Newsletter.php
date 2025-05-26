<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'status',
        'preferences',
    ];

    protected $casts = [
        'preferences' => 'json',
        'status' => 'string',
    ];

    /**
     * Scope active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}