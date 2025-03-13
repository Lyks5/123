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
}