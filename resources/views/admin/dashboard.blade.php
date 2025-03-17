@extends('admin.layouts.app')

@section('title', 'Дашборд')

@section('content')
    <h1 class="text-3xl font-bold mb-8">Дашборд</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-eco-100 text-eco-700 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 uppercase">Товары</p>
                    <p class="text-2xl font-bold">{{ $productCount }}</p>
                    <a href="{{ route('admin.products.index') }}" class="text-xs text-eco-700 hover:underline">Управление →</a>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-700 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 uppercase">Заказы</p>
                    <p class="text-2xl font-bold">{{ $orderCount }}</p>
                    <a href="{{ route('admin.orders.index') }}" class="text-xs text-eco-700 hover:underline">Управление →</a>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-700 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 uppercase">Пользователи</p>
                    <p class="text-2xl font-bold">{{ $userCount }}</p>
                    <a href="{{ route('admin.users.index') }}" class="text-xs text-eco-700 hover:underline">Управление →</a>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-amber-100 text-amber-700 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 uppercase">Блог</p>
                    <p class="text-2xl font-bold">{{ $postCount }}</p>
                    <a href="{{ route('admin.blog.posts.index') }}" class="text-xs text-eco-700 hover:underline">Управление →</a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-700 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 uppercase">Эко-характеристики</p>
                    <p class="text-2xl font-bold">{{ $ecoFeaturesCount }}</p>
                    <a href="{{ route('admin.eco-features.index') }}" class="text-xs text-eco-700 hover:underline">Управление →</a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-teal-100 text-teal-700 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 uppercase">Эко-инициативы</p>
                    <p class="text-2xl font-bold">{{ $initiativesCount }}</p>
                    <a href="{{ route('admin.initiatives.index') }}" class="text-xs text-eco-700 hover:underline">Управление →</a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-rose-100 text-rose-700 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 uppercase">Обращения</p>
                    <p class="text-2xl font-bold">{{ $contactRequestsCount }}</p>
                    <a href="{{ route('admin.contact-requests.index') }}" class="text-xs text-eco-700 hover:underline">Управление →</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Последние заказы</h2>
            
            @if($recentOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Пользователь</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Сумма</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-eco-700 hover:underline">#{{ $order->id }}</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($order->total, 0, ',', ' ') }} ₽
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->created_at->format('d.m.Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.orders.index') }}" class="text-sm text-eco-700 hover:underline">Все заказы →</a>
                </div>
            @else
                <p class="text-gray-500">Нет недавних заказов.</p>
            @endif
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Последние товары</h2>
            
            @if($latestProducts->count() > 0)
                <div class="space-y-4">
                    @foreach($latestProducts as $product)
                        <div class="flex items-center">
                            <div class="w-16 h-16 flex-shrink-0 bg-gray-100 rounded overflow-hidden">
                                @if($product->images->where('is_primary', true)->first())
                                    <img src="{{ asset('storage/' . $product->images->where('is_primary', true)->first()->image_path) }}" 
                                         alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 flex-1">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-sm font-medium text-eco-700 hover:underline">
                                    {{ $product->name }}
                                </a>
                                <p class="text-sm text-gray-500">
                                    {{ number_format($product->price, 0, ',', ' ') }} ₽
                                    @if($product->sale_price)
                                        <span class="line-through ml-2">{{ number_format($product->sale_price, 0, ',', ' ') }} ₽</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400">Добавлен: {{ $product->created_at->format('d.m.Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.products.index') }}" class="text-sm text-eco-700 hover:underline">Все товары →</a>
                </div>
            @else
                <p class="text-gray-500">Нет товаров.</p>
            @endif
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Быстрый доступ</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.products.create') }}" class="block p-4 bg-eco-50 hover:bg-eco-100 rounded-lg transition">
                    <div class="flex items-center">
                        <span class="p-2 rounded-full bg-eco-700 text-white mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span>Добавить товар</span>
                    </div>
                </a>
                <a href="{{ route('admin.categories.create') }}" class="block p-4 bg-eco-50 hover:bg-eco-100 rounded-lg transition">
                    <div class="flex items-center">
                        <span class="p-2 rounded-full bg-eco-700 text-white mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span>Добавить категорию</span>
                    </div>
                </a>
                <a href="{{ route('admin.eco-features.create') }}" class="block p-4 bg-eco-50 hover:bg-eco-100 rounded-lg transition">
                    <div class="flex items-center">
                        <span class="p-2 rounded-full bg-eco-700 text-white mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span>Добавить эко-характеристику</span>
                    </div>
                </a>
                <a href="{{ route('admin.blog.posts.create') }}" class="block p-4 bg-eco-50 hover:bg-eco-100 rounded-lg transition">
                    <div class="flex items-center">
                        <span class="p-2 rounded-full bg-eco-700 text-white mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span>Написать статью</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection