<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // Define the table if not the default 'reviews'
    // protected $table = 'reviews';

    // Define fillable fields if needed
    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the product that this review belongs to.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who wrote the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
