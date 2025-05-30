<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $appends = ['image', 'stock_quantity'];

    protected $fillable = [
        'name',
        'description',
        'sku',
        'price',
        'quantity',
        'category_id',
        'status',
        'is_featured',
        'eco_score',
        'sustainability_info',
        'carbon_footprint'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'eco_score' => 'integer',
        'carbon_footprint' => 'decimal:2'
    ];

    protected $with = ['category', 'ecoFeatures'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute')
            ->withPivot('value');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->orderBy('order')->limit(1);
    }

    public function ecoFeatures(): BelongsToMany
    {
        return $this->belongsToMany(EcoFeature::class, 'eco_feature_product')
            ->withPivot('value')
            ->withTimestamps();
    }
    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    /**
     * Get the orders containing this product.
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
            ->withPivot(['quantity', 'price', 'subtotal'])
            ->withTimestamps();
    }

    /**
     * Get all reviews for the product.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    /**
     * Get attribute values for the product
     */
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function getImageAttribute()
    {
        $primaryImage = $this->images()->orderBy('order')->first();
        $url = $primaryImage ? $primaryImage->url : asset('images/placeholder-product.jpg');
        \Log::debug("Product ID: " . $this->id . " primary image URL: " . $url);
        return $url;
    }

    public function getStockQuantityAttribute()
    {
        return $this->quantity;
    }
}
