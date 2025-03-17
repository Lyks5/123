@extends('layouts.app')

@section('title', 'Заказ оформлен')

@section('content')
<div class="bg-gray-50 py-10">
    <div class="container mx-auto px-4">
        <div class="bg-white rounded-lg shadow-sm p-8 max-w-3xl mx-auto">
            <div class="text-center mb-8">
                <div class="mx-auto w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                
                <h1 class="text-2xl font-bold text-gray-900">Спасибо за ваш заказ!</h1>
                <p class="text-gray-600 mt-2">Ваш заказ №{{ $order->id }} успешно оформлен</p>
                
                @if($order->tracking_number)
                    <p class="text-gray-600 mt-2">Номер для отслеживания: <span class="font-medium">{{ $order->tracking_number }}</span></p>
                @endif
            </div>
            
            <div class="border-t border-gray-200 pt-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Информация о заказе</h2>
                
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500">Дата заказа:</dt>
                        <dd class="font-medium">{{ $order->created_at->format('d.m.Y H:i') }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-gray-500">Статус:</dt>
                        <dd>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @if($order->status === 'pending')
                                    bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'processing')
                                    bg-blue-100 text-blue-800
                                @elseif($order->status === 'completed')
                                    bg-green-100 text-green-800
                                @elseif($order->status === 'cancelled')
                                    bg-red-100 text-red-800
                                @else
                                    bg-gray-100 text-gray-800
                                @endif
                            ">
                                @if($order->status === 'pending')
                                    Ожидает обработки
                                @elseif($order->status === 'processing')
                                    В обработке
                                @elseif($order->status === 'completed')
                                    Завершен
                                @elseif($order->status === 'cancelled')
                                    Отменен
                                @else
                                    {{ $order->status }}
                                @endif
                            </span>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-gray-500">Способ оплаты:</dt>
                        <dd class="font-medium">
                            @if($order->payment_method === 'credit_card')
                                Банковская карта
                            @elseif($order->payment_method === 'paypal')
                                PayPal
                            @elseif($order->payment_method === 'bank_transfer')
                                Банковский перевод
                            @else
                                {{ $order->payment_method }}
                            @endif
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-gray-500">Способ доставки:</dt>
                        <dd class="font-medium">
                            @if($order->shipping_method === 'standard')
                                Стандартная доставка
                            @elseif($order->shipping_method === 'express')
                                Экспресс-доставка
                            @elseif($order->shipping_method === 'pickup')
                                Самовывоз
                            @else
                                {{ $order->shipping_method }}
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
            
            <div class="border-t border-gray-200 pt-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Детали заказа</h2>
                
                <div class="divide-y divide-gray-200">
                    @foreach($order->items as $item)
                        <div class="py-4 flex">
                            <div class="flex-shrink-0 w-16 h-16">
                                @if($item->product && $item->product->primaryImage)
                                    <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" alt="{{ $item->name }}" class="w-full h-full object-cover rounded">
                                @else
                                    <div class="w-full h-full bg-gray-200 rounded flex items-center justify-center">
                                        <span class="text-gray-500 text-xs">Нет фото</span>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-sm font-medium text-gray-900">{{ $item->name }}</h3>
                                <p class="text-sm text-gray-500">Кол-во: {{ $item->quantity }} · {{ number_format($item->price, 0, '.', ' ') }} ₽</p>
                                <p class="mt-1 text-sm font-medium text-gray-900">{{ number_format($item->subtotal, 0, '.', ' ') }} ₽</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Итоги</h2>
                
                <dl class="space-y-2">
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Промежуточный итог:</dt>
                        <dd class="font-medium">{{ number_format($order->subtotal, 0, '.', ' ') }} ₽</dd>
                    </div>
                    
                    <div class="flex justify-between">
                        <dt class="text-gray-600">НДС:</dt>
                        <dd class="font-medium">{{ number_format($order->tax_amount, 0, '.', ' ') }} ₽</dd>
                    </div>
                    
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Доставка:</dt>
                        <dd class="font-medium">{{ number_format($order->shipping_amount, 0, '.', ' ') }} ₽</dd>
                    </div>
                    
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Скидка:</dt>
                            <dd class="font-medium text-red-600">-{{ number_format($order->discount_amount, 0, '.', ' ') }} ₽</dd>
                        </div>
                    @endif
                    
                    <div class="flex justify-between pt-2 border-t border-gray-200">
                        <dt class="text-gray-900 font-semibold">Итого:</dt>
                        <dd class="text-gray-900 font-semibold">{{ number_format($order->total_amount, 0, '.', ' ') }} ₽</dd>
                    </div>
                </dl>
            </div>
            
            @if($order->ecoImpactRecord)
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <h3 class="text-green-800 font-medium mb-2">Ваш экологический вклад</h3>
                    <p class="text-green-700 text-sm">Благодаря этому заказу вы сохранили:</p>
                    <ul class="mt-2 space-y-1 text-sm">
                        <li class="flex items-center text-green-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ number_format($order->ecoImpactRecord->carbon_saved, 1) }} кг CO₂
                        </li>
                        <li class="flex items-center text-green-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ number_format($order->ecoImpactRecord->plastic_saved, 1) }} кг пластика
                        </li>
                        <li class="flex items-center text-green-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ number_format($order->ecoImpactRecord->water_saved, 0) }} литров воды
                        </li>
                    </ul>
                </div>
            @endif
            
            <div class="text-center mt-8">
                @if(auth()->check())
                    <a href="{{ route('account.orders') }}" class="inline-flex bg-eco-600 text-white py-2 px-6 rounded-md hover:bg-eco-700">
                        История заказов
                    </a>
                @endif
                
                <a href="{{ route('shop') }}" class="inline-flex bg-gray-100 text-gray-800 py-2 px-6 rounded-md hover:bg-gray-200 ml-4">
                    Продолжить покупки
                </a>
            </div>
        </div>
    </div>
</div>
@endsection