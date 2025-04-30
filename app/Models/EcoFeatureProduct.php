<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EcoFeatureProduct extends Pivot
{
    use HasFactory;

    protected $table = 'eco_feature_product';

    protected $fillable = [
        'eco_feature_id',
        'product_id',
        'value',
    ];
}