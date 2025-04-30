<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
{
    // Получаем корзину из JSON-поля пользователя
    $cartData = auth()->user()->cart_data ?? [];
    $recommendedProducts = $this->getRecommendedProducts();

    return view('pages.cart', [
        'cart' => $this->normalizeCartData($cartData),
        'recommendedProducts' => $recommendedProducts
    ]);
}

public function add(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'variant_id' => 'nullable|exists:variants,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $product = Product::findOrFail($request->product_id);
    $variant = $request->variant_id ? Variant::findOrFail($request->variant_id) : null;

    // Проверка доступности
    $this->validateAvailability($product, $variant, $request->quantity);

    // Обновляем корзину
    $user = auth()->user();
    $cartData = $user->cart_data ?? [];
    
    $cartData = $this->updateCartItems(
        $cartData,
        $request->product_id,
        $request->variant_id,
        $request->quantity
    );

    // Сохраняем обновлённую корзину
    $user->update(['cart_data' => $cartData]);

    return redirect()->route('cart')->with('success', 'Товар добавлен в корзину.');
}

private function normalizeCartData($cartData)
{
    // Преобразуем JSON-данные в коллекцию с моделями
    return collect($cartData)->map(function($item) {
        return [
            'product' => Product::find($item['product_id']),
            'variant' => $item['variant_id'] ? Variant::find($item['variant_id']) : null,
            'quantity' => $item['quantity']
        ];
    })->filter();
}

private function validateAvailability($product, $variant, $quantity)
{
    if (!$product->is_active) {
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

    $user = auth()->user();
    $cartData = $user->cart_data ?? [];
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

        if (!$this->isItemValid($product, $variant, $requestItem['quantity'])) {
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

    $user->update(['cart_data' => $cartData]);

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

    $user = auth()->user();
    $cartData = $user->cart_data ?? [];
    
    $foundIndex = $this->findCartItemIndex(
        $cartData,
        $request->product_id,
        $request->variant_id
    );

    if ($foundIndex !== null) {
        array_splice($cartData, $foundIndex, 1);
        $user->update(['cart_data' => $cartData]);
    }

    return redirect()->back()->with('success', 'Товар удален из корзины');
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

        // Объединение с гостевой корзиной при наличии
        if (session()->has('guest_cart')) {
            $guestCart = session('guest_cart', []);

            foreach ($guestCart as $guestItem) {
                $found = false;
                
                // Ищем совпадения в пользовательской корзине
                foreach ($cartData as &$userItem) {
                    if ($userItem['product_id'] == $guestItem['product_id'] 
                        && $userItem['variant_id'] == $guestItem['variant_id']) {
                        $userItem['quantity'] += $guestItem['quantity'];
                        $found = true;
                        break;
                    }
                }
                
                // Добавляем новый элемент если не найдено совпадений
                if (!$found) {
                    $cartData[] = $guestItem;
                }
            }

            // Сохраняем обновлённую корзину
            $user->update(['cart_data' => $cartData]);
            session()->forget('guest_cart');
        }

        return $this->normalizeCartData($cartData);

    // Для гостей
    } else {
        $guestCart = session('guest_cart', []);
        return $this->normalizeCartData($guestCart);
    }
}
    
    protected function getRecommendedProducts()
    {
        return Product::where('is_active', true)->take(4)->get(); // Example logic
    }
}
