<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attribute extends Model
{
    protected $fillable = [
        'name',
        'type'
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_attribute')
            ->withPivot('value');
    }
}