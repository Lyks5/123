<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvironmentalInitiative extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'goal',
        'current_progress',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'goal' => 'decimal:2',
        'current_progress' => 'decimal:2',
        'status' => 'string',
    ];

    /**
     * Get the progress percentage.
     */
    public function getProgressPercentageAttribute()
    {
        if (is_numeric($this->goal) && $this->goal > 0) {
            return min(100, ($this->current_progress / $this->goal) * 100);
        }
        
        return 0;
    }

    /**
     * Scope active initiatives.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}