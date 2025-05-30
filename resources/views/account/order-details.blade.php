@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-eco-50 to-white py-12">
    <div class="container mx-auto px-4">
        <!-- Навигация -->
        <div class="max-w-6xl mx-auto mb-8">
            <a href="{{ route('account.orders') }}" class="inline-flex items-center text-eco-600 hover:text-eco-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Вернуться к списку заказов
            </a>
        </div>

        <!-- Основной контент -->
        <div class="max-w-6xl mx-auto">
            <!-- Шапка заказа -->
            <div class="bg-white rounded-t-2xl shadow-sm p-8 border border-eco-100">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <p class="text-sm text-eco-600 mb-1">Заказ создан {{ $order->created_at->format('d.m.Y в H:i') }}</p>
                        <h1 class="text-3xl font-bold text-eco-900">Заказ #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h1>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="text-2xl font-bold text-eco-900">{{ number_format($order->total_amount, 0, '.', ' ') }} ₽</span>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mt-2
                            @if($order->status === 'completed') bg-green-100 text-green-800
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @switch($order->status)
                                @case('completed')
                                    ✓ Заказ выполнен
                                    @break
                                @case('processing')
                                    ⚡ В обработке
                                    @break
                                @case('cancelled')
                                    ✕ Отменён
                                    @break
                                @default
                                    • {{ ucfirst($order->status) }}
                            @endswitch
                        </span>
                    </div>
                </div>

                <!-- Прогресс заказа -->
                <div class="mt-8">
                    <div class="relative">
                        <div class="flex justify-between mb-2">
                            <div class="w-1/4 text-center">
                                <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center
                                    {{ in_array($order->status, ['pending', 'processing', 'shipped', 'completed']) ? 'bg-eco-600 text-white' : 'bg-gray-200' }}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <span class="block mt-2 text-sm font-medium {{ in_array($order->status, ['pending', 'processing', 'shipped', 'completed']) ? 'text-eco-900' : 'text-gray-500' }}">
                                    Оформлен
                                </span>
                            </div>
                            <div class="w-1/4 text-center">
                                <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center
                                    {{ in_array($order->status, ['processing', 'shipped', 'completed']) ? 'bg-eco-600 text-white' : 'bg-gray-200' }}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <span class="block mt-2 text-sm font-medium {{ in_array($order->status, ['processing', 'shipped', 'completed']) ? 'text-eco-900' : 'text-gray-500' }}">
                                    Оплачен
                                </span>
                            </div>
                            <div class="w-1/4 text-center">
                                <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center
                                    {{ in_array($order->status, ['shipped', 'completed']) ? 'bg-eco-600 text-white' : 'bg-gray-200' }}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="block mt-2 text-sm font-medium {{ in_array($order->status, ['shipped', 'completed']) ? 'text-eco-900' : 'text-gray-500' }}">
                                    Отправлен
                                </span>
                            </div>
                            <div class="w-1/4 text-center">
                                <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center
                                    {{ $order->status === 'completed' ? 'bg-eco-600 text-white' : 'bg-gray-200' }}">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="block mt-2 text-sm font-medium {{ $order->status === 'completed' ? 'text-eco-900' : 'text-gray-500' }}">
                                    Доставлен
                                </span>
                            </div>
                        </div>
                        <div class="relative pt-1">
                            <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-200">
                                <div class="bg-eco-600 transition-all duration-500" style="width: 
                                    {{ $order->status === 'completed' ? '100%' : 
                                       ($order->status === 'shipped' ? '75%' : 
                                       ($order->status === 'processing' ? '50%' : 
                                       ($order->status === 'pending' ? '25%' : '0%'))) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <!-- Информация о доставке -->
                <div class="md:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-eco-100 overflow-hidden">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-eco-900 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Информация о доставке
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Адрес доставки -->
                                <div class="bg-eco-50/30 rounded-lg p-4">
                                    <h3 class="font-medium text-eco-900 mb-3">Адрес доставки</h3>
                                    @if($order->shippingAddress)
                                        <div class="space-y-2 text-eco-700">
                                            <p class="font-medium">{{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}</p>
                                            <p>{{ $order->shippingAddress->address_line1 }}</p>
                                            @if($order->shippingAddress->address_line2)
                                                <p>{{ $order->shippingAddress->address_line2 }}</p>
                                            @endif
                                            <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}</p>
                                            <p>{{ $order->shippingAddress->postal_code }}, {{ $order->shippingAddress->country }}</p>
                                            @if($order->shippingAddress->phone)
                                                <p class="flex items-center mt-3 text-eco-600">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                    </svg>
                                                    {{ $order->shippingAddress->phone }}
                                                </p>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-eco-600">Адрес доставки не указан</p>
                                    @endif
                                </div>

                                <!-- Способ доставки и оплаты -->
                                <div class="bg-eco-50/30 rounded-lg p-4">
                                    <h3 class="font-medium text-eco-900 mb-3">Детали доставки</h3>
                                    <div class="space-y-3 text-eco-700">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                            </svg>
                                            <span>{{ $order->shipping_method }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                            <span>{{ $order->payment_method }}</span>
                                        </div>
                                        @if($order->tracking_number)
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                                <span>Трек-номер: {{ $order->tracking_number }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Товары -->
                    <div class="bg-white rounded-xl shadow-sm border border-eco-100 overflow-hidden mt-6">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-eco-900 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                Товары в заказе
                            </h2>

                            <div class="space-y-4">
                                @foreach($order->items as $item)
                                    <div class="flex items-center bg-eco-50/30 rounded-lg p-4">
                                        <div class="w-20 h-20 flex-shrink-0">
                                            @if($item->product && $item->product->primaryImage)
                                                <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" 
                                                    alt="{{ $item->name }}" 
                                                    class="w-full h-full object-cover rounded-lg shadow-sm">
                                            @else
                                                <div class="w-full h-full bg-eco-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-eco-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-6 flex-1">
                                            <h3 class="text-lg font-medium text-eco-900">{{ $item->name }}</h3>
                                            <p class="text-eco-600">Количество: {{ $item->quantity }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-medium text-eco-900">{{ number_format($item->price * $item->quantity, 0, '.', ' ') }} ₽</p>
                                            <p class="text-sm text-eco-600">{{ number_format($item->price, 0, '.', ' ') }} ₽ за шт.</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Сумма заказа -->
                <div class="md:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-eco-100 overflow-hidden sticky top-6">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-eco-900 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                Сумма заказа
                            </h2>

                            <div class="space-y-4">
                                <div class="flex justify-between text-eco-600">
                                    <span>Товары:</span>
                                    <span>{{ number_format($order->subtotal, 0, '.', ' ') }} ₽</span>
                                </div>
                                <div class="flex justify-between text-eco-600">
                                    <span>НДС (20%):</span>
                                    <span>{{ number_format($order->tax_amount, 0, '.', ' ') }} ₽</span>
                                </div>
                                <div class="flex justify-between text-eco-600">
                                    <span>Доставка:</span>
                                    <span>{{ number_format($order->shipping_amount, 0, '.', ' ') }} ₽</span>
                                </div>
                                <div class="pt-4 mt-4 border-t border-eco-100">
                                    <div class="flex justify-between text-lg font-semibold text-eco-900">
                                        <span>Итого:</span>
                                        <span>{{ number_format($order->total_amount, 0, '.', ' ') }} ₽</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection