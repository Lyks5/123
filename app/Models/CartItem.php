<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'variant_id',
        'quantity',
    ];

    /**
     * Get the cart for the item.
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
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

    /**
     * Get the subtotal for the item.
     */
    public function getSubtotalAttribute()
    {
        $price = $this->variant_id ? $this->variant->current_price : $this->product->current_price;
        return $price * $this->quantity;
    }
}