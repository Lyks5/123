<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_amount',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'shipping_address_id',
        'billing_address_id',
        'payment_method',
        'shipping_method',
        'notes',
        'carbon_offset',
        'tracking_number',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'carbon_offset' => 'boolean',
    ];

    /**
     * Get the user for the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shipping address for the order.
     */
    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    /**
     * Get the billing address for the order.
     */
    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeValid(Builder $query): Builder // Указываем правильный тип
    {
        return $query->whereIn('status', ['completed', 'shipped']);
    }
    /**
     * Get the eco impact records for the order.
     */
    public function ecoImpactRecords()
    {
        return $this->hasMany(EcoImpactRecord::class);
    }

    /**
     * Get the reviews for the order.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get humanized status text.
     */
    public function getStatusTextAttribute()
    {
        $statuses = [
            'pending' => 'В ожидании',
            'processing' => 'В обработке',
            'shipped' => 'Отправлен',
            'delivered' => 'Доставлен',
            'completed' => 'Завершен',
            'cancelled' => 'Отменен',
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }
}