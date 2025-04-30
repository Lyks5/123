<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class VariantAttributeValue extends Pivot
{
    use HasFactory;

    protected $table = 'variant_attribute_values';

    /**
     * Get the variant that owns the value.
     */
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    /**
     * Get the attribute value that owns the pivot.
     */
    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class);
    }
}