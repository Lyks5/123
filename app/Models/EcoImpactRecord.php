<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EcoImpactRecord extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'product_id',
        'plastic_saved',
        'carbon_saved',
        'water_saved',
        'type',
        'description'
    ];

    protected $casts = [
        'plastic_saved' => 'decimal:2',
        'carbon_saved' => 'decimal:2',
        'water_saved' => 'decimal:2'
    ];

    /**
     * Правила валидации
     */
    public static $rules = [
        'user_id' => 'required|exists:users,id',
        'plastic_saved' => 'required|numeric|min:0',
        'carbon_saved' => 'required|numeric|min:0',
        'water_saved' => 'required|numeric|min:0'
    ];

    /**
     * Get the product that owns the eco impact record.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user that owns the eco impact record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}