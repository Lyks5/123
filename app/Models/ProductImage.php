<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductImage extends Model
{
    protected $fillable = [
        'url',
        'original_name',
        'mime_type',
        'size'
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_image_pivot')
            ->withPivot('order');
    }
}
