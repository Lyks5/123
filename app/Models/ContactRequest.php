<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'notes',
    ];

    /**
     * Scope pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}