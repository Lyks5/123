@extends('admin.layouts.app')

@section('title', 'Аналитика')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">Аналитика</h1>
    
    <!-- Сводка -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 uppercase">Выручка</p>
                    <p class="text-2xl font-bold dark:text-white">{{ number_format($salesData['total_revenue'] ?? 0, 0, ',', ' ') }} ₽</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">За все время</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 uppercase">Заказы</p>
                    <p class="text-2xl font-bold dark:text-white">{{ $salesData['order_count'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">За все время</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 uppercase">Пользователи</p>
                    <p class="text-2xl font-bold dark:text-white">{{ $userData['total_users'] }}</p>
                    <p class="text-xs text-green-500">+{{ $userData['new_users_30d'] }} за 30 дней</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 uppercase">Конверсия</p>
                    <p class="text-2xl font-bold dark:text-white">{{ number_format($salesData['conversion_rates']['current'] ?? 0, 1) }}%</p>
            <p class="text-xs text-green-500">+{{ number_format($salesData['conversion_rates']['change'] ?? 0, 1) }}% к пред. периоду</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Графики продаж -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 lg:col-span-2">
            <h2 class="text-xl font-semibold mb-4 dark:text-white">Динамика продаж</h2>
            <div id="revenue-chart" class="h-80"></div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4 dark:text-white">Продажи по категориям</h2>
            <div id="category-chart" class="h-80"></div>
        </div>
    </div>
    
    <!-- Экологическая аналитика -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4 dark:text-white">Экологический эффект</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Снижение CO₂</p>
                    <p class="text-2xl font-bold text-eco-700 dark:text-eco-400">{{ number_format($environmentalData['impact']['co2_reduced'], 0, ',', ' ') }} кг</p>
                </div>
                <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Сэкономлено воды</p>
                    <p class="text-2xl font-bold text-eco-700 dark:text-eco-400">{{ number_format($environmentalData['impact']['water_saved'], 0, ',', ' ') }} л</p>
                </div>
                <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Снижение пластика</p>
                    <p class="text-2xl font-bold text-eco-700 dark:text-eco-400">{{ number_format($environmentalData['impact']['plastic_reduced'], 0, ',', ' ') }} кг</p>
                </div>
            </div>
            <div id="eco-sales-chart" class="h-52"></div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4 dark:text-white">Топ товаров</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Товар</th>
                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">SKU</th>
                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Продажи (шт)</th>
                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Выручка</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productData['top_products'] as $product)
                            <tr>
                                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">{{ $product->name }}</td>
                                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">{{ $product->sku }}</td>
                                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">{{ $product->total_quantity }}</td>
                                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">{{ number_format($product->total_revenue, 0, ',', ' ') }} ₽</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Пользователи и отзывы -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4 dark:text-white">Топ клиентов</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Клиент</th>
                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Email</th>
                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Заказов</th>
                            <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userData['top_customers'] as $customer)
                            <tr>
                                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">{{ $customer->name }}</td>
                                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">{{ $customer->email }}</td>
                                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">{{ $customer->order_count }}</td>
                                <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">{{ number_format($customer->total_spent, 0, ',', ' ') }} ₽</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4 dark:text-white">Отзывы и рейтинги</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Всего отзывов</p>
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-300">{{ $productData['review_stats']['total'] }}</p>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Средняя оценка</p>
                    <p class="text-2xl font-bold text-gray-700 dark:text-gray-300">{{ number_format($productData['review_stats']['average'], 1) }}</p>
                    <div class="flex justify-center items-center space-x-1 mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($productData['review_stats']['average']))
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4 text-gray-300 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
            <div id="reviews-chart" class="h-52"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Настройка графика выручки
        // Настройка графика выручки
var revenueOptions = {
    series: [{
        name: 'Выручка',
        data: @json($salesData['monthly_revenue']['revenues'] ?? [])
    }],
    chart: {
        height: 300,
        type: 'area',
        toolbar: {
            show: false
        },
        fontFamily: 'Inter, sans-serif',
        foreColor: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
    },
    colors: ['#10b981'],
    fill: {
        type: 'gradient',
        gradient: {
            shade: 'light',
            type: 'vertical',
            shadeIntensity: 0.3,
            opacityFrom: 0.7,
            opacityTo: 0.2,
            stops: [0, 100]
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth',
        width: 3
    },
    xaxis: {
        categories: @json($salesData['monthly_revenue']['months'] ?? []),
        labels: {
            style: {
                colors: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
            }
        }
    },
    yaxis: {
        labels: {
            formatter: function(value) {
                return new Intl.NumberFormat('ru-RU').format(value) + ' ₽';
            },
            style: {
                colors: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
            }
        }
    },
    tooltip: {
        theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
        y: {
            formatter: function(value) {
                return new Intl.NumberFormat('ru-RU').format(value) + ' ₽';
            }
        }
    },
    grid: {
        borderColor: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb',
        strokeDashArray: 4
    }
};
        
        var revenueChart = new ApexCharts(document.querySelector("#revenue-chart"), revenueOptions);
        revenueChart.render();

        // Настройка графика категорий
        // Настройка графика категорий
var categoryOptions = {
    series: [{
        name: 'Выручка',
        data: @json(array_column($salesData['category_revenue'] ?? [], 'total_revenue'))
    }],
    chart: {
        type: 'bar',
        height: 300,
        toolbar: {
            show: false
        },
        fontFamily: 'Inter, sans-serif',
        foreColor: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
    },
    plotOptions: {
        bar: {
            horizontal: true,
            borderRadius: 4,
            distributed: true,
            dataLabels: {
                position: 'top'
            }
        }
    },
    colors: ['#3b82f6', '#4ade80', '#eab308', '#ec4899', '#8b5cf6'],
    dataLabels: {
        enabled: true,
        formatter: function(val) {
            return new Intl.NumberFormat('ru-RU').format(val) + ' ₽';
        },
        offsetX: 30,
        style: {
            fontSize: '12px',
            colors: [document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151']
        }
    },
    xaxis: {
        categories: @json(array_column($salesData['category_revenue'] ?? [], 'name')),
        labels: {
            style: {
                colors: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
            }
        }
    },
    yaxis: {
        labels: {
            style: {
                colors: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
            }
        }
    },
    tooltip: {
        theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
        y: {
            formatter: function(value) {
                return new Intl.NumberFormat('ru-RU').format(value) + ' ₽';
            }
        }
    },
    grid: {
        borderColor: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb',
        strokeDashArray: 4
    }
};
        
        var categoryChart = new ApexCharts(document.querySelector("#category-chart"), categoryOptions);
        categoryChart.render();

        // Настройка графика эко-продаж
        var ecoSalesOptions = {
            series: [@json($environmentalData['eco_percentage']), @json(100 - $environmentalData['eco_percentage'])],
            chart: {
                height: 210,
                type: 'donut',
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#10b981', '#9ca3af'],
            labels: ['Эко-товары', 'Обычные товары'],
            legend: {
                position: 'bottom',
                fontSize: '14px',
                labels: {
                    colors: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151'
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return Math.round(val) + '%';
                }
            },
            tooltip: {
                theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                y: {
                    formatter: function(value) {
                        return Math.round(value) + '%';
                    }
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };
        
        var ecoSalesChart = new ApexCharts(document.querySelector("#eco-sales-chart"), ecoSalesOptions);
        ecoSalesChart.render();

        // Настройка графика отзывов
        var reviewsOptions = {
            series: [@json($productData['review_stats']['distribution'][5]), 
                     @json($productData['review_stats']['distribution'][4]), 
                     @json($productData['review_stats']['distribution'][3]), 
                     @json($productData['review_stats']['distribution'][2]), 
                     @json($productData['review_stats']['distribution'][1])],
            chart: {
                height: 210,
                type: 'bar',
                toolbar: {
                    show: false
                },
                fontFamily: 'Inter, sans-serif',
                foreColor: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
            },
            colors: ['#10b981', '#3b82f6', '#eab308', '#f97316', '#ef4444'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    distributed: true,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val;
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: [document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151']
                }
            },
            xaxis: {
                categories: ['5★', '4★', '3★', '2★', '1★'],
                position: 'bottom',
                labels: {
                    style: {
                        colors: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                    }
                }
            },
            grid: {
                borderColor: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb',
                strokeDashArray: 4
            },
            tooltip: {
                theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
            }
        };
        
        var reviewsChart = new ApexCharts(document.querySelector("#reviews-chart"), reviewsOptions);
        reviewsChart.render();
        
        // Обновление графиков при переключении темы
        document.getElementById('theme-toggle')?.addEventListener('click', function() {
            setTimeout(function() {
                revenueChart.updateOptions({
                    grid: {
                        borderColor: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                    },
                    chart: {
                        foreColor: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                    },
                    xaxis: {
                        labels: {
                            style: {
                                colors: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                            }
                        }
                    },
                    tooltip: {
                        theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                    }
                });
                
                categoryChart.updateOptions({
                    grid: {
                        borderColor: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                    },
                    chart: {
                        foreColor: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                    },
                    xaxis: {
                        labels: {
                            style: {
                                colors: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                            }
                        }
                    },
                    dataLabels: {
                        style: {
                            colors: [document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151']
                        }
                    },
                    tooltip: {
                        theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                    }
                });
                
                ecoSalesChart.updateOptions({
                    legend: {
                        labels: {
                            colors: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151'
                        }
                    },
                    tooltip: {
                        theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                    }
                });
                
                reviewsChart.updateOptions({
                    grid: {
                        borderColor: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                    },
                    chart: {
                        foreColor: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                    },
                    xaxis: {
                        labels: {
                            style: {
                                colors: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                            }
                        }
                    },
                    dataLabels: {
                        style: {
                            colors: [document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151']
                        }
                    },
                    tooltip: {
                        theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                    }
                });
            }, 100);
        });
        
        document.getElementById('mobile-theme-toggle')?.addEventListener('click', function() {
            setTimeout(function() {
                revenueChart.updateOptions({
                    grid: {
                        borderColor: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                    },
                    chart: {
                        foreColor: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                    },
                    xaxis: {
                        labels: {
                            style: {
                                colors: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                            }
                        }
                    },
                    tooltip: {
                        theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                    }
                });
                
                categoryChart.updateOptions({
                    grid: {
                        borderColor: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                    },
                    chart: {
                        foreColor: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                    },
                    xaxis: {
                        labels: {
                            style: {
                                colors: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                            }
                        }
                    },
                    dataLabels: {
                        style: {
                            colors: [document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151']
                        }
                    },
                    tooltip: {
                        theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                    }
                });
                
                ecoSalesChart.updateOptions({
                    legend: {
                        labels: {
                            colors: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151'
                        }
                    },
                    tooltip: {
                        theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                    }
                });
                
                reviewsChart.updateOptions({
                    grid: {
                        borderColor: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                    },
                    chart: {
                        foreColor: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                    },
                    xaxis: {
                        labels: {
                            style: {
                                colors: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                            }
                        }
                    },
                    dataLabels: {
                        style: {
                            colors: [document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151']
                        }
                    },
                    tooltip: {
                        theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                    }
                });
            }, 100);
        });
    });
</script>
@endsection