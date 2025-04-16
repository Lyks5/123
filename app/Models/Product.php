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
        'attribute_values_json',
    ];

    protected $casts = [
        'attribute_values_json' => 'json',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the categories for the product.
     */
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
     * Get the parent product if this is a variant.
     */
    public function parent()
    {
        // Return null relation since parent_id column does not exist
        return $this->belongsTo(Product::class, 'id')->whereRaw('1 = 0');
    }

    /**
     * Get variants (child products).
     */
    public function variants()
    {
        // Return empty relation since parent_id column does not exist
        return $this->hasMany(Product::class, 'id')->whereRaw('1 = 0');
    }

    /**
     * Get the attribute values for this product variant.
     */
    public function getAttributeValuesAttribute()
    {
        return $this->attribute_values_json;
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function questions()
{
    return $this->hasMany(ProductQuestion::class)->whereNull('parent_id')->with('replies');
}


    /**
     * Get the questions for the product.

     * Get the primary image for the product.
     */
    public function getPrimaryImageAttribute()
    {
        return $this->images()->where('is_primary', true)->first() ?? $this->images()->first();
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
    public static function getFeaturedProducts($limit = 4)
    {
        return self::active()->featured()->latest()->limit($limit)->get();
    }

    /**
     * Get new products.
     */
    public static function getNewProducts($limit = 8)
    {
        return self::active()->new()->latest()->limit($limit)->get();
    }
}
