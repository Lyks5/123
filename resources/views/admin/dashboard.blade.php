@extends('admin.layouts.app')

@section('title', 'Панель управления')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/apexcharts@3.41.0/dist/apexcharts.min.css" rel="stylesheet">
@endpush

@section('content')


<div class="space-y-6">
    <!-- Заголовок и приветствие -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Панель управления
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Добро пожаловать, {{ auth()->user()->name }}
            </p>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.products.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-eco-600 hover:bg-eco-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Добавить товар
            </a>
        </div>
    </div>

    <!-- Основные показатели -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Статистика заказов -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Заказы</h2>
                        <div class="flex items-baseline">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['orders'] }}</p>
                            <p class="ml-2 text-sm text-green-600">
                                @if(isset($stats['orders_growth']))
                                    +{{ $stats['orders_growth'] }}%
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-3">
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500">
                    Все заказы →
                </a>
            </div>
        </div>

        <!-- Статистика продаж -->
        <!-- <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/50 text-green-600">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Продажи</h2>
                        <div class="flex items-baseline">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ isset($totalSales) ? number_format($totalSales, 0, '.', ' ') : '0' }} ₽
                            </p>
                            <p class="ml-2 text-sm text-green-600">
                                @if(isset($stats['revenue_growth']))
                                    +{{ $stats['revenue_growth'] }}%
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                        <span>Средний чек</span>
                        <span>{{ isset($stats['average_order']) ? number_format($stats['average_order'], 0, '.', ' ') : '0' }} ₽</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                        <span>Продажи сегодня</span>
                        <span>{{ isset($stats['today_revenue']) ? number_format($stats['today_revenue'], 0, '.', ' ') : '0' }} ₽</span>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-3">
                <a href="{{ route('admin.analytics.index') }}" class="text-sm font-medium text-green-600 dark:text-green-400 hover:text-green-500">
                    Подробная статистика →
                </a>
            </div>
        </div> -->

        <!-- Товары -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-600">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Товары</h2>
                        <div class="flex items-baseline">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['products'] }}</p>
                            <p class="ml-2 text-sm text-gray-600 dark:text-gray-400">активных</p>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-3">
                <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-purple-600 dark:text-purple-400 hover:text-purple-500">
                    Все товары →
                </a>
            </div>
        </div>

        <!-- Пользователи -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 dark:bg-orange-900/50 text-orange-600">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Пользователи</h2>
                        <div class="flex items-baseline">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['users'] }}</p>
                            <p class="ml-2 text-sm text-green-600">
                                @if(isset($stats['users_growth']))
                                    +{{ $stats['users_growth'] }}%
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-3">
                <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-orange-600 dark:text-orange-400 hover:text-orange-500">
                    Все пользователи →
                </a>
            </div>
        </div>
    </div>

    <!-- Основная информация -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Популярные товары -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Популярные товары за месяц</h3>
                <a href="{{ route('admin.analytics.index') }}" class="text-sm font-medium text-eco-600 hover:text-eco-500">
                    Подробная статистика →
                </a>
            </div>
            
            <div class="space-y-4">
                @forelse($popularProducts ?? [] as $product)
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 w-16 h-16">
                            @if($product->images->first())
                                <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover rounded-lg">
                            @else
                                <div class="w-full h-full bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ $product->name }}
                            </p>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $product->orders_count }} заказов
                                </span>
                                @if($product->eco_features_count > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.382 3.968A8.962 8.962 0 0110 2.5c2.336 0 4.507.893 6.137 2.393a1 1 0 11-1.374 1.454A6.962 6.962 0 0010 4.5c-1.81 0-3.5.683-4.785 1.847a1 1 0 11-1.833-.379zM10 6.5a6 6 0 016 6c0 1.81-.683 3.5-1.847 4.785a1 1 0 01-1.414-1.414A4 4 0 0010 8.5a4 4 0 00-4 4 4 4 0 001.018 2.66 1 1 0 01-1.532 1.286A6 6 0 0110 6.5z" clip-rule="evenodd"/>
                                        </svg>
                                        Эко
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="text-sm font-medium text-gray-900 dark:text-white text-right">
                            <div>{{ number_format($product->price, 0, '.', ' ') }} ₽</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                @if($product->stock_quantity > 0)
                                    В наличии: {{ $product->stock_quantity }}
                                @else
                                    Нет в наличии
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                        Нет данных о популярных товарах
                    </p>
                @endforelse
            </div>
        </div>

        <!-- Последние заказы -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Последние заказы</h3>
                    <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-eco-600 hover:text-eco-500">
                        Все заказы →
                    </a>
                </div>
                
                @if($recentOrders->count() > 0)
                    <div class="overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Заказ</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Клиент</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Сумма</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Статус</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($recentOrders as $order)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                                       class="text-sm font-medium text-eco-600 hover:text-eco-500">
                                                        #{{ $order->id }}
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $order->created_at->format('d.m.Y H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-200">
                                                    {{ number_format($order->total_amount, 0, '.', ' ') }} ₽
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusClasses = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300',
                                                        'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300',
                                                        'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                                                        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
                                                    ];
                                                    $statusNames = [
                                                        'pending' => 'Ожидает',
                                                        'processing' => 'В обработке',
                                                        'completed' => 'Выполнен',
                                                        'cancelled' => 'Отменён',
                                                    ];
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ $statusNames[$order->status] ?? $order->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                        Нет последних заказов
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Быстрые действия -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Быстрые действия</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.products.create') }}" 
                   class="flex items-center p-4 bg-eco-50 dark:bg-eco-900/10 rounded-lg hover:bg-eco-100 dark:hover:bg-eco-900/20 transition group">
                    <span class="flex-shrink-0 p-2 bg-eco-600 rounded-lg text-white group-hover:bg-eco-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </span>
                    <span class="ml-3 text-sm font-medium text-eco-900 dark:text-eco-200">
                        Добавить товар
                    </span>
                </a>

                <a href="{{ route('admin.categories.create') }}" 
                   class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/10 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/20 transition group">
                    <span class="flex-shrink-0 p-2 bg-purple-600 rounded-lg text-white group-hover:bg-purple-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </span>
                    <span class="ml-3 text-sm font-medium text-purple-900 dark:text-purple-200">
                        Добавить категорию
                    </span>
                </a>

                <a href="{{ route('admin.eco-features.create') }}" 
                   class="flex items-center p-4 bg-green-50 dark:bg-green-900/10 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/20 transition group">
                    <span class="flex-shrink-0 p-2 bg-green-600 rounded-lg text-white group-hover:bg-green-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </span>
                    <span class="ml-3 text-sm font-medium text-green-900 dark:text-green-200">
                        Добавить эко-характеристику
                    </span>
                </a>

                <a href="{{ route('admin.attributes.create') }}" 
                   class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/10 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/20 transition group">
                    <span class="flex-shrink-0 p-2 bg-blue-600 rounded-lg text-white group-hover:bg-blue-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </span>
                    <span class="ml-3 text-sm font-medium text-blue-900 dark:text-blue-200">
                        Добавить атрибут
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.41.0/dist/apexcharts.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        try {
            var dates = @json($stats['monthly_dates'] ?? []);
            var revenue = @json($stats['monthly_revenue'] ?? []);

            console.log('Chart Data:', { dates, revenue });

            // Проверяем наличие данных
            if (!dates.length || !revenue.length) {
                throw new Error('Нет данных для отображения');
            }

            // Проверяем соответствие длины массивов
            if (dates.length !== revenue.length) {
                throw new Error('Несоответствие данных');
            }

            var options = {
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Выручка',
                data: revenue
            }],
            xaxis: {
                categories: dates,
                labels: {
                    style: {
                        colors: '#64748b'
                    },
                    rotateAlways: true,
                    rotate: -45,
                    formatter: function(value) {
                        // Преобразуем формат даты из YYYY-MM в более читаемый вид
                        const [year, month] = value.split('-');
                        const date = new Date(year, month - 1);
                        return date.toLocaleString('ru-RU', { month: 'short', year: 'numeric' });
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Выручка (₽)',
                    style: {
                        color: '#0284c7'
                    }
                },
                labels: {
                    style: {
                        colors: '#64748b'
                    },
                    formatter: function(value) {
                        return new Intl.NumberFormat('ru-RU').format(value) + ' ₽';
                    }
                }
            },
            colors: ['#0284c7'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '60%',
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return new Intl.NumberFormat('ru-RU').format(val) + ' ₽';
                }
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return new Intl.NumberFormat('ru-RU').format(value) + ' ₽';
                    }
                }
            }
        };

            var chartElement = document.querySelector("#monthly-chart");
            if (!chartElement) {
                throw new Error('Элемент графика не найден');
            }

            var chart = new ApexCharts(chartElement, options);
            chart.render().catch(function(err) {
                console.error('Error rendering chart:', err);
                chartElement.innerHTML = '<div class="p-4 text-center"><p class="text-red-500 font-medium">Ошибка при загрузке графика</p><p class="text-gray-500 text-sm mt-1">' + err.message + '</p></div>';
            });
        } catch (error) {
            console.error('Error initializing chart:', error);
            document.querySelector("#monthly-chart").innerHTML = '<div class="p-4 text-center"><p class="text-red-500 font-medium">Ошибка при инициализации графика</p><p class="text-gray-500 text-sm mt-1">' + error.message + '</p></div>';
        }
    });
</script>
@endpush