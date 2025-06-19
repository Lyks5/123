<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Product;
use App\Models\EcoFeature;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\EcoContributionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EcoContributionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected EcoContributionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new EcoContributionService();
    }

    /** @test */
    public function рейтинг_пользователя_без_покупок_равен_нулю()
    {
        $user = User::factory()->create();

        $rating = $this->service->calculateEcoRating($user);

        $this->assertEquals(0, $rating);
    }

    /** @test */
    public function рейтинг_пользователя_с_обычными_товарами_равен_нулю()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create();
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $rating = $this->service->calculateEcoRating($user);

        $this->assertEquals(0, $rating);
    }

    /** @test */
    public function рейтинг_пользователя_с_eco_товарами_считается_корректно()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);
        $ecoFeature = EcoFeature::factory()->create();
        $ecoProduct = Product::factory()->create();
        $ecoProduct->ecoFeatures()->attach($ecoFeature->id);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $ecoProduct->id,
            'quantity' => 3,
        ]);

        $rating = $this->service->calculateEcoRating($user);

        $this->assertEquals(30, $rating); // 3 * 10 по умолчанию
    }

    /** @test */
    public function рейтинг_не_превышает_максимум()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);
        $ecoFeature = EcoFeature::factory()->create();
        $ecoProduct = Product::factory()->create();
        $ecoProduct->ecoFeatures()->attach($ecoFeature->id);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $ecoProduct->id,
            'quantity' => 200, // 200 * 10 = 2000 > 1000
        ]);

        $rating = $this->service->calculateEcoRating($user);

        $this->assertEquals(1000, $rating);
    }
}