<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController
{
    // app/Http/Controllers/CartController.php
public function addToCart(Request $request, Product $product)
{
    $cart = session()->get('cart', []);
    
    $cart[$product->id] = [
        'id' => $product->id,
        'name' => $product->name,
        'price' => $product->price,
        'quantity' => $request->quantity ?? 1,
        'image' => $product->image
    ];
    
    session()->put('cart', $cart);
    
    return back()->with('success', 'Товар добавлен в корзину');
}
}
