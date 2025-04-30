<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'birth_date' => 'date',
    ];

    /**
     * Get the addresses for the user.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the reviews written by the user.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the blog posts authored by the user.
     */
    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'author_id');
    }

    /**
     * Get the default shipping address for the user.
     */
    public function getDefaultShippingAddress()
    {
        return $this->addresses()->where('type', 'shipping')->where('is_default', true)->first();
    }

    /**
     * Get the default billing address for the user.
     */
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
// app/Models/User.php
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
}