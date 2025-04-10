<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'display_name',
        'is_required',
        'display_order',
    ];

    /**
     * The possible types of attributes.
     */
    public static $types = [
        'select' => 'Выпадающий список',
        'radio' => 'Радиокнопки',
        'checkbox' => 'Флажки',
        'color' => 'Цвет',
    ];

    /**
     * Get the values for the attribute.
     */
    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class)->orderBy('display_order');
    }
    
    /**
     * Get formatted type name.
     */
    public function getTypeNameAttribute(): string
    {
        return self::$types[$this->type] ?? $this->type;
    }
    
    /**
     * Get user-friendly display name or fallback to name
     */
    public function getDisplayNameOrNameAttribute(): string
    {
        return $this->display_name ?: $this->name;
    }
}