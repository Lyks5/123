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
        'attribute_values_json',
    ];

    protected $casts = [
        'attribute_values_json' => 'json',
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
     * Get attribute values
     */
    public function getAttributeValuesAttribute()
    {
        return $this->attribute_values_json;
    }
}