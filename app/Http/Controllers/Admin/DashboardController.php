<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\EcoFeature;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Получаем данные о продажах из AnalyticsController
        $analyticsController = new AnalyticsController();
        $salesData = $analyticsController->getSalesAnalytics();

        // Основная статистика
        // Логируем данные для отладки
        \Log::info('Sales Analytics Data:', $salesData);
        
        $stats = [
            'products' => Product::count(),
            'orders' => Order::count(),
            'users' => User::where('is_admin', false)->count(),
            'revenue' => $salesData['total_revenue'],
            'revenue_growth' => $salesData['sales_growth'],
        ];

        // Статистика заказов за разные периоды
        $ordersToday = Order::where('created_at', '>=', Carbon::now()->subDay())->count();
        $ordersWeek = Order::where('created_at', '>=', Carbon::now()->subWeek())->count();

        // Добавляем в основную статистику
        $stats['orders_today'] = $ordersToday;
        $stats['orders_week'] = $ordersWeek;

        // Для отладки записываем в лог
        \Log::info('Orders Stats', [
            'today' => $ordersToday,
            'week' => $ordersWeek,
            'query_today' => Order::where('created_at', '>=', Carbon::now()->subDay())->toSql(),
            'query_week' => Order::where('created_at', '>=', Carbon::now()->subWeek())->toSql(),
            'parameters' => [
                'today_start' => Carbon::now()->subDay()->toDateTimeString(),
                'week_start' => Carbon::now()->subWeek()->toDateTimeString(),
                'now' => Carbon::now()->toDateTimeString()
            ]
        ]);

        // Рост количества заказов
        $currentMonthOrders = Order::whereMonth('created_at', Carbon::now()->month)->count();
        $lastMonthOrders = Order::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
        $stats['orders_growth'] = $lastMonthOrders > 0 
            ? round(($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders * 100, 2)
            : 100;

        // Статистика продаж из AnalyticsController
        $stats['average_order'] = $salesData['average_order'];
        $stats['today_revenue'] = Order::whereIn('status', ['completed', 'delivered', 'shipped'])
            ->whereDate('created_at', Carbon::today())
            ->sum('total_amount');

        // Данные по месяцам из AnalyticsController
        $monthlyData = $salesData['monthly_revenue'] ?? [];
        if (!empty($monthlyData['months'])) {
            $stats['monthly_dates'] = $monthlyData['months'];
            $stats['monthly_revenue'] = $monthlyData['revenues'];
        }

        // Статистика товаров
        $stats['out_of_stock'] = Product::where('stock_quantity', 0)->count();
        $stats['low_stock'] = Product::where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', 5)
            ->count();

        // Статистика пользователей
        $stats['new_users_today'] = User::whereDate('created_at', Carbon::today())->count();
        $stats['active_users'] = User::where('last_login_at', '>=', Carbon::now()->subDays(30))->count();
        // Получаем популярные товары
        $popularProducts = Product::select('products.*')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('products.id')
            ->orderByRaw('COUNT(order_items.id) DESC')
            ->withCount(['orders' => function ($query) {
                $query->where('status', '!=', 'cancelled');
            }])
            ->with(['images' => function($query) {
                $query->where('is_primary', true);
            }])
            ->take(5)
            ->get();



        // Последние заказы
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Последние товары
        $latestProducts = Product::with(['images' => function($query) {
                $query->where('is_primary', true);
            }])
            ->latest()
            ->take(5)
            ->get();

        // Количество эко-характеристик
        $ecoFeaturesCount = EcoFeature::count();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'latestProducts' => $latestProducts,
            'ecoFeaturesCount' => $ecoFeaturesCount,
            'debug' => [
                'orders_today' => $ordersToday,
                'orders_week' => $ordersWeek
            ]
        ]);
    }
}