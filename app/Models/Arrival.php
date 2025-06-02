<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arrival extends Model
{
    protected $fillable = [
        'name',
        'quantity',
        'arrival_date',
        'purchase_price',
        'comment',
        'status'
    ];

    protected $casts = [
        'arrival_date' => 'date',
        'purchase_price' => 'decimal:2'
    ];

    /**
     * Проверяет, активно ли поступление
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}