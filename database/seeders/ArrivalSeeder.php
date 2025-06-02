<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Arrival;
use App\Models\Product;
use Carbon\Carbon;

class ArrivalSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        
        foreach ($products as $product) {
            // Создаем 2-3 поступления для каждого продукта
            $numArrivals = rand(2, 3);
            
            for ($i = 0; $i < $numArrivals; $i++) {
                $quantity = rand(10, 100);
                $purchasePrice = round($product->price * 0.7, 2); // 70% от цены продажи
                
                Arrival::create([
                    'name' => $product->name,
                    'quantity' => $quantity,
                    'arrival_date' => Carbon::now()->subDays(rand(1, 30)),
                    'purchase_price' => $purchasePrice,
                    'comment' => "Поставка #{$i} для {$product->name}",
                    'status' => 'active',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}