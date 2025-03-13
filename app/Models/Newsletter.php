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
        'is_active',
        'preferences',
    ];

    protected $casts = [
        'preferences' => 'json',
    ];

    /**
     * Scope active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}