<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Order;
use App\Models\EcoFeature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class CheckoutTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $user;
    private $product;
    private $address;

    protected function setUp(): void
    {
        parent::setUp();

        // Создаем тестового пользователя
        $this->user = User::factory()->create();

        // Создаем тестовый продукт
        $this->product = Product::factory()->create([
            'price' => 1000.00,
            'quantity' => 10,
            'status' => 'published'
        ]);

        // Создаем тестовый адрес
        $this->address = Address::factory()->create([
            'user_id' => $this->user->id,
            'is_default' => true
        ]);

        // Добавляем эко-характеристики к продукту
        $ecoFeatures = EcoFeature::factory(2)->create();
        $this->product->ecoFeatures()->attach($ecoFeatures->pluck('id'));
    }

    /** @test */
    public function user_can_create_order()
    {
        // Авторизуем пользователя
        $this->actingAs($this->user);

        // Добавляем товар в корзину
        $cartData = [
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 2
                ]
            ]
        ];
        $this->user->cart_data = $cartData;
        $this->user->save();

        // Проверяем доступ к странице оформления заказа
        $response = $this->get(route('checkout.index'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.checkout');

        // Подготавливаем данные для оформления заказа
        $orderData = [
            'shipping_address_type' => 'new',
            'shipping_first_name' => 'Иван',
            'shipping_last_name' => 'Иванов',
            'shipping_address_line1' => 'ул. Тестовая, 123',
            'shipping_city' => 'Москва',
            'shipping_state' => 'Московская область',
            'shipping_postal_code' => '123456',
            'shipping_country' => 'Россия',
            'shipping_phone' => '+7 999 123 45 67',
            'billing_address_type' => 'same',
            'payment_method' => 'credit_card',
            'card_number' => '4242424242424242',
            'card_name' => 'Ivan Ivanov',
            'card_expiry' => '12/25',
            'card_cvv' => '123',
            'shipping_method' => 'standard'
        ];

        // Отправляем запрос на создание заказа
        $response = $this->post(route('checkout.process'), $orderData);

        // Проверяем успешное создание заказа
        $response->assertRedirect(route('checkout.success', ['order' => 1]));
        
        // Проверяем создание заказа и адреса в базе данных
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'status' => 'pending',
            'payment_method' => 'credit_card',
            'shipping_method' => 'standard'
        ]);

        $this->assertDatabaseHas('addresses', [
            'user_id' => $this->user->id,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'address_line1' => 'ул. Тестовая, 123',
            'city' => 'Москва'
        ]);

        // Проверяем корректность обработки заказа
        $this->assertEquals(8, $this->product->fresh()->quantity);
        $this->user->refresh();
        $this->assertEmpty($this->user->cart_data['items'] ?? []);
    }

    /** @test */
    public function checkout_validates_required_fields()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('checkout.process'), []);

        $response->assertSessionHasErrors([
            'shipping_address_type',
            'billing_address_type',
            'payment_method'
        ]);
    }

    /** @test */
    public function checkout_fails_with_empty_cart()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('checkout.process'), [
            'shipping_address_type' => 'existing',
            'shipping_address_id' => $this->address->id,
            'billing_address_type' => 'same',
            'payment_method' => 'credit_card'
        ]);

        $response->assertRedirect(route('shop'));
        $response->assertSessionHas('error', 'Ваша корзина пуста.');
    }
}