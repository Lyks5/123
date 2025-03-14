@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Аналитика</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Панель управления</a></li>
        <li class="breadcrumb-item active">Аналитика</li>
    </ol>
    
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h2>{{ number_format($totalSales, 0, '.', ' ') }} ₽</h2>
                    <p class="mb-0">Общая выручка</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h2>{{ $totalOrders }}</h2>
                    <p class="mb-0">Всего заказов</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <h2>{{ number_format($averageOrderValue, 0, '.', ' ') }} ₽</h2>
                    <p class="mb-0">Средний чек</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <h2>{{ $totalCustomers }}</h2>
                    <p class="mb-0">Клиентов</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-graph-up me-1"></i>
                    Выручка по месяцам
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-pie-chart me-1"></i>
                    Заказы по статусу
                </div>
                <div class="card-body">
                    <canvas id="orderStatusChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-star me-1"></i>
                    Популярные товары
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Товар</th>
                                <th>Продано</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->sales_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">Нет данных</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-folder me-1"></i>
                    Популярные категории
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Категория</th>
                                <th>Продано товаров</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topCategories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->sales_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">Нет данных</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Данные для графиков
    const months = @json($monthNames);
    const revenueData = @json($revenueByMonth);
    const orderStatusLabels = ['Ожидает', 'В обработке', 'Отправлен', 'Доставлен', 'Выполнен', 'Отменен'];
    const orderStatusData = [
        {{ $ordersByStatus['pending'] }},
        {{ $ordersByStatus['processing'] }},
        {{ $ordersByStatus['shipped'] }},
        {{ $ordersByStatus['delivered'] }},
        {{ $ordersByStatus['completed'] }},
        {{ $ordersByStatus['cancelled'] }}
    ];
    const orderStatusColors = [
        'rgba(255, 193, 7, 0.8)',
        'rgba(13, 202, 240, 0.8)',
        'rgba(13, 110, 253, 0.8)',
        'rgba(25, 135, 84, 0.8)',
        'rgba(25, 135, 84, 0.8)',
        'rgba(220, 53, 69, 0.8)'
    ];
    
    // График выручки
    const revenueCtx = document.getElementById('revenueChart');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Выручка (₽)',
                data: revenueData,
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('ru-RU') + ' ₽';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y.toLocaleString('ru-RU') + ' ₽';
                        }
                    }
                }
            }
        }
    });
    
    // График заказов по статусам
    const statusCtx = document.getElementById('orderStatusChart');
    new Chart(statusCtx, {
        type: 'pie',
        data: {
            labels: orderStatusLabels,
            datasets: [{
                data: orderStatusData,
                backgroundColor: orderStatusColors,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
@endpush
@endsection