
@extends('layouts.app')

@section('title', 'Мои заказы - ЭкоМаркет')

@section('content')
<div class="bg-gray-50 py-8 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-eco-900">Мои заказы</h1>
            <p class="text-eco-700">Просмотр всех ваших заказов</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Боковое меню -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-eco-100 rounded-full flex items-center justify-center text-eco-700">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        @endif
                    </div>
                    <div class="ml-4">
                        <h3 class="font-medium text-eco-900">{{ auth()->user()->name }}</h3>
                        <p class="text-sm text-eco-600">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <nav class="space-y-1">
                    <a href="{{ route('account') }}" class="block px-4 py-2 rounded-md text-eco-700 hover:bg-eco-50 hover:text-eco-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Главная
                    </a>
                    <a href="{{ route('account.orders') }}" class="block px-4 py-2 rounded-md bg-eco-50 text-eco-900 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        Мои заказы
                    </a>
                    <a href="{{ route('account.profile') }}" class="block px-4 py-2 rounded-md text-eco-700 hover:bg-eco-50 hover:text-eco-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Профиль
                    </a>
                    <a href="{{ route('account.addresses') }}" class="block px-4 py-2 rounded-md text-eco-700 hover:bg-eco-50 hover:text-eco-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        Адреса
                    </a>
                    @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded-md text-eco-700 hover:bg-eco-50 hover:text-eco-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="3" y1="9" x2="21" y2="9"></line>
                            <line x1="9" y1="21" x2="9" y2="9"></line>
                        </svg>
                        Админ-панель
                    </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 rounded-md text-eco-700 hover:bg-eco-50 hover:text-eco-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            Выйти
                        </button>
                    </form>
                </nav>
            </div>

            <!-- Основное содержимое -->
            <div class="col-span-1 md:col-span-3">
                @if(request()->has('id'))
                    @php $order = $orders->firstWhere('id', request()->get('id')); @endphp
                    @if($order)
                        <div class="bg-white rounded-lg shadow p-6 mb-6">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h2 class="text-xl font-semibold text-eco-900">Заказ #{{ $order->order_number }}</h2>
                                    <p class="text-sm text-eco-600">Размещен {{ $order->created_at->format('d.m.Y H:i') }}</p>
                                </div>
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                    @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                                    @elseif($order->status == 'delivered' || $order->status == 'completed') bg-green-100 text-green-800
                                    @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    @if($order->status == 'pending') Ожидает
                                    @elseif($order->status == 'processing') Обработка
                                    @elseif($order->status == 'shipped') Отправлен
                                    @elseif($order->status == 'delivered') Доставлен
                                    @elseif($order->status == 'completed') Завершен
                                    @elseif($order->status == 'cancelled') Отменен
                                    @else Неизвестно
                                    @endif
                                </span>
                            </div>

                            <div class="border-t border-b border-eco-200 py-4 my-4">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="font-medium text-eco-900">Товары заказа</h3>
                                    <span class="text-sm text-eco-600">{{ $order->items->count() }} {{ trans_choice('товар|товара|товаров', $order->items->count()) }}</span>
                                </div>
                                <div class="space-y-4 mt-4">
                                    @foreach($order->items as $item)
                                    <div class="flex items-center">
                                        <div class="h-16 w-16 bg-eco-50 rounded-md flex-shrink-0 overflow-hidden">
                                            @if($item->product && $item->product->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $item->product->images->where('is_primary', true)->first()->image_path) }}" alt="{{ $item->product_name }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center text-eco-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                        <polyline points="21 15 16 10 5 21"></polyline>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <h4 class="text-sm font-medium text-eco-900">{{ $item->product_name }}</h4>
                                            @if($item->variant_name)
                                                <p class="text-xs text-eco-600">Вариант: {{ $item->variant_name }}</p>
                                            @endif
                                            <div class="flex justify-between items-center mt-1">
                                                <span class="text-sm text-eco-700">{{ number_format($item->price, 0, '.', ' ') }} ₽ × {{ $item->quantity }}</span>
                                                <span class="text-sm font-medium text-eco-900">{{ number_format($item->total, 0, '.', ' ') }} ₽</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <h3 class="font-medium text-eco-900 mb-2">Адрес доставки</h3>
                                    <div class="bg-eco-50 rounded-md p-4 text-sm">
                                        <p class="font-medium">{{ $order->shipping_name }}</p>
                                        <p>{{ $order->shipping_address_line1 }}</p>
                                        @if($order->shipping_address_line2)
                                            <p>{{ $order->shipping_address_line2 }}</p>
                                        @endif
                                        <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}</p>
                                        <p>{{ $order->shipping_country }}</p>
                                        @if($order->shipping_phone)
                                            <p class="mt-2">Телефон: {{ $order->shipping_phone }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-medium text-eco-900 mb-2">Способ оплаты</h3>
                                    <div class="bg-eco-50 rounded-md p-4 text-sm">
                                        <p>{{ $order->payment_method == 'card' ? 'Банковская карта' : ($order->payment_method == 'cash' ? 'Наличными при получении' : 'Не указано') }}</p>
                                        @if($order->payment_status)
                                            <p class="mt-2">Статус оплаты: 
                                                <span class="
                                                    @if($order->payment_status == 'paid') text-green-700 font-medium
                                                    @elseif($order->payment_status == 'pending') text-yellow-700 font-medium
                                                    @else text-red-700 font-medium
                                                    @endif
                                                ">
                                                    {{ $order->payment_status == 'paid' ? 'Оплачено' : ($order->payment_status == 'pending' ? 'Ожидает оплаты' : 'Не оплачено') }}
                                                </span>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-eco-200 pt-4">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm text-eco-700">Сумма товаров</span>
                                    <span class="text-sm text-eco-900">{{ number_format($order->subtotal, 0, '.', ' ') }} ₽</span>
                                </div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm text-eco-700">Доставка</span>
                                    <span class="text-sm text-eco-900">{{ number_format($order->shipping_cost, 0, '.', ' ') }} ₽</span>
                                </div>
                                @if($order->discount > 0)
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm text-eco-700">Скидка</span>
                                    <span class="text-sm text-red-600">-{{ number_format($order->discount, 0, '.', ' ') }} ₽</span>
                                </div>
                                @endif
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm text-eco-700">Налог</span>
                                    <span class="text-sm text-eco-900">{{ number_format($order->tax, 0, '.', ' ') }} ₽</span>
                                </div>
                                <div class="flex justify-between font-medium text-eco-900 pt-2 border-t border-eco-200 mt-2">
                                    <span>Итого</span>
                                    <span>{{ number_format($order->total, 0, '.', ' ') }} ₽</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <a href="{{ route('account.orders') }}" class="text-eco-600 hover:text-eco-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="19" y1="12" x2="5" y2="12"></line>
                                    <polyline points="12 19 5 12 12 5"></polyline>
                                </svg>
                                Назад к списку заказов
                            </a>
                            
                            @if($order->status != 'cancelled' && $order->status != 'completed')
                                <a href="#" class="text-eco-600 hover:text-eco-700">Связаться с нами по заказу</a>
                            @endif
                        </div>
                    @else
                        <div class="bg-yellow-50 text-yellow-800 p-4 rounded-lg shadow mb-6">
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                                <span>Заказ не найден. <a href="{{ route('account.orders') }}" class="underline">Вернуться к списку заказов</a>.</span>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold text-eco-900 mb-4">Все заказы</h2>
                        
                        @if($orders->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-eco-200">
                                    <thead class="bg-eco-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-eco-700 uppercase tracking-wider">№ заказа</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-eco-700 uppercase tracking-wider">Дата</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-eco-700 uppercase tracking-wider">Статус</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-eco-700 uppercase tracking-wider">Товары</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-eco-700 uppercase tracking-wider">Сумма</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-eco-700 uppercase tracking-wider"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-eco-200">
                                        @foreach($orders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-eco-900">{{ $order->order_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-eco-600">{{ $order->created_at->format('d.m.Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'processing' => 'bg-blue-100 text-blue-800',
                                                        'shipped' => 'bg-purple-100 text-purple-800',
                                                        'delivered' => 'bg-green-100 text-green-800',
                                                        'completed' => 'bg-green-100 text-green-800',
                                                        'cancelled' => 'bg-red-100 text-red-800',
                                                    ];
                                                    $statusNames = [
                                                        'pending' => 'Ожидает',
                                                        'processing' => 'Обработка',
                                                        'shipped' => 'Отправлен',
                                                        'delivered' => 'Доставлен',
                                                        'completed' => 'Завершен',
                                                        'cancelled' => 'Отменен',
                                                    ];
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ $statusNames[$order->status] ?? 'Неизвестно' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-eco-600">{{ $order->items->count() }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-eco-600">{{ number_format($order->total, 0, '.', ' ') }} ₽</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('account.orders') }}?id={{ $order->id }}" class="text-eco-600 hover:text-eco-900">Подробнее</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4">
                                {{ $orders->links() }}
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-eco-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                    <line x1="3" y1="6" x2="21" y2="6"></line>
                                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                                </svg>
                                <p class="mt-4 text-eco-600">У вас пока нет заказов.</p>
                                <a href="{{ route('shop') }}" class="mt-2 inline-block text-eco-600 hover:text-eco-700 font-medium">Перейти в магазин →</a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection