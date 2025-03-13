<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * Get the coupons for the order.
     */
    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'order_coupons')
            ->withPivot('discount_amount');
    }

    /**
     * Get the eco impact record for the order.
     */
    public function ecoImpactRecord()
    {
        return $this->hasOne(EcoImpactRecord::class);
    }

    /**
     * Calculate total eco impact for the order.
     */
    public function calculateEcoImpact()
    {
        // Здесь можно рассчитать экологический вклад на основе товаров в заказе
        $carbonSaved = 0;
        $plasticSaved = 0;
        $waterSaved = 0;

        foreach ($this->items as $item) {
            // Пример расчета (в реальном проекте нужна более сложная логика)
            if ($item->eco_impact) {
                $impact = json_decode($item->eco_impact, true);
                $carbonSaved += ($impact['carbon_saved'] ?? 0) * $item->quantity;
                $plasticSaved += ($impact['plastic_saved'] ?? 0) * $item->quantity;
                $waterSaved += ($impact['water_saved'] ?? 0) * $item->quantity;
            }
        }

        return [
            'carbon_saved' => $carbonSaved,
            'plastic_saved' => $plasticSaved,
            'water_saved' => $waterSaved,
        ];
    }
}