<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'user_id',
        'answer',
        'is_admin',
        'is_approved',
    ];

    /**
     * Get the question for the answer.
     */
    public function question()
    {
        return $this->belongsTo(ProductQuestion::class, 'question_id');
    }

    /**
     * Get the user for the answer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope approved answers.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}