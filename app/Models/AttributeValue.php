<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'value',
        'display_order',
        'hex_color',
    ];

    protected $casts = [
        'display_order' => 'integer',
    ];

    /**
     * Get the attribute for the value.
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Get the variants for the attribute value.
     */
    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(Variant::class, 'variant_attribute_values');
    }
    
    /**
     * Get formatted value based on attribute type.
     */
    public function getFormattedValueAttribute(): string
    {
        if ($this->attribute && $this->attribute->type === 'color' && $this->hex_color) {
            return '<span class="color-swatch" style="background-color: ' . $this->hex_color . '" title="' . $this->value . '"></span> ' . $this->value;
        }
        
        return $this->value;
    }
}