<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    // Разрешённые для массового заполнения поля
    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'rating',
        'title',
        'comment',
        'is_verified_purchase',
        'is_approved',
    ];

    /**
     * Отношение: отзыв принадлежит продукту
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Отношение: отзыв принадлежит пользователю
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Отношение: отзыв может принадлежать заказу (nullable)
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}