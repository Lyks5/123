<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Product.php
class Product extends Model
{
    protected $fillable = [
        'name', 
        'slug',
        'description',
        'price',
        'eco_features' // ['recycled', 'organic', 'biodegradable']
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
