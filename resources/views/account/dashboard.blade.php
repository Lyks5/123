@extends('layouts.app')
@section('title', 'Личный кабинет')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Личный кабинет</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Боковое меню -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b">
                        <div class="flex items-center">
                            <div class="mr-4">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-16 h-16 rounded-full object-cover">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-eco-100 flex items-center justify-center text-eco-700 text-xl font-bold">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                            <h2 class="text-xl font-semibold">{{ Auth::user()->name }}</h2>
                                <p class="text-gray-500">{{ Auth::user()->email }}</p>
                                @if(Auth::user()->is_admin)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-eco-100 text-eco-800 mt-1">
                                        Администратор
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <nav class="p-4">
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('account') }}" class="block px-4 py-2 rounded transition-colors {{ request()->routeIs('account') ? 'bg-eco-100 text-eco-700' : 'hover:bg-gray-50' }}">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        <span>Обзор</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('account.orders') }}" class="block px-4 py-2 rounded transition-colors {{ request()->routeIs('account.orders') ? 'bg-eco-100 text-eco-700' : 'hover:bg-gray-50' }}">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        <span>Мои заказы</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('account.profile') }}" class="block px-4 py-2 rounded transition-colors {{ request()->routeIs('account.profile') ? 'bg-eco-100 text-eco-700' : 'hover:bg-gray-50' }}">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span>Профиль</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('account.addresses') }}" class="block px-4 py-2 rounded transition-colors {{ request()->routeIs('account.addresses') ? 'bg-eco-100 text-eco-700' : 'hover:bg-gray-50' }}">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Адреса</span>
                                    </div>
                                </a>
                            </li>
                            
                            @if(Auth::user()->is_admin)
                            <li class="pt-2 mt-2 border-t">
                                <a href="{{ route('admin.dashboard') }}" class="block py-2 px-4 rounded bg-eco-700 text-white hover:bg-eco-800">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Админ-панель</span>
                                    </div>
                                </a>
                            </li>
                            @endif
                            
                            <li class="pt-2 border-t mt-2">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 rounded text-red-600 hover:bg-red-50 transition-colors">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            <span>Выйти</span>
                                        </div>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            
            <!-- Основной контент -->
            <div class="md:col-span-2">
            <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Добро пожаловать, {{ Auth::user()->name }}!</h2>
                    
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div class="border rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-eco-100 text-eco-600 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-gray-500">Заказы</p>
                                    <p class="text-2xl font-bold">{{ Auth::user()->orders->count() }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border rounded-lg p-4">
                        <div class="flex items-center">
                                <div class="p-3 rounded-full bg-eco-100 text-eco-600 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-gray-500">Избранное</p>
                                    <p class="text-2xl font-bold">{{ Auth::user()->wishlists->sum(function($wishlist) { return $wishlist->items->count(); }) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <h3 class="font-medium text-lg">Последние заказы</h3>
                        
                        @if(Auth::user()->orders->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">№ заказа</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Сумма</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach(Auth::user()->orders()->latest()->take(3)->get() as $order)
                                            <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-eco-700">
                                            <a href="{{ route('account.orders') }}" class="hover:underline">#{{ $order->id }}</a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $order->created_at->format('d.m.Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ number_format($order->total, 0, ',', ' ') }} ₽
                                                </td>
                                                <td class="px-4 py-3">
                                                    @php
                                                        $statusClass = [
                                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                                            'processing' => 'bg-blue-100 text-blue-800',
                                                            'shipped' => 'bg-purple-100 text-purple-800',
                                                            'delivered' => 'bg-green-100 text-green-800',
                                                            'completed' => 'bg-green-100 text-green-800',
                                                            'cancelled' => 'bg-red-100 text-red-800',
                                                        ][$order->status] ?? 'bg-gray-100 text-gray-800';
                                                        
                                                        $statusText = [
                                                            'pending' => 'Ожидает',
                                                            'processing' => 'Обрабатывается',
                                                            'shipped' => 'Отправлен',
                                                            'delivered' => 'Доставлен',
                                                            'completed' => 'Завершен',
                                                            'cancelled' => 'Отменен',
                                                        ][$order->status] ?? $order->status;
                                                    @endphp
                                                    <span class="px-2 py-1 text-xs rounded-full {{ $statusClass }}">
                                                        {{ $statusText }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('account.orders') }}" class="text-eco-700 hover:underline inline-flex items-center">
                                    Все заказы
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <p>У вас пока нет заказов.</p>
                                <a href="{{ route('shop') }}" class="mt-2 inline-flex items-center text-eco-700 hover:underline">
                                    Перейти в магазин
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
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