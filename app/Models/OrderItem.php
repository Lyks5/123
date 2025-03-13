<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'name',
        'sku',
        'price',
        'quantity',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'eco_impact',
    ];

    protected $casts = [
        'eco_impact' => 'json',
    ];

    /**
     * Get the order for the item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product for the item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variant for the item.
     */
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}