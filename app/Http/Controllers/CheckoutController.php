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
use App\Services\ArrivalStockService;

class CheckoutController extends Controller
{
    protected $arrivalStockService;

    public function __construct(ArrivalStockService $arrivalStockService)
    {
        $this->arrivalStockService = $arrivalStockService;
    }

    public function index()
    {
        $cart = $this->getCart();
        
        if ($cart->isEmpty()) {
            return redirect()->route('shop')->with('error', 'Ваша корзина пуста. Добавьте товары перед оформлением заказа.');
        }

        $checkout = $this->getOrCreateCheckout($cart);
        
        $addresses = auth()->check() ? auth()->user()->addresses : collect();
        
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
            'shipping_first_name' => 'required_if:shipping_address_type,new',
            'shipping_last_name' => 'required_if:shipping_address_type,new',
            'shipping_address_line1' => 'required_if:shipping_address_type,new',
            'shipping_city' => 'required_if:shipping_address_type,new',
            'shipping_state' => 'required_if:shipping_address_type,new',
            'shipping_postal_code' => 'required_if:shipping_address_type,new',
            'shipping_country' => 'required_if:shipping_address_type,new',
            'shipping_phone' => 'nullable',
            'billing_first_name' => 'required_if:billing_address_type,new',
            'billing_last_name' => 'required_if:billing_address_type,new',
            'billing_address_line1' => 'required_if:billing_address_type,new',
            'billing_city' => 'required_if:billing_address_type,new',
            'billing_state' => 'required_if:billing_address_type,new',
            'billing_postal_code' => 'required_if:billing_address_type,new',
            'billing_country' => 'required_if:billing_address_type,new',
            'billing_phone' => 'nullable',
            'card_number' => 'required_if:payment_method,credit_card',
            'card_name' => 'required_if:payment_method,credit_card',
            'card_expiry' => 'required_if:payment_method,credit_card',
            'card_cvv' => 'required_if:payment_method,credit_card',
        ]);

        $cart = $this->getCart();
        
        $cartData = auth()->check() ? (auth()->user()->cart_data ?? []) : (session()->get('guest_cart', []));
        if (empty($cartData)) {
            return redirect()->route('shop')->with('error', 'Ваша корзина пуста.');
        }

        DB::beginTransaction();

        try {
            $shippingAddressId = null;
            
            if ($request->shipping_address_type === 'existing') {
                $shippingAddressId = $request->shipping_address_id;
            } else {
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
            
            $billingAddressId = null;
            
            if ($request->billing_address_type === 'existing') {
                $billingAddressId = $request->billing_address_id;
            } elseif ($request->billing_address_type === 'same') {
                $billingAddressId = $shippingAddressId;
            } else {
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
            
            $subtotal = $cart->getSubtotal();
            $taxRate = config('settings.tax_rate', 0.2);
            $taxAmount = $subtotal * $taxRate;
            
            $shippingMethod = session('checkout.shipping_method', 'standard');
            $shippingMethods = [
                'standard' => 300,
                'express' => 600,
                'pickup' => 0,
            ];
            $shippingAmount = $shippingMethods[$shippingMethod];
            
            $totalAmount = $subtotal + $taxAmount + $shippingAmount;
            
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
            
            foreach ($cart->items as $item) {
                $product = $item->product;
                $variant = $item->variant;
                
                $price = $variant ? $variant->price : $product->price;
                $itemTax = $price * $taxRate;
                
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
                    'discount_amount' => 0,
                    'eco_impact' => $this->calculateItemEcoImpact($product, $variant, $item->quantity),
                ]);
                
                if ($variant) {
                    $variant->decrement('stock_quantity', $item->quantity);
                } else {
                    $product->decrement('stock_quantity', $item->quantity);
                    $this->arrivalStockService->decrementStock($product, $item->quantity);
                }
            }
            
            $ecoImpact = $this->calculateEcoImpact($cart);
            
            // Создаем запись в eco_impact_records
            EcoImpactRecord::create([
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'carbon_saved' => $ecoImpact['carbon_saved'],
                'plastic_saved' => $ecoImpact['plastic_saved'],
                'water_saved' => $ecoImpact['water_saved'],
                'type' => 'purchase',
                'description' => 'Экологический вклад от покупки'
            ]);
            
            if (auth()->check()) {
                $user = auth()->user();
                
                // Обновляем eco_impact_score (0.02 очка за каждую единицу эко-метрик)
                $score_increment = 0;
                $score_increment += $ecoImpact['carbon_saved'] * 0.02; // За каждый кг CO2
                $score_increment += $ecoImpact['plastic_saved'] * 0.02; // За каждый кг пластика
                $score_increment += $ecoImpact['water_saved'] * 0.02; // За каждый литр воды
                
                // Если выбрана carbon offset опция, добавляем бонус
                if ($request->has('carbon_offset')) {
                    $score_increment *= 1.5; // 50% бонус за carbon offset
                }
                
                $user->increment('eco_impact_score', $score_increment);
            }
            
            $cart->clear();
            if (auth()->check()) {
                auth()->user()->cart_data = ['items' => []];
                auth()->user()->save();
            } else {
                session()->forget('cart_data');
            }
            session()->forget('checkout.shipping_method');
            
            DB::commit();
            
            return redirect()->route('checkout.success', $order)->with('success', 'Ваш заказ успешно оформлен!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()->with('error', 'Произошла ошибка при оформлении заказа: ' . $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        if (auth()->check() && $order->user_id !== auth()->id()) {
            abort(403);
        }
        
        if (!auth()->check() && !session()->has('order_' . $order->id)) {
            abort(403);
        }
        
        if (!auth()->check()) {
            session(['order_' . $order->id => true]);
        }
        
        return view('pages.checkout-success', compact('order'));
    }

    protected function getOrCreateCheckout($cart)
    {
        $checkoutId = session('checkout_id');
        $checkout = $checkoutId ? Checkout::find($checkoutId) : null;

        if (!$checkout) {
            $checkout = new Checkout();
            $checkout->shipping_method = session('checkout.shipping_method', 'standard');
        }

        $checkout->subtotal = $cart->getSubtotal();
        $checkout->tax_amount = $checkout->calculateTax();
        $checkout->shipping_amount = $checkout->calculateShipping();
        $checkout->total_amount = $checkout->calculateTotal();

        $checkout->save();

        session(['checkout_id' => $checkout->id]);

        return $checkout;
    }

    protected function getCart()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $cartData = $user->cart_data ?? [];
        } else {
            $cartData = session('guest_cart', []);
        }

        $items = collect($cartData)->map(function ($item) {
            if (!is_array($item) || !isset($item['product_id'])) {
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

        $cart = new Cart(auth()->id(), $cartData);
        $cart->items = $items;
        
        return $cart;
    }

    protected function calculateItemEcoImpact($product, $variant, $quantity)
    {
        $ecoFeatures = $product->ecoFeatures;
        $carbonSaved = 0;
        $plasticSaved = 0;
        $waterSaved = 0;
        
        foreach ($ecoFeatures as $feature) {
            // Получаем значение из pivot таблицы и преобразуем в float
            $value = floatval($feature->pivot->value);
            
            // Проверяем тип эко-характеристики по slug и названию
            $slug = strtolower($feature->slug);
            $name = strtolower($feature->name);
            
            // Для углерода
            if (str_contains($slug, 'carbon') || str_contains($slug, 'co2') ||
                str_contains($name, 'carbon') || str_contains($name, 'co2')) {
                $carbonSaved += $value;
            }
            
            // Для пластика
            if (str_contains($slug, 'plastic') || str_contains($name, 'plastic') ||
                str_contains($slug, 'recycled') || str_contains($name, 'recycled')) {
                $plasticSaved += $value;
            }
            
            // Для воды
            if (str_contains($slug, 'water') || str_contains($name, 'water')) {
                $waterSaved += $value;
            }
        }
        
        // Учитываем количество и вес товара
        $weight = floatval($product->weight ?? 1);
        
        // Убедимся, что все значения положительные
        $carbonTotal = max(0, $carbonSaved * $quantity * $weight);
        $plasticTotal = max(0, $plasticSaved * $quantity * $weight);
        $waterTotal = max(0, $waterSaved * $quantity * $weight);
        
        // Возвращаем сумму эко-метрик с округлением до 2 знаков
        return [
            'carbon_saved' => round($carbonTotal, 2),
            'plastic_saved' => round($plasticTotal, 2),
            'water_saved' => round($waterTotal, 2),
        ];
    }

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
