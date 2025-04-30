<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'is_default',
        'first_name',
        'last_name',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Get the user that owns the address.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope default shipping address.
     */
    public function scopeDefaultShipping($query)
    {
        return $query->where('type', 'shipping')->where('is_default', true);
    }

    /**
     * Scope default billing address.
     */
    public function scopeDefaultBilling($query)
    {
        return $query->where('type', 'billing')->where('is_default', true);
    }

    /**
     * Get full name.
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get formatted address.
     */
    public function getFormattedAttribute()
    {
        $parts = [
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ];
        
        return implode(', ', array_filter($parts));
    }
}