<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Checkout;
use App\Models\EcoImpactRecord;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        // Получаем корзину
        $cart = $this->getCart();
        
        // Проверяем, что корзина не пуста
        if ($cart->isEmpty()) {
            return redirect()->route('shop')->with('error', 'Ваша корзина пуста. Добавьте товары перед оформлением заказа.');
        }

        // Получаем или создаем сессию оформления заказа
        $checkout = $this->getOrCreateCheckout($cart);
        
        // Получаем адреса пользователя, если он авторизован
        $addresses = auth()->check() ? auth()->user()->addresses : collect();
        
        // Доставка
        $shippingMethods = [
            'standard' => [
                'name' => 'Стандартная доставка',
                'price' => 300,
                'days' => '3-5 дней',
            ],
            'express' => [
                'name' => 'Экспресс-доставка',
                'price' => 600,
                'days' => '1-2 дня',
            ],
            'pickup' => [
                'name' => 'Самовывоз',
                'price' => 0,
                'days' => '1-2 дня',
            ],
        ];

        // Расчет экологического вклада
        $ecoImpact = $this->calculateEcoImpact($cart);
        
        return view('pages.checkout', compact(
            'cart',
            'checkout',
            'addresses',
            'shippingMethods',
            'ecoImpact'
        ));
    }

    public function updateShipping(Request $request)
    {
        $request->validate([
            'shipping_method' => 'required|in:standard,express,pickup',
        ]);

        session(['checkout.shipping_method' => $request->shipping_method]);

        return redirect()->back();
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_address_id' => 'required_if:shipping_address_type,existing|nullable|exists:addresses,id',
            'billing_address_id' => 'required_if:billing_address_type,existing|nullable|exists:addresses,id',
            'shipping_address_type' => 'required|in:existing,new',
            'billing_address_type' => 'required|in:existing,new,same',
            'payment_method' => 'required|in:credit_card,paypal,bank_transfer',
            'carbon_offset' => 'boolean',
            
            // Валидация для нового адреса доставки
            'shipping_first_name' => 'required_if:shipping_address_type,new',
            'shipping_last_name' => 'required_if:shipping_address_type,new',
            'shipping_address_line1' => 'required_if:shipping_address_type,new',
            'shipping_city' => 'required_if:shipping_address_type,new',
            'shipping_state' => 'required_if:shipping_address_type,new',
            'shipping_postal_code' => 'required_if:shipping_address_type,new',
            'shipping_country' => 'required_if:shipping_address_type,new',
            'shipping_phone' => 'nullable',
            
            // Валидация для нового платежного адреса
            'billing_first_name' => 'required_if:billing_address_type,new',
            'billing_last_name' => 'required_if:billing_address_type,new',
            'billing_address_line1' => 'required_if:billing_address_type,new',
            'billing_city' => 'required_if:billing_address_type,new',
            'billing_state' => 'required_if:billing_address_type,new',
            'billing_postal_code' => 'required_if:billing_address_type,new',
            'billing_country' => 'required_if:billing_address_type,new',
            'billing_phone' => 'nullable',
            
            // Данные карты (если выбрана оплата картой)
            'card_number' => 'required_if:payment_method,credit_card',
            'card_name' => 'required_if:payment_method,credit_card',
            'card_expiry' => 'required_if:payment_method,credit_card',
            'card_cvv' => 'required_if:payment_method,credit_card',
        ]);

        // Получаем корзину
        $cart = $this->getCart();
        
        // Проверяем, что корзина не пуста
        $cartData = auth()->check() ? (auth()->user()->cart_data ?? []) : (session()->get('guest_cart', []));
        if (empty($cartData)) {
            return redirect()->route('shop')->with('error', 'Ваша корзина пуста.');
        }

        DB::beginTransaction();

        try {
            // Обрабатываем адрес доставки
            $shippingAddressId = null;
            
            if ($request->shipping_address_type === 'existing') {
                $shippingAddressId = $request->shipping_address_id;
            } else {
                // Создаем новый адрес доставки
                $addressData = [
                    'type' => 'shipping',
                    'is_default' => false,
                    'first_name' => $request->shipping_first_name,
                    'last_name' => $request->shipping_last_name,
                    'address_line1' => $request->shipping_address_line1,
                    'address_line2' => $request->shipping_address_line2,
                    'city' => $request->shipping_city,
                    'state' => $request->shipping_state,
                    'postal_code' => $request->shipping_postal_code,
                    'country' => $request->shipping_country,
                    'phone' => $request->shipping_phone,
                ];

                if (auth()->check()) {
                    $addressData['user_id'] = auth()->id();
                }

                $shippingAddress = Address::create($addressData);
                
                $shippingAddressId = $shippingAddress->id;
            }
            
            // Обрабатываем платежный адрес
            $billingAddressId = null;
            
            if ($request->billing_address_type === 'existing') {
                $billingAddressId = $request->billing_address_id;
            } elseif ($request->billing_address_type === 'same') {
                $billingAddressId = $shippingAddressId;
            } else {
                // Создаем новый платежный адрес
                $addressData = [
                    'type' => 'billing',
                    'is_default' => false,
                    'first_name' => $request->billing_first_name,
                    'last_name' => $request->billing_last_name,
                    'address_line1' => $request->billing_address_line1,
                    'address_line2' => $request->billing_address_line2,
                    'city' => $request->billing_city,
                    'state' => $request->billing_state,
                    'postal_code' => $request->billing_postal_code,
                    'country' => $request->billing_country,
                    'phone' => $request->billing_phone,
                ];

                if (auth()->check()) {
                    $addressData['user_id'] = auth()->id();
                }

                $billingAddress = Address::create($addressData);
                
                $billingAddressId = $billingAddress->id;
            }
            
            // Рассчитываем суммы
            $subtotal = $cart->getSubtotal();
            $taxRate = config('settings.tax_rate', 0.2); // 20% НДС
            $taxAmount = $subtotal * $taxRate;
            
            // Доставка
            $shippingMethod = session('checkout.shipping_method', 'standard');
            $shippingMethods = [
                'standard' => 300,
                'express' => 600,
                'pickup' => 0,
            ];
            $shippingAmount = $shippingMethods[$shippingMethod];
            
            // Общая сумма
            $totalAmount = $subtotal + $taxAmount + $shippingAmount;
            
            // Создаем заказ
            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'shipping_address_id' => $shippingAddressId,
                'billing_address_id' => $billingAddressId,
                'payment_method' => $request->payment_method,
                'shipping_method' => $shippingMethod,
                'notes' => $request->notes,
                'carbon_offset' => $request->has('carbon_offset'),
            ]);
            
            // Добавляем товары к заказу
            foreach ($cart->items as $item) {
                $product = $item->product;
                $variant = $item->variant;
                
                $price = $variant ? $variant->price : $product->price;
                $itemTax = $price * $taxRate;
                
                // Создаем элемент заказа
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'variant_id' => $variant ? $variant->id : null,
                    'name' => $product->name . ($variant ? ' (' . $variant->attributeValues->pluck('value')->join(', ') . ')' : ''),
                    'sku' => $variant ? $variant->sku : $product->sku,
                    'price' => $price,
                    'quantity' => $item->quantity,
                    'subtotal' => $price * $item->quantity,
                    'tax_amount' => $itemTax * $item->quantity,
                    'discount_amount' => 0, // Для простоты у нас нет скидок на отдельные товары
                    'eco_impact' => $this->calculateItemEcoImpact($product, $variant, $item->quantity),
                ]);
                
                // Обновляем запасы
                if ($variant) {
                    $variant->decrement('stock_quantity', $item->quantity);
                } else {
                    $product->decrement('quantity', $item->quantity);
                }
            }
            
            // Создаем запись об экологическом вкладе
            if ($request->has('carbon_offset')) {
                $ecoImpact = $this->calculateEcoImpact($cart);
                
                EcoImpactRecord::create([
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'carbon_saved' => $ecoImpact['carbon_saved'],
                    'plastic_saved' => $ecoImpact['plastic_saved'],
                    'water_saved' => $ecoImpact['water_saved'],
                    'type' => 'purchase',
                    'description' => 'Экологический вклад от покупки',
                ]);
                
                // Обновляем экологический счет пользователя
                if (auth()->check()) {
                    $user = auth()->user();
                    $user->increment('eco_impact_score', $ecoImpact['carbon_saved'] * 0.1);
                }
            }
            
            // Очищаем корзину и данные оформления заказа
            $cart->clear();
            if (auth()->check()) {
                auth()->user()->cart_data = ['items' => []];
                auth()->user()->save();
            } else {
                session()->forget('cart_data');
            }
            session()->forget('checkout.shipping_method');
            
            DB::commit();
            
            // Перенаправляем на страницу успешного заказа
            return redirect()->route('checkout.success', $order)->with('success', 'Ваш заказ успешно оформлен!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()->with('error', 'Произошла ошибка при оформлении заказа: ' . $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        // Проверяем, принадлежит ли заказ текущему пользователю
        if (auth()->check() && $order->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Если пользователь не авторизован, проверяем по сессии
        if (!auth()->check() && !session()->has('order_' . $order->id)) {
            abort(403);
        }
        
        // Метка для гостевого доступа к заказу
        if (!auth()->check()) {
            session(['order_' . $order->id => true]);
        }
        
        return view('pages.checkout-success', compact('order'));
    }

   /**
    * Получить или создать объект оформления заказа
    */
   protected function getOrCreateCheckout($cart)
   {
       // Пытаемся получить существующий чекаут из сессии
       $checkoutId = session('checkout_id');
       $checkout = $checkoutId ? Checkout::find($checkoutId) : null;

       if (!$checkout) {
           // Создаем новый чекаут
           $checkout = new Checkout();
           $checkout->shipping_method = session('checkout.shipping_method', 'standard');
       }

       // Обновляем суммы
       $checkout->subtotal = $cart->getSubtotal();
       $checkout->tax_amount = $checkout->calculateTax();
       $checkout->shipping_amount = $checkout->calculateShipping();
       $checkout->total_amount = $checkout->calculateTotal();

       $checkout->save();

       // Сохраняем ID в сессии
       session(['checkout_id' => $checkout->id]);

       return $checkout;
   }

   /**
    * Получить корзину для текущего пользователя или сессии.
    */
    protected function getCart()
    {
        // Для авторизованных пользователей
        if (auth()->check()) {
            $user = auth()->user();
            $cartData = $user->cart_data ?? [];
        } else {
            // Для гостей
            $cartData = session('guest_cart', []);
        }

        // Преобразуем данные корзины в нужный формат
        $items = collect($cartData)->map(function ($item) {
            if (!isset($item['product_id'])) {
                return null;
            }

            $product = Product::find($item['product_id']);
            if (!$product || $product->status !== 'published') {
                return null;
            }

            $variant = isset($item['variant_id']) ? Variant::find($item['variant_id']) : null;
            if (isset($item['variant_id']) && !$variant) {
                return null;
            }

            if (!isset($item['quantity'])) {
                return null;
            }

            return (object)[
                'product' => $product,
                'variant' => $variant,
                'quantity' => $item['quantity']
            ];
        })->filter()->values();

        // Создаем объект корзины
        $cart = new Cart(auth()->id(), $cartData);
        
        // Добавляем коллекцию items как свойство
        $cart->items = $items;
        
        return $cart;
    }

    /**
     * Рассчитать экологический вклад для товара.
     */
    protected function calculateItemEcoImpact($product, $variant, $quantity)
    {
        // Здесь можно реализовать сложную логику расчета на основе характеристик товара
        // Для примера используем упрощенный подход
        
        $ecoFeatures = $product->ecoFeatures;
        $carbonSaved = 0;
        $plasticSaved = 0;
        $waterSaved = 0;
        
        // Пример расчета
        foreach ($ecoFeatures as $feature) {
            switch ($feature->slug) {
                case 'recycled-materials':
                    $carbonSaved += 0.5; // кг CO2
                    $plasticSaved += 0.2; // кг пластика
                    break;
                case 'organic-cotton':
                    $waterSaved += 2000; // литров воды
                    $carbonSaved += 0.3; // кг CO2
                    break;
                case 'biodegradable':
                    $plasticSaved += 0.5; // кг пластика
                    break;
                // Другие эко-особенности...
            }
        }
        
        return [
            'carbon_saved' => $carbonSaved * $quantity,
            'plastic_saved' => $plasticSaved * $quantity,
            'water_saved' => $waterSaved * $quantity,
        ];
    }

    /**
     * Рассчитать общий экологический вклад для корзины.
     */
    protected function calculateEcoImpact($cart)
    {
        $carbonSaved = 0;
        $plasticSaved = 0;
        $waterSaved = 0;
        
        foreach ($cart->items as $item) {
            $impact = $this->calculateItemEcoImpact($item->product, $item->variant, $item->quantity);
            
            $carbonSaved += $impact['carbon_saved'];
            $plasticSaved += $impact['plastic_saved'];
            $waterSaved += $impact['water_saved'];
        }
        
        return [
            'carbon_saved' => $carbonSaved,
            'plastic_saved' => $plasticSaved,
            'water_saved' => $waterSaved,
        ];
    }
}
