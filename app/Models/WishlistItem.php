<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'wishlist_id',
        'product_id',
        'variant_id',
    ];

    /**
     * Get the wishlist for the item.
     */
    public function wishlist()
    {
        return $this->belongsTo(Wishlist::class);
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