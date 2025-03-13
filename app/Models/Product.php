<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'sku',
        'stock_quantity',
        'is_featured',
        'is_active',
        'is_new',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Get the eco features for the product.
     */
    public function ecoFeatures()
    {
        return $this->belongsToMany(EcoFeature::class)
            ->withPivot('value')
            ->withTimestamps();
    }

    /**
     * Get the images for the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the variants for the product.
     */
    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the questions for the product.
     */
    public function questions()
    {
        return $this->hasMany(ProductQuestion::class);
    }

    /**
     * Get all tags for the product.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get the primary image for the product.
     */
    public function getPrimaryImageAttribute()
    {
        return $this->images()->where('is_primary', true)->first() ?? $this->images()->first();
    }

    /**
     * Get the average rating for the product.
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('is_approved', true)->avg('rating') ?? 0;
    }

    /**
     * Check if the product is on sale.
     */
    public function getIsOnSaleAttribute()
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    /**
     * Get the current price (considers sale price if available).
     */
    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured products.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include new products.
     */
    public function scopeNew($query)
    {
        return $query->where('is_new', true);
    }

    /**
     * Get featured products.
     */
    public function getFeaturedProducts($limit = 4)
    {
        return $this->active()->featured()->latest()->limit($limit)->get();
    }

    /**
     * Get new products.
     */
    public function getNewProducts($limit = 8)
    {
        return $this->active()->new()->latest()->limit($limit)->get();
    }
}
