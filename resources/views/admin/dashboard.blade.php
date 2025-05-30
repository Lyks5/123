
@extends('admin.layouts.app')

@section('title', 'Дашборд')

@section('content')
    <h1 class="text-3xl font-bold mb-8">Дашборд</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700/20 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-eco-100 dark:bg-eco-900/50 text-eco-700 dark:text-eco-400 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 uppercase">Товары</p>
                    <p class="text-2xl font-bold dark:text-white">{{ $stats['products'] }}</p>
                    <a href="{{ route('admin.products.index') }}" class="text-xs text-eco-700 dark:text-eco-400 hover:underline">Управление →</a>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700/20 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-400 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 uppercase">Заказы</p>
                    <p class="text-2xl font-bold dark:text-white">{{ $stats['orders'] }}</p>
                    <a href="{{ route('admin.orders.index') }}" class="text-xs text-eco-700 dark:text-eco-400 hover:underline">Управление →</a>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700/20 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-400 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 uppercase">Пользователи</p>
                    <p class="text-2xl font-bold dark:text-white">{{ $stats['users'] }}</p>
                    <a href="{{ route('admin.users.index') }}" class="text-xs text-eco-700 dark:text-eco-400 hover:underline">Управление →</a>
                </div>
            </div>
        </div>
        
    </div>

    

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700/20 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-400 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 uppercase">Эко-характеристики</p>
                    @isset($ecoFeaturesCount)
                    <p class="text-2xl font-bold dark:text-white">{{ $ecoFeaturesCount }}</p>
                    @else
                    <p class="text-red-500">Данные недоступны</p>
                    @endisset
                    <a href="{{ route('admin.eco-features.index') }}" class="text-xs text-eco-700 dark:text-eco-400 hover:underline">Управление →</a>
                </div>
            </div>
        </div>

        

        
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700/20 p-6">
            <h2 class="text-xl font-bold mb-4 dark:text-white">Последние заказы</h2>
            
            @if($recentOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Пользователь</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Сумма</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Статус</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Дата</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-eco-700 dark:text-eco-400 hover:underline">#{{ $order->id }}</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ number_format($order->total, 0, ',', ' ') }} ₽
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClass = [
                                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300',
                                                'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300',
                                                'shipped' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300',
                                                'delivered' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                                                'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                                                'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
                                            ][$order->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                            
                                            $statusText = [
                                                'pending' => 'Ожидает',
                                                'processing' => 'Обрабатывается',
                                                'shipped' => 'Отправлен',
                                                'delivered' => 'Доставлен',
                                                'completed' => 'Завершен',
                                                'cancelled' => 'Отменен',
                                            ][$order->status] ?? $order->status;
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->created_at->format('d.m.Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.orders.index') }}" class="text-sm text-eco-700 dark:text-eco-400 hover:underline">Все заказы →</a>
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400">Нет недавних заказов.</p>
            @endif
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700/20 p-6">
            <h2 class="text-xl font-bold mb-4 dark:text-white">Последние товары</h2>
            
            @if($latestProducts->count() > 0)
                <div class="space-y-4">
                    @foreach($latestProducts as $product)
                        <div class="flex items-center">
                            <div class="w-16 h-16 flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded overflow-hidden">
                                @if($product->images->where('is_primary', true)->first())
                                    <img src="{{ asset('storage/' . $product->images->where('is_primary', true)->first()->image_path) }}" 
                                         alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 flex-1">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-sm font-medium text-eco-700 dark:text-eco-400 hover:underline">
                                    {{ $product->name }}
                                </a>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ number_format($product->price, 0, ',', ' ') }} ₽
                                    @if($product->sale_price)
                                        <span class="line-through ml-2">{{ number_format($product->sale_price, 0, ',', ' ') }} ₽</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">Добавлен: {{ $product->created_at->format('d.m.Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.products.index') }}" class="text-sm text-eco-700 dark:text-eco-400 hover:underline">Все товары →</a>
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400">Нет товаров.</p>
            @endif
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-700/20 p-6">
            <h2 class="text-xl font-bold mb-4 dark:text-white">Быстрый доступ</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.products.create') }}" class="block p-4 bg-eco-50 dark:bg-eco-900/10 hover:bg-eco-100 dark:hover:bg-eco-900/20 rounded-lg transition">
                    <div class="flex items-center">
                        <span class="p-2 rounded-full bg-eco-700 dark:bg-eco-600 text-white mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span class="dark:text-white">Добавить товар</span>
                    </div>
                </a>
                <a href="{{ route('admin.categories.create') }}" class="block p-4 bg-eco-50 dark:bg-eco-900/10 hover:bg-eco-100 dark:hover:bg-eco-900/20 rounded-lg transition">
                    <div class="flex items-center">
                        <span class="p-2 rounded-full bg-eco-700 dark:bg-eco-600 text-white mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span class="dark:text-white">Добавить категорию</span>
                    </div>
                </a>
                <a href="{{ route('admin.eco-features.create') }}" class="block p-4 bg-eco-50 dark:bg-eco-900/10 hover:bg-eco-100 dark:hover:bg-eco-900/20 rounded-lg transition">
                    <div class="flex items-center">
                        <span class="p-2 rounded-full bg-eco-700 dark:bg-eco-600 text-white mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span class="dark:text-white">Добавить эко-характеристику</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection