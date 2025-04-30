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

    protected $casts = [
        'carbon_saved' => 'decimal:2',
        'plastic_saved' => 'decimal:2',
        'water_saved' => 'decimal:2',
    ];

    /**
     * Get the user for the record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function sumAll(): array
    {
        return [
            'carbon' => (float) self::sum('carbon_saved'),
            'plastic' => (float) self::sum('plastic_saved'),
            'water' => (float) self::sum('water_saved')
        ];
    }
    /**
     * Get the order for the record.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}