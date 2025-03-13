<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'value',
    ];

    /**
     * Get the attribute for the value.
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Get the variants for the attribute value.
     */
    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'variant_attribute_values');
    }
}