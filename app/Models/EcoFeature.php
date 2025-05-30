<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcoFeature extends Model
{
    use HasFactory;

    

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the products for the eco feature.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'eco_feature_product')
            ->withPivot('value');
    }
}