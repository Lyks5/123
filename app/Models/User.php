<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
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
        'birth_date',
        'gender',
        'eco_impact_score',
        'is_admin',
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
        'birth_date' => 'date',
        'is_admin' => 'boolean',
    ];

    /**
     * Get the user's addresses.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get the user's orders.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the user's wishlists.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the user's cart.
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Get the user's reviews.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the user's eco impact records.
     */
    public function ecoImpactRecords()
    {
        return $this->hasMany(EcoImpactRecord::class);
    }
    
    /**
     * Get the blog posts authored by the user.
     */
    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'author_id');
    }
    public function getDefaultShippingAddress()
    {
        return $this->addresses()->where('type', 'shipping')->where('is_default', true)->first()
            ?? $this->addresses()->where('type', 'shipping')->first()
            ?? $this->addresses()->first();
    }
    
    /**
     * Get the default billing address for the user.
     */
    public function getDefaultBillingAddress()
    {
        return $this->addresses()->where('type', 'billing')->where('is_default', true)->first()
            ?? $this->addresses()->where('type', 'billing')->first()
            ?? $this->getDefaultShippingAddress();
    }

    /**
     * Get the user's total eco impact.
     */
    public function getTotalEcoImpact()
    {
        $totalImpact = [
            'carbon_saved' => 0,
            'plastic_saved' => 0,
            'water_saved' => 0,
        ];
        
        foreach ($this->orders as $order) {
            $ecoImpact = $order->calculateEcoImpact();
            $totalImpact['carbon_saved'] += $ecoImpact['carbon_saved'] ?? 0;
            $totalImpact['plastic_saved'] += $ecoImpact['plastic_saved'] ?? 0;
            $totalImpact['water_saved'] += $ecoImpact['water_saved'] ?? 0;
        }
        
        return $totalImpact;
    }
}
