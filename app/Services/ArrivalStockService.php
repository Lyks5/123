<?php

namespace App\Services;

use App\Models\Arrival;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ArrivalStockService
{
    /**
     * Уменьшить количество товара в поступлениях при оформлении заказа
     */
    public function decrementStock(Product $product, int $quantity): void
    {
        $arrivals = Arrival::where('name', $product->name)
            ->where('status', 'active')
            ->where('quantity', '>', 0)
            ->orderBy('arrival_date')
            ->get();

        $remainingQuantity = $quantity;

        foreach ($arrivals as $arrival) {
            if ($remainingQuantity <= 0) {
                break;
            }

            $deductQuantity = min($arrival->quantity, $remainingQuantity);
            $arrival->quantity -= $deductQuantity;
            $remainingQuantity -= $deductQuantity;

            if ($arrival->quantity == 0) {
                $arrival->status = 'used';
            }

            $arrival->save();
        }
    }

    /**
     * Вернуть количество товара в поступления при отмене заказа
     */
    public function incrementStock(Product $product, int $quantity): void
    {
        // Находим последнее активное поступление или использованное
        $arrival = Arrival::where('name', $product->name)
            ->whereIn('status', ['active', 'used'])
            ->orderByDesc('arrival_date')
            ->first();

        if ($arrival) {
            if ($arrival->status === 'used') {
                $arrival->status = 'active';
            }
            $arrival->quantity += $quantity;
            $arrival->save();
        }
    }
}