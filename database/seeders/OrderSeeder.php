<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Address;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    private $orderStatuses = [
        ['status' => 'completed', 'weight' => 60],
        ['status' => 'pending', 'weight' => 30],
        ['status' => 'processing', 'weight' => 5],
        ['status' => 'cancelled', 'weight' => 5],
    ];

    private function getRandomStatus(): string
    {
        $rand = rand(1, 100);
        $curr = 0;
        
        foreach ($this->orderStatuses as $status) {
            $curr += $status['weight'];
            if ($rand <= $curr) {
                return $status['status'];
            }
        }
        
        return 'completed';
    }

    private function getRandomDate(): Carbon
    {
        $probability = rand(1, 100);
        
        if ($probability <= 30) {
            // 30% заказов за последние 24 часа
            $startDate = Carbon::now()->subDay();
            $endDate = Carbon::now();
        } elseif ($probability <= 70) {
            // 40% заказов за последнюю неделю (исключая последние 24 часа)
            $startDate = Carbon::now()->subWeek();
            $endDate = Carbon::now()->subDay();
        } else {
            // 30% заказов за предыдущий период
            $startDate = Carbon::now()->subMonths(6);
            $endDate = Carbon::now()->subWeek();
        }
        
        $randomTimestamp = rand($startDate->timestamp, $endDate->timestamp);
        return Carbon::createFromTimestamp($randomTimestamp);
    }

    public function run(): void
    {
        // Получаем всех пользователей, кроме админа
        $users = User::where('is_admin', false)->get();
        $products = Product::all();

        foreach ($users as $user) {
            // Создаем адрес для пользователя
            $address = Address::create([
                'user_id' => $user->id,
                'type' => 'shipping',
                'first_name' => explode(' ', $user->name)[0],
                'last_name' => explode(' ', $user->name)[1] ?? 'Фамилия',
                'address_line1' => 'ул. Примерная, д.' . rand(1, 100),
                'address_line2' => 'кв. ' . rand(1, 200),
                'city' => 'Москва',
                'state' => 'Москва',
                'postal_code' => '123456',
                'country' => 'Россия',
                'phone' => '+7' . rand(9000000000, 9999999999),
                'is_default' => true,
            ]);

            // Создаем от 5 до 10 заказов для каждого пользователя
            $orderCount = rand(5, 10);
            for ($i = 0; $i < $orderCount; $i++) {
                $orderDate = $this->getRandomDate();
                $status = $this->getRandomStatus();

                // Создаем заказ с базовой информацией
                $order = Order::create([
                    'user_id' => $user->id,
                    'status' => $status,
                    'payment_method' => 'card',
                    'shipping_method' => 'standard',
                    'shipping_address_id' => $address->id,
                    'billing_address_id' => $address->id,
                    'total_amount' => 0,
                    'subtotal' => 0,
                    'tax_amount' => 0,
                    'shipping_amount' => 500,
                    'discount_amount' => 0,
                    'notes' => 'Тестовый заказ',
                    'tracking_number' => $status === 'completed' ? 'TN' . rand(100000, 999999) : null,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);

                // Добавляем 1-3 случайных товара в заказ
                $orderSubtotal = 0;
                $numberOfProducts = rand(1, 3);
                $orderProducts = $products->random($numberOfProducts);

                foreach ($orderProducts as $product) {
                    $quantity = rand(1, 3);
                    $price = $product->price;
                    $subtotal = $price * $quantity;
                    $orderSubtotal += $subtotal;

                    $order->items()->create([
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'price' => $price,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                        'tax_amount' => 0,
                        'discount_amount' => 0,
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]);
                }

                // Обновляем суммы заказа
                $total = $orderSubtotal + $order->shipping_amount - $order->discount_amount;
                $order->update([
                    'subtotal' => $orderSubtotal,
                    'total_amount' => $total,
                ]);
            }
        }
    }
}