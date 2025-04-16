@extends('layouts.app')

@section('title', 'Мои заказы - ЭкоМаркет')

@section('content')
<!-- Header spacing -->
<div class="h-20"></div>

<div class="bg-gradient-to-b from-eco-50 to-white py-12 min-h-screen">
    <div class="container mx-auto px-4">
        <!-- Page header -->
        <div class="mb-10 max-w-3xl mx-auto text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-eco-900 mb-3">Мои заказы</h1>
            <p class="text-eco-600 text-lg max-w-2xl mx-auto">История ваших покупок и статус текущих заказов</p>
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
                            <a href="{{ route('account') }}" class="block px-4 py-2.5 rounded-lg text-eco-700 hover:bg-eco-50 transition-colors">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </svg>
                                    <span class="font-medium">Обзор</span>
                                </div>
                            </a>
                            
                            <a href="{{ route('account.orders') }}" class="block px-4 py-2.5 rounded-lg bg-eco-100 text-eco-900 transition-colors">
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-eco-100">
                        <h2 class="text-xl font-semibold text-eco-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-eco-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                            История заказов
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        @if($orders->count() > 0)
                            <div class="space-y-6">
                                @foreach($orders as $order)
                                    <div class="border border-eco-100 rounded-lg overflow-hidden bg-white">
                                        <div class="bg-eco-50/50 p-4 flex flex-col md:flex-row justify-between gap-4">
                                            <div>
                                                <div class="flex items-center">
                                                    <h3 class="font-medium text-lg text-eco-900">Заказ #{{ $order->id }}</h3>
                                                    <span class="ml-3 px-2.5 py-1 rounded-full text-xs font-medium {{ $order->status_color }}">
                                                        {{ $order->status_text }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-eco-600 mt-1">от {{ $order->created_at->format('d.m.Y') }}</p>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-lg font-medium text-eco-900">{{ number_format($order->total_amount, 0, '.', ' ') }} ₽</div>
                                                @if($order->shipping_method)
                                                    <p class="text-sm text-eco-600">{{ $order->shipping_method }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="p-4 border-t border-eco-100">
                                            <div class="space-y-3">
                                                @foreach($order->items->take(3) as $item)
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center">
                                                            <div class="w-12 h-12 bg-eco-50 rounded-lg flex items-center justify-center overflow-hidden mr-3">
                                                                @if($item->product && $item->product->images && $item->product->images->isNotEmpty())
                                                                    <img src="{{ asset('storage/' . $item->product->images->where('is_primary', true)->first()->image_path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                                                @else
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-eco-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                                        <polyline points="21 15 16 10 5 21"></polyline>
                                                                    </svg>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <p class="text-eco-900 font-medium">{{ $item->product ? $item->product->name : 'Товар недоступен' }}</p>
                                                                <p class="text-sm text-eco-600">{{ $item->quantity }} шт. × {{ number_format($item->price, 0, '.', ' ') }} ₽</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                
                                                @if($order->items->count() > 3)
                                                    <div class="text-sm text-eco-600">
                                                        ... и еще {{ $order->items->count() - 3 }} товара(ов)
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="mt-4 pt-4 border-t border-eco-100 flex flex-col md:flex-row justify-between gap-3">
                                                @if($order->tracking_number)
                                                    <div>
                                                        <span class="text-sm text-eco-600">Номер отслеживания:</span>
                                                        <span class="text-sm font-medium text-eco-900 ml-1">{{ $order->tracking_number }}</span>
                                                    </div>
                                                @endif
                                                
                                                <div class="flex gap-2 md:ml-auto">
                                                    <a href="{{ route('account.orders.show', $order->id) }}" class="px-4 py-2 bg-eco-50 hover:bg-eco-100 text-eco-900 rounded-lg text-sm font-medium transition-colors">
                                                        Детали заказа
                                                    </a>
                                                    @if($order->status == 'delivered' || $order->status == 'completed')
                                                        <a href="#" class="px-4 py-2 bg-eco-600 hover:bg-eco-700 text-white rounded-lg text-sm font-medium transition-colors">
                                                            Повторить заказ
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-6">
                                {{ $orders->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="mx-auto w-16 h-16 rounded-full bg-eco-50 flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-eco-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                        <line x1="3" y1="6" x2="21" y2="6"></line>
                                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-eco-900 mb-2">У вас еще нет заказов</h3>
                                <p class="text-eco-600 mb-6 max-w-md mx-auto">Самое время сделать первый заказ в нашем магазине экологичных товаров</p>
                                <a href="{{ route('shop') }}" class="inline-block bg-eco-600 hover:bg-eco-700 text-white py-2 px-4 rounded-lg transition-colors">
                                    Перейти в магазин
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