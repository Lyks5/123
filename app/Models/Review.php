<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

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
     * Get the product for the review.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user for the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order for the review.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope approved reviews.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}