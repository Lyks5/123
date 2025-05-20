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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
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

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'author_id');
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
}