<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
    ];

    public function getSubtotal()
    {
        return $this->items->sum(function ($item) {
            $price = $item->variant_id ? $item->variant->current_price : $item->product->current_price;
            return $price * $item->quantity;
        });
    }

    /**
     * Get the user for the cart.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the cart.
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the total amount for the cart.
     */
    public function getTotalAttribute()
    {
        return $this->items->sum(function ($item) {
            $price = $item->variant_id ? $item->variant->current_price : $item->product->current_price;
            return $price * $item->quantity;
        });
    }

    /**
     * Get the total quantity for the cart.
     */
    public function getTotalQuantityAttribute()
    {
        return $this->items->sum('quantity');
    }
}
