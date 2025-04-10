<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_amount',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'shipping_address_id',
        'billing_address_id',
        'payment_method',
        'shipping_method',
        'notes',
        'carbon_offset',
        'tracking_number',
        'eco_impact',
    ];

    protected $casts = [
        'eco_impact' => 'json',
    ];

    /**
     * Get the user for the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shipping address for the order.
     */
    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    /**
     * Get the billing address for the order.
     */
    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Calculate total eco impact for the order.
     */
    public function calculateEcoImpact()
    {
        // Return the existing eco impact if it exists
        if ($this->eco_impact) {
            return $this->eco_impact;
        }
        
        // Calculate from items
        $carbonSaved = 0;
        $plasticSaved = 0;
        $waterSaved = 0;

        foreach ($this->items as $item) {
            if ($item->product && $item->product->eco_features) {
                $carbonSaved += 0.5 * $item->quantity; // Example calculation
                $plasticSaved += 0.2 * $item->quantity; // Example calculation
                $waterSaved += 1.5 * $item->quantity; // Example calculation
            }
        }

        $impact = [
            'carbon_saved' => $carbonSaved,
            'plastic_saved' => $plasticSaved,
            'water_saved' => $waterSaved,
        ];
        
        // Save the calculated impact
        $this->eco_impact = $impact;
        $this->save();
        
        return $impact;
    }
    
    /**
     * Get humanized status text.
     */
    public function getStatusTextAttribute()
    {
        $statuses = [
            'pending' => 'Ожидает',
            'processing' => 'В обработке',
            'shipped' => 'Отправлен',
            'delivered' => 'Доставлен',
            'completed' => 'Завершен',
            'cancelled' => 'Отменен',
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }
    
    /**
     * Get status color class.
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'shipped' => 'bg-purple-100 text-purple-800',
            'delivered' => 'bg-green-100 text-green-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];
        
        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}