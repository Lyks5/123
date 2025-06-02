<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AttributeValue extends Model
{
    protected $fillable = [
        'attribute_id',
        'value',
        'hex_color',
        'display_order'
    ];

    protected $casts = [
        'display_order' => 'integer'
    ];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_attribute_values')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->attribute->type === 'color' && $this->hex_color) {
            return "{$this->value} ({$this->hex_color})";
        }
        return $this->value;
    }
}