<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $appends = ['image'];

    protected $fillable = [
        'name',
        'description',
        'sku',
        'price',
        'stock_quantity',
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

    public const STATUSES = [
        'draft' => 'Черновик',
        'published' => 'Опубликован',
        'archived' => 'Архивирован'
    ];

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
        return $this->belongsToMany(Attribute::class)
            ->withPivot('value');
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_values')
            ->withPivot('value')
            ->withTimestamps();
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

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
            ->withPivot(['quantity', 'price', 'subtotal'])
            ->withTimestamps();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getImageAttribute()
    {
        $primaryImage = $this->images()->orderBy('order')->first();
        $url = $primaryImage ? $primaryImage->url : asset('images/placeholder-product.jpg');
        \Log::debug("Product ID: " . $this->id . " primary image URL: " . $url);
        return $url;
    }

    public function getRouteKeyName()
    {
        return 'sku';
    }
}
