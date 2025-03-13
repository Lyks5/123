<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'question',
        'is_answered',
        'is_approved',
    ];

    /**
     * Get the product for the question.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user for the question.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the answers for the question.
     */
    public function answers()
    {
        return $this->hasMany(ProductAnswer::class, 'question_id');
    }

    /**
     * Scope approved questions.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}