<?php

namespace App\Services;

use App\Models\Arrival;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ArrivalService
{
    public function create(array $data): Arrival
    {
        return Arrival::create([
            'name' => $data['name'],
            'quantity' => $data['quantity'],
            'arrival_date' => $data['arrival_date'],
            'purchase_price' => $data['purchase_price'],
            'comment' => $data['comment'] ?? null,
            'status' => 'active'
        ]);
    }

    public function findByProductName(string $name)
    {
        return Arrival::where('name', $name)
            ->where('status', 'active')
            ->orderBy('arrival_date')
            ->first();
    }

    public function incrementStock(string $productName, int $quantity): void
    {
        DB::transaction(function () use ($productName, $quantity) {
            $arrival = $this->findByProductName($productName);
            
            if ($arrival) {
                // Если нашли активное поступление, увеличиваем его количество
                $arrival->increment('quantity', $quantity);
                if ($arrival->status === 'used') {
                    $arrival->update(['status' => 'active']);
                }
            } else {
                // Если не нашли, создаем новое поступление
                $arrival = Arrival::where('name', $productName)
                    ->orderByDesc('arrival_date')
                    ->first();

                $this->create([
                    'name' => $productName,
                    'quantity' => $quantity,
                    'arrival_date' => now(),
                    'purchase_price' => $arrival ? $arrival->purchase_price : 0,
                    'comment' => 'Возврат товара в поступления'
                ]);
            }
        });
    }
}