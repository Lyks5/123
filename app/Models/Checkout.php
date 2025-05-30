<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    protected $fillable = [
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'total_amount',
        'shipping_method',
        'payment_method',
        'shipping_address_id',
        'billing_address_id',
        'notes'
    ];

    protected $casts = [
        'subtotal' => 'float',
        'tax_amount' => 'float',
        'shipping_amount' => 'float',
        'total_amount' => 'float'
    ];

    // Отношения
    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    // Методы расчета
    public function calculateSubtotal($cart)
    {
        return $cart->getSubtotal();
    }

    public function calculateTax()
    {
        $taxRate = config('settings.tax_rate', 0.2); // 20% НДС по умолчанию
        return $this->subtotal * $taxRate;
    }

    public function calculateShipping()
    {
        $shippingMethods = [
            'standard' => 300,
            'express' => 600,
            'pickup' => 0,
        ];

        return $shippingMethods[$this->shipping_method] ?? 0;
    }

    public function calculateTotal()
    {
        return $this->subtotal + $this->tax_amount + $this->shipping_amount;
    }

    public function updateAmounts()
    {
        $this->subtotal = $this->calculateSubtotal();
        $this->tax_amount = $this->calculateTax();
        $this->shipping_amount = $this->calculateShipping();
        $this->total_amount = $this->calculateTotal();

        return $this;
    }
}