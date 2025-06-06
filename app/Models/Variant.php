<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'status' => 'string',
    ];

    /**
     * Get the product for the variant.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the attribute values for the variant.
     */
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'variant_attribute_values');
    }

    /**
     * Get the current price (considers sale price if available).
     */
    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Check if the variant is on sale.
     */
    public function getIsOnSaleAttribute()
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    /**
     * Scope active variants.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}