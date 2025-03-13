@extends('layouts.app')

@section('title', 'Корзина покупок')

@section('content')
<div class="min-h-screen bg-background">
    @include('components.navbar')
    
    <div class="container-width px-4 py-8 pt-24">
        <h1 class="text-3xl font-bold text-eco-900 mb-8">Корзина покупок</h1>
        
        @if(count($cartItems) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <div class="hidden sm:grid sm:grid-cols-12 text-sm font-medium text-eco-600 mb-4">
                            <div class="sm:col-span-6">Товар</div>
                            <div class="sm:col-span-2 text-center">Цена</div>
                            <div class="sm:col-span-2 text-center">Количество</div>
                            <div class="sm:col-span-2 text-right">Итого</div>
                        </div>
                        
                        <hr class="mb-6" />
                        
                        @foreach($cartItems as $item)
                            <div class="mb-6">
                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                                    <!-- Product image and details -->
                                    <div class="sm:col-span-6">
                                        <div class="flex items-center">
                                            <div class="w-20 h-20 flex-shrink-0 rounded-md overflow-hidden mr-4">
                                                <img 
                                                    src="{{ $item->image }}" 
                                                    alt="{{ $item->name }}" 
                                                    class="w-full h-full object-cover"
                                                />
                                            </div>
                                            <div>
                                                <a 
                                                    href="{{ route('product.show', $item->id) }}" 
                                                    class="text-eco-900 font-medium hover:text-eco-700 transition-colors"
                                                >
                                                    {{ $item->name }}
                                                </a>
                                                <button 
                                                    onclick="event.preventDefault(); document.getElementById('remove-item-{{ $item->id }}').submit();"
                                                    class="flex items-center text-eco-600 hover:text-eco-900 text-sm mt-1"
                                                >
                                                    Удалить
                                                </button>
                                                <form id="remove-item-{{ $item->id }}" action="{{ route('cart.remove', $item->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Price -->
                                    <div class="sm:col-span-2 text-center">
                                        <span class="sm:hidden text-eco-600 mr-2">Цена:</span>
                                        <span class="text-eco-900">${{ number_format($item->price, 2) }}</span>
                                    </div>
                                    
                                    <!-- Quantity -->
                                    <div class="sm:col-span-2 flex justify-center">
                                        <div class="flex items-center border border-eco-200 rounded-lg">
                                            <button 
                                                onclick="event.preventDefault(); document.getElementById('decrease-quantity-{{ $item->id }}').submit();"
                                                class="py-1 px-3 text-eco-700 hover:text-eco-900 transition-colors"
                                                {{ $item->quantity === 1 ? 'disabled' : '' }}
                                            >
                                                -
                                            </button>
                                            <span class="py-1 px-3 text-eco-900 border-x border-eco-200">
                                                {{ $item->quantity }}
                                            </span>
                                            <button 
                                                onclick="event.preventDefault(); document.getElementById('increase-quantity-{{ $item->id }}').submit();"
                                                class="py-1 px-3 text-eco-700 hover:text-eco-900 transition-colors"
                                            >
                                                +
                                            </button>
                                        </div>
                                        <form id="decrease-quantity-{{ $item->id }}" action="{{ route('cart.update', $item->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                        </form>
                                        <form id="increase-quantity-{{ $item->id }}" action="{{ route('cart.update', $item->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                        </form>
                                    </div>
                                    
                                    <!-- Total -->
                                    <div class="sm:col-span-2 text-right">
                                        <span class="sm:hidden text-eco-600 mr-2">Итого:</span>
                                        <span class="text-eco-900 font-medium">
                                            ${{ number_format($item->price * $item->quantity, 2) }}
                                        </span>
                                    </div>
                                </div>
                                @if (!$loop->last)
                                    <hr class="my-6" />
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <a 
                            href="{{ route('shop') }}"
                            class="text-eco-700 hover:text-eco-900 transition-colors"
                        >
                            ← Продолжить покупки
                        </a>
                        <button 
                            class="border-eco-200 text-eco-900 hover:bg-eco-50"
                            onclick="event.preventDefault(); document.getElementById('clear-cart').submit();"
                        >
                            Очистить корзину
                        </button>
                        <form id="clear-cart" action="{{ route('cart.clear') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
                
                <!-- Order summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <h2 class="text-lg font-semibold text-eco-900 mb-4">Сводка заказа</h2>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between">
                                <span class="text-eco-700">Сумма</span>
                                <span class="text-eco-900">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-eco-700">Доставка</span>
                                <span class="text-eco-900">${{ number_format($shipping, 2) }}</span>
                            </div>
                            <hr />
                            <div class="flex justify-between font-semibold">
                                <span class="text-eco-900">Итого</span>
                                <span class="text-eco-900">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                        
                        <button 
                            class="w-full bg-eco-600 hover:bg-eco-700 text-white"
                            onclick="handleCheckout()"
                        >
                            Оформить заказ
                        </button>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center">
                            <span class="text-sm text-eco-700">
                                Мы используем эко-упаковку и методы доставки, чтобы минимизировать углеродный след вашего заказа.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-xl shadow-sm">
                <h2 class="text-xl font-semibold text-eco-900 mb-2">Ваша корзина пуста</h2>
                <p class="text-eco-700 mb-8 max-w-md mx-auto">
                    Похоже, вы еще не добавили товары в корзину. Начните покупки, чтобы найти экологичные товары для спорта.
                </p>
                <a href="{{ route('shop') }}">
                    <button class="bg-eco-600 hover:bg-eco-700 text-white">
                        Перейти в магазин
                    </button>
                </a>
            </div>
        @endif
    </div>
    
</div>
@endsection
