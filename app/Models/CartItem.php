<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'variant_id',
        'quantity'
    ];

    /**
     * Отношение к корзине
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Отношение к продукту
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Отношение к варианту продукта
     */
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}