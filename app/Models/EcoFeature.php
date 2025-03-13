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
    ];

    /**
     * Get the products for the eco feature.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('value')
            ->withTimestamps();
    }
}