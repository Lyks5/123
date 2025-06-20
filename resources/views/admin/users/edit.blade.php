@extends('admin.layouts.app')

@section('title', 'Редактирование пользователя')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-eco-600 hover:text-eco-700 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Назад к списку пользователей
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Основная информация -->
        <div class="bg-white shadow rounded-lg p-6 md:col-span-2">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Редактирование пользователя</h1>
                <p class="text-gray-600 mt-1">{{ $user->name }}</p>
            </div>

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Имя</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Телефон</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                </div>
                
                <div class="space-y-4 mb-6">
                    <div>
                        <div class="flex items-center">
                            <input id="is_admin" name="is_admin" type="checkbox" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 text-eco-600 focus:ring-eco-500">
                            <label for="is_admin" class="ml-2 block text-sm text-gray-700">Администратор</label>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Администраторы имеют полный доступ к панели управления.</p>
                    </div>

                    
                </div>
                
                <div class="flex justify-end">
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                        Отмена
                    </a>
                    <button type="submit" class="bg-eco-600 hover:bg-eco-700 text-white font-bold py-2 px-4 rounded">
                        Сохранить изменения
                    </button>
                </div>
            </form>
        </div>

        <!-- Боковая информация -->
        <div class="space-y-6">
            <!-- Статистика пользователя -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Статистика</h2>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Дата регистрации</p>
                        <p class="font-medium">{{ $user->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Статус email</p>
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Подтвержден
                                <span class="ml-1 text-xs">{{ $user->email_verified_at->format('d.m.Y') }}</span>
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Не подтвержден
                            </span>
                        @endif
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Кол-во заказов</p>
                        <p class="font-medium">{{ $user->orders->count() }}</p>
                    </div>
                    
                    @if($user->eco_impact_score)
                        <div>
                            <p class="text-sm text-gray-500">Эко-рейтинг</p>
                            <p class="font-medium">{{ $user->eco_impact_score }} баллов</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Последние действия -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Последние заказы</h2>
                
                @if($user->orders->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($user->orders->sortByDesc('created_at')->take(5) as $order)
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-0">
                                <div>
                                    <div class="font-medium">Заказ #{{ $order->id }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->created_at->format('d.m.Y') }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-medium">{{ number_format($order->total_amount, 0, '.', ' ') }} ₽</div>
                                    <div>
                                        <span class="px-2 py-1 rounded-full text-xs
                                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                            @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                                            @elseif($order->status == 'delivered') bg-indigo-100 text-indigo-800
                                            @elseif($order->status == 'completed') bg-green-100 text-green-800
                                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($order->status == 'pending') Ожидает
                                            @elseif($order->status == 'processing') В обработке
                                            @elseif($order->status == 'shipped') Отправлен
                                            @elseif($order->status == 'delivered') Доставлен
                                            @elseif($order->status == 'completed') Завершен
                                            @elseif($order->status == 'cancelled') Отменен
                                            @else {{ $order->status }} @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($user->orders->count() > 5)
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.orders.index', ['user' => $user->id]) }}" class="text-eco-600 hover:text-eco-700 text-sm font-medium">
                                Посмотреть все заказы
                            </a>
                        </div>
                    @endif
                @else
                    <p class="text-gray-500 text-sm">У пользователя нет заказов.</p>
                @endif
            </div>
        </div>
    </div>
@endsection