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
        $cart = $this->getCart();
        
        return view('pages.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Проверяем, что товар активен
        if (!$product->is_active) {
            return redirect()->back()->with('error', 'Этот товар больше не доступен.');
        }

        // Проверяем наличие варианта, если указан
        if ($request->variant_id) {
            $variant = Variant::findOrFail($request->variant_id);
            
            if (!$variant->is_active) {
                return redirect()->back()->with('error', 'Этот вариант товара больше не доступен.');
            }
            
            // Проверяем наличие на складе
            if ($variant->stock_quantity < $request->quantity) {
                return redirect()->back()->with('error', 'К сожалению, запрошенное количество недоступно.');
            }
        } else {
            // Проверяем наличие на складе
            if ($product->stock_quantity < $request->quantity) {
                return redirect()->back()->with('error', 'К сожалению, запрошенное количество недоступно.');
            }
        }

        // Получаем или создаем корзину
        $cart = $this->getCart();

        // Проверяем, есть ли уже такой товар в корзине
        $cartItem = $cart->items()
            ->where('product_id', $request->product_id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($cartItem) {
            // Обновляем количество
            $cartItem->update([
                'quantity' => $cartItem->quantity + $request->quantity,
            ]);
        } else {
            // Создаем новый элемент корзины
            $cart->items()->create([
                'product_id' => $request->product_id,
                'variant_id' => $request->variant_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('cart')->with('success', 'Товар добавлен в корзину.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:cart_items,id',
            'items.*.quantity' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $item) {
            $cartItem = CartItem::findOrFail($item['id']);
            
            // Проверяем, принадлежит ли элемент текущей корзине
            if ($cartItem->cart_id !== $this->getCart()->id) {
                continue;
            }

            if ($item['quantity'] == 0) {
                // Удаляем элемент, если количество 0
                $cartItem->delete();
            } else {
                // Проверяем наличие на складе
                $product = $cartItem->product;
                $variant = $cartItem->variant;
                
                $stockQuantity = $variant ? $variant->stock_quantity : $product->stock_quantity;
                
                if ($stockQuantity < $item['quantity']) {
                    return redirect()->back()->with('error', "К сожалению, для товара '{$product->name}' доступно только {$stockQuantity} шт.");
                }
                
                // Обновляем количество
                $cartItem->update([
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        return redirect()->back()->with('success', 'Корзина обновлена.');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id',
        ]);

        $cartItem = CartItem::findOrFail($request->item_id);
        
        // Проверяем, принадлежит ли элемент текущей корзине
        if ($cartItem->cart_id === $this->getCart()->id) {
            $cartItem->delete();
        }

        return redirect()->back()->with('success', 'Товар удален из корзины.');
    }

    

    /**
     * Получить корзину для текущего пользователя или сессии.
     */
    protected function getCart()
    {
        if (auth()->check()) {
            // Для авторизованного пользователя
            $cart = Cart::firstOrCreate([
                'user_id' => auth()->id(),
            ]);

            // Если есть временная корзина, объединяем с пользовательской
            if (session()->has('cart_id')) {
                $sessionCart = Cart::where('session_id', session('cart_id'))->first();
                
                if ($sessionCart && $sessionCart->id !== $cart->id) {
                    // Переносим товары из временной корзины
                    foreach ($sessionCart->items as $item) {
                        $existingItem = $cart->items()
                            ->where('product_id', $item->product_id)
                            ->where('variant_id', $item->variant_id)
                            ->first();

                        if ($existingItem) {
                            $existingItem->update([
                                'quantity' => $existingItem->quantity + $item->quantity,
                            ]);
                        } else {
                            $cart->items()->create([
                                'product_id' => $item->product_id,
                                'variant_id' => $item->variant_id,
                                'quantity' => $item->quantity,
                            ]);
                        }
                    }

                    // Удаляем временную корзину
                    $sessionCart->delete();
                    session()->forget('cart_id');
                }
            }
        } else {
            // Для гостя
            $sessionId = session()->get('cart_id');
            
            if (!$sessionId) {
                $sessionId = Str::uuid();
                session()->put('cart_id', $sessionId);
            }
            
            $cart = Cart::firstOrCreate([
                'session_id' => $sessionId,
            ]);
        }

        return $cart->load('items.product', 'items.variant');
    }
}
