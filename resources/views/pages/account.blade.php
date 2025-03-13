@extends('layouts.app')

@section('title', 'Личный кабинет - EcoSport')

@section('content')
    <div class="min-h-screen bg-background">
        @include('components.navbar')
        
        <div class="container mx-auto px-4 py-8 pt-24">
            <h1 class="text-3xl font-bold text-eco-900 mb-8">Личный кабинет</h1>
            
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 bg-eco-100 rounded-full flex items-center justify-center mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="text-eco-700" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            </div>
                            <div>
                                <div class="font-semibold text-eco-900">{{ $user->name }}</div>
                                <div class="text-sm text-eco-600">{{ $user->email }}</div>
                            </div>
                        </div>
                        
                        <nav class="space-y-1">
                            <a href="#profile" class="flex items-center px-3 py-2 text-eco-900 rounded-md bg-eco-50 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 text-eco-700" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                Профиль
                            </a>
                            <a href="#orders" class="flex items-center px-3 py-2 text-eco-700 hover:bg-eco-50 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><path d="M3 6h18"></path><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                                Заказы
                            </a>
                            <a href="#wishlist" class="flex items-center px-3 py-2 text-eco-700 hover:bg-eco-50 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                Избранное
                            </a>
                            <a href="#address" class="flex items-center px-3 py-2 text-eco-700 hover:bg-eco-50 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                Адреса
                            </a>
                            <div class="pt-4 mt-4 border-t border-eco-100">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center px-3 py-2 text-eco-700 hover:bg-eco-50 rounded-md w-full text-left">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-3" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" x2="9" y1="12" y2="12"></line></svg>
                                        Выйти
                                    </button>
                                </form>
                            </div>
                        </nav>
                    </div>
                </div>
                
                <!-- Main content -->
                <div class="lg:col-span-3">
                    <!-- Профиль -->
                    <div id="profile" class="bg-white rounded-xl shadow-sm mb-8">
                        <div class="p-6 border-b border-eco-100">
                            <h2 class="text-xl font-semibold text-eco-900">Информация профиля</h2>
                            <p class="text-eco-600 text-sm mt-1">
                                Обновите свою личную информацию
                            </p>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('account.update') }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-eco-700 mb-2">
                                            Имя
                                        </label>
                                        <input 
                                            type="text" 
                                            id="name" 
                                            name="name" 
                                            value="{{ $user->name }}" 
                                            class="w-full rounded-lg border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                        />
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-eco-700 mb-2">
                                            Email
                                        </label>
                                        <input 
                                            type="email" 
                                            id="email" 
                                            name="email" 
                                            value="{{ $user->email }}" 
                                            class="w-full rounded-lg border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                        />
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-eco-600 hover:bg-eco-700 text-white px-6 py-2 rounded-lg">
                                        Сохранить изменения
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Заказы -->
                    <div id="orders" class="bg-white rounded-xl shadow-sm mb-8">
                        <div class="p-6 border-b border-eco-100">
                            <h2 class="text-xl font-semibold text-eco-900">История заказов</h2>
                            <p class="text-eco-600 text-sm mt-1">
                                Просмотрите историю ваших заказов и их статусы
                            </p>
                        </div>
                        <div class="p-6">
                            @if(count($orders) > 0)
                                <table class="w-full">
                                    <thead>
                                        <tr class="text-left border-b border-eco-100">
                                            <th class="pb-3 font-medium text-eco-700">Номер заказа</th>
                                            <th class="pb-3 font-medium text-eco-700">Дата</th>
                                            <th class="pb-3 font-medium text-eco-700">Товары</th>
                                            <th class="pb-3 font-medium text-eco-700">Сумма</th>
                                            <th class="pb-3 font-medium text-eco-700">Статус</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                            <tr class="border-b border-eco-100">
                                                <td class="py-4">{{ $order['id'] }}</td>
                                                <td class="py-4">{{ $order['date'] }}</td>
                                                <td class="py-4">{{ $order['items'] }} шт.</td>
                                                <td class="py-4">₽{{ number_format($order['total'], 0, ',', ' ') }}</td>
                                                <td class="py-4">
                                                    <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                                        {{ $order['status'] }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center py-16">
                                    <p class="text-eco-600">У вас пока нет заказов</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection