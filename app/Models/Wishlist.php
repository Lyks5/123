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
    public static function getDefaultForUser($userId)
    {
        $wishlist = self::where('user_id', $userId)
            ->where('is_default', true)
            ->first();
            
        if (!$wishlist) {
            $wishlist = self::create([
                'user_id' => $userId,
                'name' => 'Избранное',
                'is_default' => true,
                'is_public' => false,
            ]);
        }
        
        return $wishlist;
    }
    /**
     * Get all wishlists for a user with item counts.
     */
    public static function getAllForUserWithCounts($userId)
    {
        return self::where('user_id', $userId)
            ->withCount('items')
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get();
    }
    
    /**
     * Get wishlist by ID, making sure it belongs to the user.
     */
    public static function getForUser($wishlistId, $userId)
    {
        return self::where('id', $wishlistId)
            ->where('user_id', $userId)
            ->first();
    }

}