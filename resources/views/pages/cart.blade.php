@extends('layouts.app')

@section('title', 'Корзина')

@section('content')
<div class="bg-gray-50 py-10">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Корзина</h1>
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif
        
        @if($cart->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold mb-6">Товары в корзине ({{ $cart->items->count() }})</h2>
                            
                            <div class="divide-y divide-gray-200">
                                @foreach($cart->items as $item)
                                    <div class="py-6 flex flex-col sm:flex-row">
                                        <div class="flex-shrink-0 w-full sm:w-24 h-24 mb-4 sm:mb-0">
                                            @if($item->product->primaryImage)
                                                <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-md">
                                            @else
                                                <div class="w-full h-full bg-gray-200 rounded-md flex items-center justify-center">
                                                    <span class="text-gray-500 text-xs">Нет фото</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="flex-1 sm:ml-6">
                                            <div class="flex flex-wrap justify-between mb-2">
                                                <div>
                                                    <h3 class="text-lg font-medium text-gray-900">
                                                        <a href="{{ route('product.show', $item->product->slug) }}" class="hover:text-eco-600">
                                                            {{ $item->product->name }}
                                                        </a>
                                                    </h3>
                                                    <p class="text-sm text-gray-500">Артикул: {{ $item->product->sku }}</p>
                                                </div>
                                                <div class="text-right">
                                                    @if($item->product->sale_price)
                                                        <p class="text-eco-600 font-semibold">
                                                            {{ number_format($item->product->sale_price, 0, '.', ' ') }} ₽
                                                        </p>
                                                        <p class="text-sm text-gray-500 line-through">
                                                            {{ number_format($item->product->price, 0, '.', ' ') }} ₽
                                                        </p>
                                                    @else
                                                        <p class="font-semibold">
                                                            {{ number_format($item->product->price, 0, '.', ' ') }} ₽
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            @if($item->variant)
                                                <div class="text-sm text-gray-700 mb-4">
                                                    <span class="font-medium">Вариант:</span> 
                                                    {{ $item->variant->name }}
                                                </div>
                                            @endif
                                            
                                            <div class="flex flex-wrap items-center justify-between mt-4">
                                                <div class="flex items-center border border-gray-300 rounded-md">
<form action="{{ route('cart.update') }}" method="POST" class="inline-flex">
    @csrf
    @method('PATCH')
    <input type="hidden" name="items[0][id]" value="{{ $item->id }}">
    <input type="hidden" name="items[0][quantity]" value="{{ max(1, $item->quantity - 1) }}">
    <button type="submit" class="px-3 py-1 text-gray-600 hover:bg-gray-100">−</button>
</form>

                                                    
                                                    <span class="px-3 py-1 text-gray-700 font-medium">{{ $item->quantity }}</span>
                                                    
<form action="{{ route('cart.update') }}" method="POST" class="inline-flex">
    @csrf
    @method('PATCH')
    <input type="hidden" name="items[0][id]" value="{{ $item->id }}">
    <input type="hidden" name="items[0][quantity]" value="{{ min($item->product->stock_quantity, $item->quantity + 1) }}">
    <button type="submit" class="px-3 py-1 text-gray-600 hover:bg-gray-100" {{ $item->quantity >= $item->product->stock_quantity ? 'disabled' : '' }}>+</button>
</form>

                                                </div>
                                                
                                                <div class="mt-4 sm:mt-0 flex items-center gap-4">
                                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот товар из корзины?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-sm text-red-600 hover:text-red-700 flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            Удалить
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-6 flex justify-between">
                                <a href="{{ route('shop') }}" class="inline-flex items-center text-eco-600 hover:text-eco-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Продолжить покупки
                                </a>
                                
                                <form action="{{ route('cart.remove') }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите очистить корзину?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700">
                                        Очистить корзину
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden sticky top-6">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold mb-6">Итого</h2>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Стоимость товаров:</span>
                                    <span class="font-medium">{{ number_format($cart->getTotalAttribute(), 0, '.', ' ') }} ₽</span>
                                </div>
                                
                                @if($cart->discount_amount > 0)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Скидка:</span>
                                        <span class="font-medium text-red-600">-{{ number_format($cart->discount_amount, 0, '.', ' ') }} ₽</span>
                                    </div>
                                @endif
                                
                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex justify-between text-lg font-semibold">
                                        <span>Итого к оплате:</span>
                                        <span>{{ number_format($cart->getTotalAttribute(), 0, '.', ' ') }} ₽</span>
                                    </div>
                                </div>
                                
                                <a href="{{ route('checkout') }}" class="w-full bg-eco-600 text-white text-center py-3 px-4 rounded-md hover:bg-eco-700 font-medium mt-4 block">
                                    Оформить заказ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold mb-2">Ваша корзина пуста</h2>
                <p class="text-gray-600 mb-6">Добавьте товары в корзину, чтобы оформить заказ</p>
                <a href="{{ route('shop') }}" class="inline-flex items-center justify-center bg-eco-600 text-white py-3 px-6 rounded-md hover:bg-eco-700">
                    Перейти в магазин
                </a>
            </div>
            
            @if($recommendedProducts && $recommendedProducts->count() > 0)
                <div class="mt-12">
                    <h2 class="text-2xl font-semibold mb-6">Рекомендуемые товары</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($recommendedProducts as $product)
                            @include('components.product-card', ['product' => $product])
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
