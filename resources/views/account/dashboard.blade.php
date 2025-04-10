@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')
<!-- Header spacing -->
<div class="h-20"></div>

<div class="bg-gradient-to-b from-eco-50 to-white py-12 min-h-screen">
    <div class="container mx-auto px-4">
        <!-- Page header -->
        <div class="mb-10 max-w-3xl mx-auto text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-eco-900 mb-3">Личный кабинет</h1>
            <p class="text-eco-600 text-lg max-w-2xl mx-auto">Добро пожаловать, {{ auth()->user()->name }}! Управляйте своими заказами и профилем</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 max-w-7xl mx-auto">
            <!-- Sidebar navigation -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden sticky top-24">
                    <div class="p-6 border-b border-eco-100">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-eco-100 rounded-full flex items-center justify-center text-eco-600 mr-4">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-medium text-eco-900">{{ auth()->user()->name }}</h3>
                                <p class="text-sm text-eco-600">{{ auth()->user()->email }}</p>
                                @if(auth()->user()->is_admin)
                                    <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        Администратор
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <nav class="p-3">
                        <div class="space-y-1">
                            <a href="{{ route('account') }}" class="block px-4 py-2.5 rounded-lg bg-eco-100 text-eco-900 transition-colors">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </svg>
                                    <span class="font-medium">Обзор</span>
                                </div>
                            </a>
                            
                            <a href="{{ route('account.orders') }}" class="block px-4 py-2.5 rounded-lg text-eco-700 hover:bg-eco-50 transition-colors">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                        <line x1="3" y1="6" x2="21" y2="6"></line>
                                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                                    </svg>
                                    <span class="font-medium">Мои заказы</span>
                                </div>
                            </a>
                            
                            <a href="{{ route('account.profile') }}" class="block px-4 py-2.5 rounded-lg text-eco-700 hover:bg-eco-50 transition-colors">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <span class="font-medium">Профиль</span>
                                </div>
                            </a>
                            <a href="{{ route('account.wishlists') }}" class="block px-4 py-2.5 rounded-lg text-eco-700 hover:bg-eco-50 transition-colors">
                                <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                                    <span class="font-medium">Избранное</span>
                                </div>
                            </a>
                            <a href="{{ route('account.addresses') }}" class="block px-4 py-2.5 rounded-lg text-eco-700 hover:bg-eco-50 transition-colors">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span class="font-medium">Адреса</span>
                                </div>
                            </a>
                            
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 rounded-lg text-eco-700 hover:bg-eco-50 transition-colors mt-4 border-t border-eco-100 pt-4">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="3" y1="9" x2="21" y2="9"></line>
                                            <line x1="9" y1="21" x2="9" y2="9"></line>
                                        </svg>
                                        <span class="font-medium">Админ-панель</span>
                                    </div>
                                </a>
                            @endif
                            
                            <form method="POST" action="{{ route('logout') }}" class="mt-4 border-t border-eco-100 pt-4">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                            <polyline points="16 17 21 12 16 7"></polyline>
                                            <line x1="21" y1="12" x2="9" y2="12"></line>
                                        </svg>
                                        <span class="font-medium">Выйти</span>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Main content -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Stats cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-eco-600 text-sm font-medium">Заказы</h3>
                                <p class="text-2xl font-bold text-eco-900 mt-1">{{ $user->orders->count() }}</p>
                            </div>
                            <div class="h-12 w-12 bg-eco-50 rounded-full flex items-center justify-center text-eco-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                    <line x1="3" y1="6" x2="21" y2="6"></line>
                                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-eco-600 text-sm font-medium">Избранное</h3>
                                <p class="text-2xl font-bold text-eco-900 mt-1">
                                    {{ $user->wishlists->sum(function($wishlist) { return $wishlist->items->count(); }) }}
                                </p>
                            </div>
                            <div class="h-12 w-12 bg-eco-50 rounded-full flex items-center justify-center text-eco-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-eco-600 text-sm font-medium">Эко-рейтинг</h3>
                                <p class="text-2xl font-bold text-eco-900 mt-1">
                                    {{ $user->eco_impact_score ?? 0 }}/10
                                </p>
                            </div>
                            <div class="h-12 w-12 bg-eco-50 rounded-full flex items-center justify-center text-eco-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                                    <path d="M19 7v4h-4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Eco Impact -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-eco-100">
                        <h2 class="text-xl font-semibold text-eco-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-eco-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                                <path d="M19 7v4h-4"></path>
                            </svg>
                            Ваш экологический вклад
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-eco-50 p-4 rounded-lg">
                                <div class="text-eco-600 text-sm font-medium mb-1">Углеродный след</div>
                                <div class="text-2xl font-bold text-eco-800">
                                    {{ number_format($ecoImpact['carbon_saved'] ?? 0, 1) }} кг
                                </div>
                                <div class="text-xs text-eco-500 mt-1">CO2 сэкономлено</div>
                            </div>
                            
                            <div class="bg-eco-50 p-4 rounded-lg">
                                <div class="text-eco-600 text-sm font-medium mb-1">Пластик</div>
                                <div class="text-2xl font-bold text-eco-800">
                                    {{ number_format($ecoImpact['plastic_saved'] ?? 0, 1) }} кг
                                </div>
                                <div class="text-xs text-eco-500 mt-1">пластика не использовано</div>
                            </div>
                            
                            <div class="bg-eco-50 p-4 rounded-lg">
                                <div class="text-eco-600 text-sm font-medium mb-1">Вода</div>
                                <div class="text-2xl font-bold text-eco-800">
                                    {{ number_format($ecoImpact['water_saved'] ?? 0, 1) }} л
                                </div>
                                <div class="text-xs text-eco-500 mt-1">воды сэкономлено</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Orders -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-eco-100">
                        <h2 class="text-xl font-semibold text-eco-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-eco-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                            Последние заказы
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        @if($recentOrders->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-eco-100">
                                    <thead>
                                        <tr class="text-left text-sm text-eco-500">
                                            <th class="px-4 py-3 font-medium">№ заказа</th>
                                            <th class="px-4 py-3 font-medium">Дата</th>
                                            <th class="px-4 py-3 font-medium">Сумма</th>
                                            <th class="px-4 py-3 font-medium">Статус</th>
                                            <th class="px-4 py-3 font-medium"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-eco-100">
                                        @foreach($recentOrders as $order)
                                            <tr class="hover:bg-eco-50 transition-colors">
                                                <td class="px-4 py-4 text-eco-800 font-medium">
                                                    #{{ $order->id }}
                                                </td>
                                                <td class="px-4 py-4 text-eco-600">
                                                    {{ $order->created_at->format('d.m.Y') }}
                                                </td>
                                                <td class="px-4 py-4 text-eco-800 font-medium">
                                                    {{ number_format($order->total_amount, 0, ',', ' ') }} ₽
                                                </td>
                                                <td class="px-4 py-4">
                                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $order->status_color }}">
                                                        {{ $order->status_text }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-4 text-right">
                                                    <a href="{{ route('account.orders') }}" class="text-eco-600 hover:text-eco-800 text-sm font-medium">
                                                        Подробнее
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-6 text-center">
                                <a href="{{ route('account.orders') }}" class="inline-flex items-center text-eco-600 hover:text-eco-800 font-medium transition-colors">
                                    Смотреть все заказы
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-eco-50 rounded-full text-eco-500 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                        <line x1="3" y1="6" x2="21" y2="6"></line>
                                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-eco-800 mb-2">У вас пока нет заказов</h3>
                                <p class="text-eco-600 mb-6 max-w-md mx-auto">
                                    Время сделать первый заказ! Выбирайте из нашего ассортимента экологичных товаров
                                </p>
                                <a href="{{ route('shop') }}" class="inline-flex items-center px-4 py-2 bg-eco-600 hover:bg-eco-700 text-white font-medium rounded-lg transition-colors">
                                    Перейти в магазин
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection