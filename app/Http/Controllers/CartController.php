<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        $recommendedProducts = $this->getRecommendedProducts();

        return view('pages.cart', [
            'cart' => $cart,
            'recommendedProducts' => $recommendedProducts
        ]);
    }

public function add(Request $request)
{
    try {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $variant = isset($validated['variant_id']) ? Variant::findOrFail($validated['variant_id']) : null;

        // Проверка доступности
        $this->validateAvailability($product, $variant, $validated['quantity']);

        // Получаем текущую корзину
        $cart = $this->getCart();

        try {
            // Добавляем товар в корзину
            $cart->addItem(
                $validated['product_id'],
                $validated['variant_id'] ?? null,
                $validated['quantity']
            );

            // Получаем данные корзины
            $cartArray = $cart->toArray();
            
            // Проверяем наличие ключа items
            if (!isset($cartArray['items'])) {
                throw new \Exception('Некорректная структура данных корзины');
            }

            // Сохраняем обновленную корзину
            if (auth()->check()) {
                auth()->user()->update(['cart_data' => $cartArray['items']]);
            } else {
                session()->put('guest_cart', $cartArray['items']);
            }

            // Получаем общее количество товаров в корзине
            $cartCount = collect($cartArray['items'])->sum('quantity');

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Товар добавлен в корзину',
                    'cartCount' => $cartCount,
                    'cartData' => $cart->getItems()
                ], 201);
            }

            return redirect()->route('cart')->with('success', 'Товар добавлен в корзину.');

        } catch (\Exception $e) {
            \Log::error('Ошибка при добавлении товара в корзину: ' . $e->getMessage(), [
                'product_id' => $validated['product_id'],
                'variant_id' => $validated['variant_id'] ?? null,
                'quantity' => $validated['quantity']
            ]);
            throw new \Exception('Ошибка при добавлении товара в корзину');
        }

    } catch (\Exception $e) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
        return redirect()->back()->with('error', $e->getMessage());
    }
}

private function normalizeCartData($cartData)
{
    if (!is_array($cartData)) {
        return collect();
    }

    // Преобразуем JSON-данные в коллекцию с моделями и проверяем наличие товаров
    return collect($cartData)->map(function($item) {
        if (!is_array($item) || !isset($item['product_id'])) {
            return null;
        }

        try {
            $product = Product::find($item['product_id']);
            if (!$product || $product->status !== 'published') {
                return null;
            }

            $variant = null;
            if (isset($item['variant_id'])) {
                $variant = Variant::find($item['variant_id']);
                if (!$variant) {
                    return null;
                }
            }

            return [
                'product' => $product,
                'variant' => $variant,
                'quantity' => min($item['quantity'] ?? 1, $product->stock_quantity)
            ];
        } catch (\Exception $e) {
            \Log::error('Error normalizing cart item: ' . $e->getMessage(), [
                'item' => $item
            ]);
            return null;
        }
    })->filter();
}

private function validateAvailability($product, $variant, $quantity)
{
    if ($product->status === 'archived') {
        throw ValidationException::withMessages([
            'product_id' => 'Этот товар находится в архиве.'
        ]);
    }

    if ($product->status !== 'published') {
        throw ValidationException::withMessages([
            'product_id' => 'Этот товар больше не доступен.'
        ]);
    }

    $stock = $variant ? $variant->stock_quantity : $product->stock_quantity;
    
    if ($stock < $quantity) {
        throw ValidationException::withMessages([
            'quantity' => 'Запрошенное количество недоступно.'
        ]);
    }
}

public function update(Request $request)
{
    $request->validate([
        'items' => 'required|array',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.variant_id' => 'nullable|exists:variants,id',
        'items.*.quantity' => 'required|integer|min:0',
    ]);

    $cartData = auth()->check() ? (auth()->user()->cart_data ?? []) : (session()->get('guest_cart', []));
    $hasErrors = false;

    foreach ($request->items as $requestItem) {
        // Находим элемент в корзине
        $foundIndex = $this->findCartItemIndex(
            $cartData,
            $requestItem['product_id'],
            $requestItem['variant_id']
        );

        if ($foundIndex === null) continue;

        // Проверяем доступность товара
        $product = Product::find($requestItem['product_id']);
        $variant = $requestItem['variant_id'] ? Variant::find($requestItem['variant_id']) : null;

        try {
            $this->validateAvailability($product, $variant, $requestItem['quantity']);
        } catch (ValidationException $e) {
            $hasErrors = true;
            continue;
        }

        // Обновляем или удаляем
        if ($requestItem['quantity'] == 0) {
            array_splice($cartData, $foundIndex, 1);
        } else {
            $cartData[$foundIndex]['quantity'] = $requestItem['quantity'];
        }
    }

    // Сохраняем обновленную корзину
    if (auth()->check()) {
        auth()->user()->update(['cart_data' => $cartData]);
    } else {
        session()->put('guest_cart', $cartData);
    }

    return $hasErrors
        ? redirect()->back()->with('error', 'Некоторые товары не были обновлены')
        : redirect()->back()->with('success', 'Корзина обновлена');
}

public function remove(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'variant_id' => 'nullable|exists:variants,id',
    ]);

    $cartData = auth()->check() ? (auth()->user()->cart_data ?? []) : (session()->get('guest_cart', []));
    
    $foundIndex = $this->findCartItemIndex(
        $cartData,
        $request->product_id,
        $request->variant_id
    );

    if ($foundIndex !== null) {
        array_splice($cartData, $foundIndex, 1);
        
        if (auth()->check()) {
            auth()->user()->update(['cart_data' => $cartData]);
        } else {
            session()->put('guest_cart', $cartData);
        }
    }

    return redirect()->back()->with('success', 'Товар удален из корзины');
}

public function applyCoupon(Request $request)
{
    $request->validate([
        'coupon_code' => 'required|string'
    ]);

    // TODO: Implement coupon logic
    return redirect()->back()->with('error', 'Функционал купонов временно недоступен');
}

private function findCartItemIndex($cartData, $productId, $variantId)
{
    foreach ($cartData as $index => $item) {
        if ($item['product_id'] == $productId && $item['variant_id'] == $variantId) {
            return $index;
        }
    }
    return null;
}

    

    /**
 * Получить корзину для текущего пользователя или сессии.
 */
protected function getCart()
{
    try {
        // Для авторизованных пользователей
        if (auth()->check()) {
            $user = auth()->user();
            $cartData = $user->cart_data ?? [];
            
            if (!is_array($cartData)) {
                $cartData = [];
            }

            // Объединение с гостевой корзиной при наличии
            if (session()->has('guest_cart')) {
                $guestCart = session('guest_cart', []);
                
                if (is_array($guestCart)) {
                    foreach ($guestCart as $guestItem) {
                        if (!isset($guestItem['product_id'])) {
                            continue;
                        }
                        
                        $found = false;
                        
                        // Ищем совпадения в пользовательской корзине
                        foreach ($cartData as &$userItem) {
                            if (isset($userItem['product_id']) &&
                                $userItem['product_id'] == $guestItem['product_id'] &&
                                ($userItem['variant_id'] ?? null) == ($guestItem['variant_id'] ?? null)) {
                                    $userItem['quantity'] = ($userItem['quantity'] ?? 0) + ($guestItem['quantity'] ?? 1);
                                    $found = true;
                                    break;
                            }
                        }
                        
                        // Добавляем новый элемент если не найдено совпадений
                        if (!$found) {
                            $cartData[] = [
                                'product_id' => $guestItem['product_id'],
                                'variant_id' => $guestItem['variant_id'] ?? null,
                                'quantity' => $guestItem['quantity'] ?? 1
                            ];
                        }
                    }
                }

                // Сохраняем обновлённую корзину
                $user->update(['cart_data' => $cartData]);
                session()->forget('guest_cart');
            }

            $items = $this->normalizeCartData($cartData);
            $cart = new Cart(auth()->id(), $cartData);
            $cart->items = $items;
            return $cart;

        // Для гостей
        } else {
            $guestCart = session('guest_cart', []);
            
            if (!is_array($guestCart)) {
                $guestCart = [];
            }
            
            $items = $this->normalizeCartData($guestCart);
            $cart = new Cart(null, $guestCart);
            $cart->items = $items;
            return $cart;
        }
    } catch (\Exception $e) {
        \Log::error('Error in getCart method: ' . $e->getMessage());
        return new Cart(auth()->id() ?? null, []);
    }
}
    
    protected function getRecommendedProducts()
    {
        return Product::where('status', 'published')->take(4)->get(); // Example logic
    }
}
