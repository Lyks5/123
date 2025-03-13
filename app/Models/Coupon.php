<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_order_amount',
        'starts_at',
        'expires_at',
        'is_active',
        'max_uses',
        'used_count',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the orders for the coupon.
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_coupons')
            ->withPivot('discount_amount');
    }

    /**
     * Check if the coupon is valid.
     */
    public function isValid($orderAmount = 0)
    {
        // Проверка активности
        if (!$this->is_active) {
            return false;
        }

        // Проверка максимального количества использований
        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return false;
        }

        // Проверка даты начала
        if ($this->starts_at && now()->lt($this->starts_at)) {
            return false;
        }

        // Проверка даты окончания
        if ($this->expires_at && now()->gt($this->expires_at)) {
            return false;
        }

        // Проверка минимальной суммы заказа
        if ($this->min_order_amount && $orderAmount < $this->min_order_amount) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount amount.
     */
    public function calculateDiscount($orderAmount)
    {
        if ($this->type === 'percentage') {
            return ($orderAmount * $this->value) / 100;
        }

        return min($this->value, $orderAmount);
    }
}