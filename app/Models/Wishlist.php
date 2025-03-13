<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'is_default',
        'is_public',
    ];

    /**
     * Get the user for the wishlist.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the wishlist.
     */
    public function items()
    {
        return $this->hasMany(WishlistItem::class);
    }

    /**
     * Get the products for the wishlist.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'wishlist_items');
    }
}