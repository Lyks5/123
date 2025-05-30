@extends('layouts.app')

@section('title', 'Оформление заказа')

@section('content')
<div class="bg-gray-50 py-10">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Оформление заказа</h1>
        
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

        @if($cart && $cart->items->count() > 0)
            <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Левая колонка - Адрес и оплата -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Адрес доставки -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold mb-6">Адрес доставки</h2>
                                
                                @if(auth()->check() && $addresses->count() > 0)
                                    <div class="mb-6">
                                        <div class="flex items-center mb-4">
                                            <input type="radio" name="shipping_address_type" id="existing_shipping" value="existing" checked 
                                                class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-gray-300 rounded">
                                            <label for="existing_shipping" class="ml-2 block text-gray-700">
                                                Выбрать из сохраненных адресов
                                            </label>
                                        </div>
                                        
                                        <div class="ml-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($addresses as $address)
                                                <div class="border rounded-md p-4 {{ $address->is_default && $address->type == 'shipping' ? 'border-eco-500 bg-eco-50' : 'border-gray-200' }}">
                                                    <input type="radio" name="shipping_address_id" id="shipping_address_{{ $address->id }}" 
                                                        value="{{ $address->id }}" {{ $address->is_default && $address->type == 'shipping' ? 'checked' : '' }}
                                                        class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-gray-300 rounded">
                                                    <label for="shipping_address_{{ $address->id }}" class="ml-2">
                                                        <span class="font-medium">{{ $address->first_name }} {{ $address->last_name }}</span><br>
                                                        {{ $address->address_line1 }}
                                                        @if($address->address_line2), {{ $address->address_line2 }}@endif<br>
                                                        {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}<br>
                                                        {{ $address->country }}
                                                        @if($address->phone)<br>{{ $address->phone }}@endif
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        <div class="flex items-center mt-4">
                                            <input type="radio" name="shipping_address_type" id="new_shipping" value="new" 
                                                class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-gray-300 rounded">
                                            <label for="new_shipping" class="ml-2 block text-gray-700">
                                                Добавить новый адрес
                                            </label>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="shipping_address_type" value="new">
                                @endif
                                
                                <div id="new_shipping_form" class="{{ auth()->check() && $addresses->count() > 0 ? 'hidden' : '' }}">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="shipping_first_name" class="block text-sm font-medium text-gray-700 mb-1">Имя</label>
                                            <input type="text" name="shipping_first_name" id="shipping_first_name" value="{{ old('shipping_first_name') }}" 
                                                class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                            @error('shipping_first_name')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="shipping_last_name" class="block text-sm font-medium text-gray-700 mb-1">Фамилия</label>
                                            <input type="text" name="shipping_last_name" id="shipping_last_name" value="{{ old('shipping_last_name') }}" 
                                                class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                            @error('shipping_last_name')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <label for="shipping_address_line1" class="block text-sm font-medium text-gray-700 mb-1">Адрес</label>
                                        <input type="text" name="shipping_address_line1" id="shipping_address_line1" value="{{ old('shipping_address_line1') }}" 
                                            class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                        @error('shipping_address_line1')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="mt-4">
                                        <label for="shipping_address_line2" class="block text-sm font-medium text-gray-700 mb-1">
                                            Дополнительная информация (кв./офис) <span class="text-gray-500">(необязательно)</span>
                                        </label>
                                        <input type="text" name="shipping_address_line2" id="shipping_address_line2" value="{{ old('shipping_address_line2') }}" 
                                            class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                        <div>
                                            <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-1">Город</label>
                                            <input type="text" name="shipping_city" id="shipping_city" value="{{ old('shipping_city') }}" 
                                                class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                            @error('shipping_city')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="shipping_state" class="block text-sm font-medium text-gray-700 mb-1">Область/край</label>
                                            <input type="text" name="shipping_state" id="shipping_state" value="{{ old('shipping_state') }}" 
                                                class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                            @error('shipping_state')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700 mb-1">Индекс</label>
                                            <input type="text" name="shipping_postal_code" id="shipping_postal_code" value="{{ old('shipping_postal_code') }}" 
                                                class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                            @error('shipping_postal_code')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <label for="shipping_country" class="block text-sm font-medium text-gray-700 mb-1">Страна</label>
                                        <select name="shipping_country" id="shipping_country" 
                                            class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                            <option value="Россия" {{ old('shipping_country') == 'Россия' ? 'selected' : '' }}>Россия</option>
                                            <option value="Беларусь" {{ old('shipping_country') == 'Беларусь' ? 'selected' : '' }}>Беларусь</option>
                                            <option value="Казахстан" {{ old('shipping_country') == 'Казахстан' ? 'selected' : '' }}>Казахстан</option>
                                            <option value="Украина" {{ old('shipping_country') == 'Украина' ? 'selected' : '' }}>Украина</option>
                                        </select>
                                        @error('shipping_country')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="mt-4">
                                        <label for="shipping_phone" class="block text-sm font-medium text-gray-700 mb-1">Телефон</label>
                                        <input type="text" name="shipping_phone" id="shipping_phone" value="{{ old('shipping_phone') }}" 
                                            class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                        @error('shipping_phone')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    @if(auth()->check())
                                        <div class="mt-4">
                                            <div class="flex items-center">
                                                <input type="checkbox" name="save_shipping_address" id="save_shipping_address" value="1" 
                                                    class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-gray-300 rounded">
                                                <label for="save_shipping_address" class="ml-2 block text-sm text-gray-700">
                                                    Сохранить адрес для будущих заказов
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Платежный адрес -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold mb-6">Платежный адрес</h2>
                                
                                <div class="mb-6">
                                    <div class="flex items-center mb-4">
                                        <input type="radio" name="billing_address_type" id="same_billing" value="same" checked 
                                            class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-gray-300 rounded">
                                        <label for="same_billing" class="ml-2 block text-gray-700">
                                            Использовать адрес доставки для оплаты
                                        </label>
                                    </div>
                                    
                                    @if(auth()->check() && $addresses->count() > 0)
                                        <div class="flex items-center mb-4">
                                            <input type="radio" name="billing_address_type" id="existing_billing" value="existing" 
                                                class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-gray-300 rounded">
                                            <label for="existing_billing" class="ml-2 block text-gray-700">
                                                Выбрать из сохраненных адресов
                                            </label>
                                        </div>
                                        
                                        <div class="ml-6 hidden" id="existing_billing_addresses">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                @foreach($addresses as $address)
                                                    <div class="border rounded-md p-4 {{ $address->is_default && $address->type == 'billing' ? 'border-eco-500 bg-eco-50' : 'border-gray-200' }}">
                                                        <input type="radio" name="billing_address_id" id="billing_address_{{ $address->id }}" 
                                                            value="{{ $address->id }}" {{ $address->is_default && $address->type == 'billing' ? 'checked' : '' }}
                                                            class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-gray-300 rounded">
                                                        <label for="billing_address_{{ $address->id }}" class="ml-2">
                                                            <span class="font-medium">{{ $address->first_name }} {{ $address->last_name }}</span><br>
                                                            {{ $address->address_line1 }}
                                                            @if($address->address_line2), {{ $address->address_line2 }}@endif<br>
                                                            {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}<br>
                                                            {{ $address->country }}
                                                            @if($address->phone)<br>{{ $address->phone }}@endif
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center mt-4">
                                        <input type="radio" name="billing_address_type" id="new_billing" value="new" 
                                            class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-gray-300 rounded">
                                        <label for="new_billing" class="ml-2 block text-gray-700">
                                            Добавить новый адрес
                                        </label>
                                    </div>
                                </div>
                                
                                <div id="new_billing_form" class="hidden">
                                    <!-- Форма для нового платежного адреса, идентична форме для адреса доставки -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="billing_first_name" class="block text-sm font-medium text-gray-700 mb-1">Имя</label>
                                            <input type="text" name="billing_first_name" id="billing_first_name" value="{{ old('billing_first_name') }}" 
                                                class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                        </div>
                                        
                                        <div>
                                            <label for="billing_last_name" class="block text-sm font-medium text-gray-700 mb-1">Фамилия</label>
                                            <input type="text" name="billing_last_name" id="billing_last_name" value="{{ old('billing_last_name') }}" 
                                                class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <label for="billing_address_line1" class="block text-sm font-medium text-gray-700 mb-1">Адрес</label>
                                        <input type="text" name="billing_address_line1" id="billing_address_line1" value="{{ old('billing_address_line1') }}" 
                                            class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                    </div>
                                    
                                    <div class="mt-4">
                                        <label for="billing_address_line2" class="block text-sm font-medium text-gray-700 mb-1">
                                            Дополнительная информация (кв./офис) <span class="text-gray-500">(необязательно)</span>
                                        </label>
                                        <input type="text" name="billing_address_line2" id="billing_address_line2" value="{{ old('billing_address_line2') }}" 
                                            class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                        <div>
                                            <label for="billing_city" class="block text-sm font-medium text-gray-700 mb-1">Город</label>
                                            <input type="text" name="billing_city" id="billing_city" value="{{ old('billing_city') }}" 
                                                class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                        </div>
                                        
                                        <div>
                                            <label for="billing_state" class="block text-sm font-medium text-gray-700 mb-1">Область/край</label>
                                            <input type="text" name="billing_state" id="billing_state" value="{{ old('billing_state') }}" 
                                                class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                        </div>
                                        
                                        <div>
                                            <label for="billing_postal_code" class="block text-sm font-medium text-gray-700 mb-1">Индекс</label>
                                            <input type="text" name="billing_postal_code" id="billing_postal_code" value="{{ old('billing_postal_code') }}" 
                                                class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <label for="billing_country" class="block text-sm font-medium text-gray-700 mb-1">Страна</label>
                                        <select name="billing_country" id="billing_country" 
                                            class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                            <option value="Россия" {{ old('billing_country') == 'Россия' ? 'selected' : '' }}>Россия</option>
                                            <option value="Беларусь" {{ old('billing_country') == 'Беларусь' ? 'selected' : '' }}>Беларусь</option>
                                            <option value="Казахстан" {{ old('billing_country') == 'Казахстан' ? 'selected' : '' }}>Казахстан</option>
                                            <option value="Украина" {{ old('billing_country') == 'Украина' ? 'selected' : '' }}>Украина</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <label for="billing_phone" class="block text-sm font-medium text-gray-700 mb-1">Телефон</label>
                                        <input type="text" name="billing_phone" id="billing_phone" value="{{ old('billing_phone') }}" 
                                            class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                    </div>
                                    
                                    @if(auth()->check())
                                        <div class="mt-4">
                                            <div class="flex items-center">
                                                <input type="checkbox" name="save_billing_address" id="save_billing_address" value="1" 
                                                    class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-gray-300 rounded">
                                                <label for="save_billing_address" class="ml-2 block text-sm text-gray-700">
                                                    Сохранить адрес для будущих заказов
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Способ доставки -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold mb-6">Способ доставки</h2>
                                
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <input type="radio" name="shipping_method" id="shipping_standard" value="standard" 
                                            {{ session('checkout.shipping_method', 'standard') === 'standard' ? 'checked' : '' }}
                                            class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-gray-300 rounded">
                                        <label for="shipping_standard" class="ml-3 flex flex-col">
                                            <span class="block text-sm font-medium text-gray-700">Стандартная доставка</span>
                                            <span class="block text-sm text-gray-500">{{ $shippingMethods['standard']['days'] }} • {{ number_format($shippingMethods['standard']['price'], 0, '.', ' ') }} ₽</span>
                                        </label>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="radio" name="shipping_method" id="shipping_express" value="express" 
                                            {{ session('checkout.shipping_method') === 'express' ? 'checked' : '' }}
                                            class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-gray-300 rounded">
                                        <label for="shipping_express" class="ml-3 flex flex-col">
                                            <span class="block text-sm font-medium text-gray-700">Экспресс-доставка</span>
                                            <span class="block text-sm text-gray-500">{{ $shippingMethods['express']['days'] }} • {{ number_format($shippingMethods['express']['price'], 0, '.', ' ') }} ₽</span>
                                        </label>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="radio" name="shipping_method" id="shipping_pickup" value="pickup" 
                                            {{ session('checkout.shipping_method') === 'pickup' ? 'checked' : '' }}
                                            class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-gray-300 rounded">
                                        <label for="shipping_pickup" class="ml-3 flex flex-col">
                                            <span class="block text-sm font-medium text-gray-700">Самовывоз из магазина</span>
                                            <span class="block text-sm text-gray-500">{{ $shippingMethods['pickup']['days'] }} • Бесплатно</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Способ оплаты -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold mb-6">Способ оплаты</h2>
                                
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <input type="radio" name="payment_method" id="payment_card" value="credit_card" checked 
                                            class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-gray-300 rounded">
                                        <label for="payment_card" class="ml-3 block text-sm font-medium text-gray-700">
                                            Банковская карта
                                        </label>
                                    </div>
                                    
                                    <div class="ml-7 pt-4 payment-details" id="card_payment_details">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="card_number" class="block text-sm font-medium text-gray-700 mb-1">Номер карты</label>
                                                <input type="text" name="card_number" id="card_number" placeholder="0000 0000 0000 0000" 
                                                    class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                                @error('card_number')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            
                                            <div>
                                                <label for="card_name" class="block text-sm font-medium text-gray-700 mb-1">Имя на карте</label>
                                                <input type="text" name="card_name" id="card_name" placeholder="IVAN IVANOV" 
                                                    class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                                @error('card_name')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-4 mt-4">
                                            <div>
                                                <label for="card_expiry" class="block text-sm font-medium text-gray-700 mb-1">Срок действия</label>
                                                <input type="text" name="card_expiry" id="card_expiry" placeholder="MM/YY" 
                                                    class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                                @error('card_expiry')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            
                                            <div>
                                                <label for="card_cvv" class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                                                <input type="text" name="card_cvv" id="card_cvv" placeholder="000" 
                                                    class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                                @error('card_cvv')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 flex items-center justify-between">
                                            <span class="text-sm text-gray-500">Данные защищены</span>
                                            <div class="flex items-center space-x-2">
                                                <img src="{{ asset('images/payment/visa.svg') }}" alt="Visa" class="h-5">
                                                <img src="{{ asset('images/payment/mastercard.svg') }}" alt="Mastercard" class="h-5">
                                                <img src="{{ asset('images/payment/mir.svg') }}" alt="МИР" class="h-5">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="radio" name="payment_method" id="payment_paypal" value="paypal" 
                                            class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-gray-300 rounded">
                                        <label for="payment_paypal" class="ml-3 block text-sm font-medium text-gray-700">
                                            PayPal
                                        </label>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="radio" name="payment_method" id="payment_bank" value="bank_transfer" 
                                            class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-gray-300 rounded">
                                        <label for="payment_bank" class="ml-3 block text-sm font-medium text-gray-700">
                                            Банковский перевод
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Дополнительная информация -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="p-6">
                                <h2 class="text-lg font-semibold mb-4">Дополнительная информация</h2>
                                
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Примечания к заказу (необязательно)</label>
                                    <textarea name="notes" id="notes" rows="3" 
                                        placeholder="Укажите особые пожелания или информацию для доставки" 
                                        class="w-full rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                                </div>
                                
                                <div class="mt-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="carbon_offset" id="carbon_offset" value="1" 
                                            {{ old('carbon_offset') ? 'checked' : '' }}
                                            class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-gray-300 rounded">
                                        <label for="carbon_offset" class="ml-2 text-sm text-gray-700">
                                            Компенсировать углеродный след (+{{ number_format($ecoImpact['carbon_saved'] * 0.5, 0) }} ₽)
                                        </label>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 ml-6">
                                        Мы посадим {{ number_format($ecoImpact['carbon_saved'] * 0.1, 1) }} деревьев, чтобы компенсировать углеродный след от доставки
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Правая колонка - Итоги заказа -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden sticky top-6">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold mb-6">Ваш заказ</h2>
                                
                                <div class="divide-y divide-gray-200">
                                    @foreach($cart->items as $item)
                                        <div class="py-4 flex">
                                            <div class="flex-shrink-0 w-16 h-16">
                                                @if($item->product->primaryImage)
                                                    <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded">
                                                @else
                                                    <div class="w-full h-full bg-gray-200 rounded flex items-center justify-center">
                                                        <span class="text-gray-500 text-xs">Нет фото</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4 flex-1">
                                                <h3 class="text-sm font-medium text-gray-900">
                                                    {{ $item->product->name }}
                                                    @if($item->variant)
                                                        <span class="text-gray-500"> - {{ $item->variant->name }}</span>
                                                    @endif
                                                </h3>
                                                <p class="text-sm text-gray-500">Кол-во: {{ $item->quantity }}</p>
                                                <p class="mt-1 text-sm font-medium text-gray-900">
                                                    @if($item->variant)
                                                        {{ number_format($item->variant->price * $item->quantity, 0, '.', ' ') }} ₽
                                                    @else
                                                        {{ number_format($item->product->price * $item->quantity, 0, '.', ' ') }} ₽
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="border-t border-gray-200 pt-4 mt-4">
                                    <div class="flex justify-between mb-2">
                                        <span class="text-sm text-gray-600">Промежуточный итог:</span>
                                        <span class="text-sm font-medium">{{ number_format($checkout->subtotal, 0, '.', ' ') }} ₽</span>
                                    </div>
                                    
                                    <div class="flex justify-between mb-2">
                                        <span class="text-sm text-gray-600">НДС ({{ config('settings.tax_rate', 0.2) * 100 }}%):</span>
                                        <span class="text-sm font-medium">{{ number_format($checkout->tax_amount, 0, '.', ' ') }} ₽</span>
                                    </div>
                                    
                                    <div class="flex justify-between mb-2">
                                        <span class="text-sm text-gray-600">Доставка:</span>
                                        <span class="text-sm font-medium">{{ number_format($checkout->shipping_amount, 0, '.', ' ') }} ₽</span>
                                    </div>
                                    
                                    <div class="flex justify-between mb-4 pt-2 border-t border-gray-200">
                                        <span class="text-base font-semibold">Итого:</span>
                                        <span class="text-base font-semibold">{{ number_format($checkout->total_amount, 0, '.', ' ') }} ₽</span>
                                    </div>
                                </div>
                                
                                <div class="mt-6">
                                    <button type="submit" id="checkout-submit" class="w-full bg-eco-600 text-white text-center py-3 px-4 rounded-md hover:bg-eco-700 font-medium">
                                        Оформить заказ
                                    </button>
                                    
                                    <p class="mt-3 text-xs text-gray-500 text-center">
                                        Нажимая кнопку "Оформить заказ", вы соглашаетесь с нашими <a href="/terms" class="text-eco-600 hover:text-eco-700">Условиями предоставления услуг</a> и <a href="/privacy" class="text-eco-600 hover:text-eco-700">Политикой конфиденциальности</a>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Переключение формы адреса доставки
        const shippingAddressType = document.querySelectorAll('input[name="shipping_address_type"]');
        const newShippingForm = document.getElementById('new_shipping_form');
        
        if (shippingAddressType && newShippingForm) {
            shippingAddressType.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    if (this.value === 'new') {
                        newShippingForm.classList.remove('hidden');
                    } else {
                        newShippingForm.classList.add('hidden');
                    }
                });
            });
        }
        
        // Переключение формы платежного адреса
        const billingAddressType = document.querySelectorAll('input[name="billing_address_type"]');
        const newBillingForm = document.getElementById('new_billing_form');
        const existingBillingAddresses = document.getElementById('existing_billing_addresses');
        
        if (billingAddressType && newBillingForm && existingBillingAddresses) {
            billingAddressType.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    if (this.value === 'new') {
                        newBillingForm.classList.remove('hidden');
                        existingBillingAddresses.classList.add('hidden');
                    } else if (this.value === 'existing') {
                        newBillingForm.classList.add('hidden');
                        existingBillingAddresses.classList.remove('hidden');
                    } else {
                        newBillingForm.classList.add('hidden');
                        existingBillingAddresses.classList.add('hidden');
                    }
                });
            });
        }
        
        // Обновление способа доставки
        const shippingMethod = document.querySelectorAll('input[name="shipping_method"]');
        
        if (shippingMethod) {
            shippingMethod.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    fetch('{{ route('checkout.shipping') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            shipping_method: this.value
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        }
                    });
                });
            });
        }
        
        // Переключение способа оплаты
        const paymentMethod = document.querySelectorAll('input[name="payment_method"]');
        const cardPaymentDetails = document.getElementById('card_payment_details');
        
        if (paymentMethod && cardPaymentDetails) {
            paymentMethod.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    if (this.value === 'credit_card') {
                        cardPaymentDetails.classList.remove('hidden');
                    } else {
                        cardPaymentDetails.classList.add('hidden');
                    }
                });
            });
        }
    });
</script>
@endpush
@endsection