<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcoImpactRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'carbon_saved',
        'plastic_saved',
        'water_saved',
        'type',
        'description',
    ];

    /**
     * Get the user for the record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order for the record.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}