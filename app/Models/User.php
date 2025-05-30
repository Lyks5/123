<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'bio',
        'email_verified_at',
        'remember_token',
        'is_admin',
        'birth_date',
        'gender',
        'cart_data',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
        'wishlist_data' => 'array',
        'cart_data' => 'array',
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getDefaultShippingAddress()
    {
        return $this->addresses()->where('type', 'shipping')->where('is_default', true)->first();
    }

    public function getDefaultBillingAddress()
    {
        return $this->addresses()->where('type', 'billing')->where('is_default', true)->first();
    }

    public function getCartDataAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    public function setCartDataAttribute($value)
    {
        $this->attributes['cart_data'] = json_encode($value);
    }

    public function ecoImpactRecords()
    {
        return $this->hasMany(EcoImpactRecord::class);
    }

    public function getTotalEcoImpact()
    {
        return [
            'carbon_saved' => $this->ecoImpactRecords()->sum('carbon_saved'),
            'plastic_saved' => $this->ecoImpactRecords()->sum('plastic_saved'),
            'water_saved' => $this->ecoImpactRecords()->sum('water_saved')
        ];
    }

    public function isAdmin()
    {
        return $this->is_admin === 1;
    }

    // Методы для работы с избранным
    public function getWishlist(string $name = 'Избранное'): Wishlist
    {
        $lists = $this->wishlist_data ?? [];
        
        foreach ($lists as $list) {
            if ($list['list_name'] === $name) {
                return new Wishlist($name, $list['items'] ?? []);
            }
        }

        return new Wishlist($name);
    }

    public function saveWishlist(Wishlist $wishlist): void
    {
        $lists = $this->wishlist_data ?? [];
        $updated = false;

        foreach ($lists as &$list) {
            if ($list['list_name'] === $wishlist->toArray()['list_name']) {
                $list = $wishlist->toArray();
                $updated = true;
                break;
            }
        }

        if (!$updated) {
            $lists[] = $wishlist->toArray();
        }

        $this->wishlist_data = $lists;
        $this->save();
    }

    public function addToWishlist(int $productId, ?int $variantId = null, string $listName = 'Избранное'): void
    {
        $wishlist = $this->getWishlist($listName);
        $wishlist->addItem($productId, $variantId);
        $this->saveWishlist($wishlist);
    }

    public function removeFromWishlist(int $productId, ?int $variantId = null, string $listName = 'Избранное'): void
    {
        $wishlist = $this->getWishlist($listName);
        $wishlist->removeItem($productId, $variantId);
        $this->saveWishlist($wishlist);
    }

    public function isInWishlist(int $productId, ?int $variantId = null, string $listName = 'Избранное'): bool
    {
        return $this->getWishlist($listName)->hasItem($productId, $variantId);
    }
}