<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Review;
use App\Models\EcoFeature;
use App\Models\EcoImpactRecord;
use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use League\Csv\Writer;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AnalyticsController extends Controller
{
    /**
     * Constructor
     */
    
    /**
     * Clear analytics cache
     */
    public function clearCache()
    {
        cache()->forget('environmental_analytics');
        cache()->forget('analytics_dashboard');
        cache()->forget('sales_analytics');
        cache()->forget('monthly_sales_data');
        
        return redirect()->back()->with('success', 'Кэш аналитики успешно очищен');
    }


    /**
     * Display the analytics dashboard.
     */
    public function index()
    {
        // Сначала получаем и кэшируем данные об экологическом влиянии
        $environmentalData = cache()->remember('environmental_analytics', 3600, function () {
            return $this->getEnvironmentalAnalytics();
        });

        // Кэшируем остальные аналитические данные
        $analyticsData = cache()->remember('analytics_dashboard', 3600, function () use ($environmentalData) {
            $userData = $this->getUserAnalytics();
            $salesData = $this->getSalesAnalytics();
            $productStats = $this->getProductAnalytics();
            // Базовая статистика с оптимизированными запросами
            $orders = Order::select(
                DB::raw('COUNT(*) as total_count'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN total_amount ELSE 0 END) as total_revenue'),
                DB::raw('COUNT(CASE WHEN status IN ("completed", "delivered", "shipped") THEN 1 END) as completed_count'),
                DB::raw('SUM(CASE WHEN status IN ("completed", "delivered", "shipped") THEN total_amount END) / COUNT(CASE WHEN status IN ("completed", "delivered", "shipped") THEN 1 END) as avg_order_value')
            )->first();

            // Статистика по статусам заказов одним запросом
            $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            // Получаем данные о продажах
            $salesData = $this->getSalesAnalytics();
            
            // Данные о продажах по месяцам
            $monthlySalesData = $this->getMonthlySalesData();

            // Статистика по продуктам с оптимизированной выборкой
            $productStats = Product::select(
                DB::raw('COUNT(*) as total'),
                DB::raw('COUNT(CASE WHEN stock_quantity = 0 THEN 1 END) as out_of_stock'),
                DB::raw('COUNT(CASE WHEN stock_quantity > 0 AND stock_quantity <= 5 THEN 1 END) as low_stock')
            )->first();

            // Получаем базовую статистику пользователей
            $userStats = User::select(
                DB::raw('COUNT(*) as total'),
                DB::raw('COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END) as new_users')
            )->first();

            $environmentalData = $this->getEnvironmentalAnalytics();

            $data = [
                'orders' => [
                    'total' => $orders->total_count,
                    'completed' => $orders->completed_count,
                    'total_revenue' => $orders->total_revenue,
                    'average_order_value' => $orders->avg_order_value,
                    'by_status' => $ordersByStatus
                ],
                'sales' => $salesData,
                'monthly_data' => $monthlySalesData,
                'products' => $productStats,
                'users' => $userData
            ];

            // Добавляем экологические данные
            $data['environmental'] = $environmentalData;

            return $data;
        });

        // Убеждаемся, что все данные существуют
        $environmentalData = $analyticsData['environmental'] ?? [];
        
        // Подготавливаем месяцы на русском
        $monthNames = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthNames[] = Carbon::now()->subMonths($i)->locale('ru')->monthName;
        }

        // Получаем данные о выручке
        $yearlyData = collect($analyticsData['monthly_data']['year']);
        $revenueByMonth = $yearlyData->map(function($item) {
            return $item['revenue'] ?? 0;
        })->values()->toArray();

        // Получаем данные о статусах заказов
        $ordersByStatus = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count()
        ];

        // Получаем популярные товары
        $topProducts = Product::select(
            'products.name',
            DB::raw('COUNT(order_items.id) as sales_count')
        )
        ->join('order_items', 'products.id', '=', 'order_items.product_id')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->whereIn('orders.status', ['completed', 'delivered', 'shipped'])
        ->groupBy('products.id', 'products.name')
        ->orderByDesc('sales_count')
        ->limit(10)
        ->get();

        // Получаем популярные категории
        $topCategories = Category::select(
            'categories.name',
            DB::raw('COUNT(order_items.id) as sales_count')
        )
        ->join('products', 'categories.id', '=', 'products.category_id')
        ->join('order_items', 'products.id', '=', 'order_items.product_id')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->whereIn('orders.status', ['completed', 'delivered', 'shipped'])
        ->groupBy('categories.id', 'categories.name')
        ->orderByDesc('sales_count')
        ->limit(10)
        ->get();

        // Добавляем кнопку для очистки кэша
        return view('admin.analytics', [
            'cache_updated_at' => cache('analytics_dashboard_updated_at', now()),
            'analyticsData' => $analyticsData,
            'salesData' => $analyticsData['sales'] ?? [],
            'userData' => $analyticsData['users'] ?? [],
            'monthlyData' => $analyticsData['monthly_data'] ?? [],
            'productData' => $analyticsData['products'] ?? [],
            'environmentalData' => $environmentalData,
            'monthNames' => $monthNames,
            'revenueByMonth' => $revenueByMonth,
            'ordersByStatus' => $ordersByStatus,
            'totalSales' => $analyticsData['orders']['total_revenue'] ?? 0,
            'totalOrders' => $analyticsData['sales']['order_count'] ?? 0,
            'averageOrderValue' => $analyticsData['sales']['average_order'] ?? 0,
            'totalCustomers' => $analyticsData['users']['total_users'] ?? 0,
            'topProducts' => $topProducts,
            'topCategories' => $topCategories
        ]);
    }

    public function analytics()
    {
        return $this->index();
    }
    
    /**
     * Get sales analytics data.
     */
    private function getSalesAnalytics()
    {
        // Кэшируем данные на 1 час
        return cache()->remember('sales_analytics', 3600, function () {
            // Получаем данные о посетителях из таблицы sessions
            // Упрощенный подсчет посетителей на основе заказов
            $visitors = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(DISTINCT user_id) as visitors'))
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->get();

            // Получаем данные о заказах
            $orders = Order::where('created_at', '>=', now()->subDays(30))
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as orders'))
                ->groupBy('date')
                ->get();

            // Расчет конверсии
            $conversionRates = [];
            foreach ($visitors as $visitor) {
                $dayOrders = $orders->where('date', $visitor->date)->first();
                $conversionRates[$visitor->date] = $visitor->visitors > 0 ? 100 : 0; // Временно установим 100% конверсию для упрощения
            }

            // Популярные товары с детальной статистикой
            $topProducts = Product::select(
                'products.id',
                'products.name',
                'products.sku',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['completed', 'delivered', 'shipped'])
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

            // Расширенная статистика по категориям
            $categoryStats = Category::select(
                'categories.name',
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count'),
                DB::raw('COUNT(DISTINCT order_items.product_id) as unique_products'),
                DB::raw('AVG(order_items.quantity * order_items.price) as avg_order_value')
            )
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['completed', 'delivered', 'shipped'])
            ->groupBy('categories.name')
            ->orderByDesc('total_revenue')
            ->get();

            // Динамика продаж по времени
            $salesTrends = Order::where('status', 'completed')
                ->where('created_at', '>=', now()->subYear())
                ->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('SUM(total_amount) as revenue'),
                    DB::raw('COUNT(*) as order_count'),
                    DB::raw('AVG(total_amount) as avg_order_value'),
                    DB::raw('COUNT(DISTINCT user_id) as unique_customers')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $currentPeriodSales = Order::where('status', 'completed')
                ->where('created_at', '>=', now()->subDays(30))
                ->sum('total_amount');

            $previousPeriodSales = Order::where('status', 'completed')
                ->whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])
                ->sum('total_amount');

            $salesGrowth = $previousPeriodSales > 0
                ? (($currentPeriodSales - $previousPeriodSales) / $previousPeriodSales) * 100
                : 100;

            // Получаем общую выручку и количество заказов
            $totalRevenue = Order::whereIn('status', ['completed', 'delivered', 'shipped'])->sum('total_amount') ?? 0;
            $orderCount = Order::where('status', 'completed')->count() ?? 0;

            // Устанавливаем цели (можно настроить в зависимости от бизнес-требований)
            $revenueGoal = 1000000; // Цель по выручке
            $ordersGoal = 1000; // Цель по количеству заказов

            return [
                'conversion_rates' => [
                    'current' => array_sum($conversionRates) / max(count($conversionRates), 1),
                    'change' => $salesGrowth
                ],
                'total_revenue' => $totalRevenue,
                'revenue_goal_progress' => min(100, ($totalRevenue / $revenueGoal) * 100),
                'order_count' => $orderCount,
                'orders_goal_progress' => min(100, ($orderCount / $ordersGoal) * 100),
                'top_products' => $topProducts,
                'category_revenue' => $categoryStats,
                'monthly_revenue' => [
                    'months' => $salesTrends->pluck('month'),
                    'revenues' => $salesTrends->pluck('revenue')
                ],
                'sales_growth' => $salesGrowth,
                'average_order' => Order::whereIn('status', ['completed', 'delivered', 'shipped'])->avg('total_amount') ?: 0
            ];
        });
    }
    
    /**
     * Get user analytics data.
     */
    private function getUserAnalytics()
    {
        // User registration over time
        $months = [];
        $registrations = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $count = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $registrations[] = $count;
        }
        
        // User retention rate (placeholder)
        $retentionRate = [
            'current' => 65,
            'previous' => 62,
            'change' => 4.8
        ];
        
        // Top customers by spending
        $topCustomers = DB::table('users')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->whereIn('orders.status', ['completed', 'delivered', 'shipped'])
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('SUM(orders.total_amount) as total_spent'),
                DB::raw('COUNT(orders.id) as order_count')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_spent', 'desc')
            ->limit(5)
            ->get();
        
        $totalUsers = User::count();
        $newUsers = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        return [
            'months' => $months,
            'registrations' => $registrations,
            'total_users' => $totalUsers ?? 0,
            'retention_rate' => $retentionRate,
            'top_customers' => $topCustomers,
            'new_users_30d' => $newUsers ?? 0,
            'users_goal_progress' => 100 * ($totalUsers / max($totalUsers, 1000)) // Целевое значение 1000 пользователей
        ];
    }
    
    /**
     * Get product analytics data.
     */
    private function getProductAnalytics(): array
    {
        // Top selling products
        $topProducts = DB::table('products')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['completed', 'delivered', 'shipped'])
            ->select(
                'products.id',
                'products.name',
                'products.sku',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();
            
        // Stock-level products
        $stockLevels = [
            'out_of_stock' => Product::where('stock_quantity', 0)->count(),
            'low_stock' => Product::where('stock_quantity', '>', 0)
                ->where('stock_quantity', '<=', 5)
                ->count(),
            'in_stock' => Product::where('stock_quantity', '>', 5)->count(),
        ];
        
        try {
            $data = [
                'review_stats' => $this->getReviewStatistics(),
                'stock_levels' => $stockLevels,
                'total_products' => Product::count(),
                'active_products' => Product::where('is_active', true)->count(),
            ];

            if ($topProducts->isNotEmpty()) {
                $data['top_products'] = $topProducts;
            }

            return $data;
        } catch (\Exception $e) {
            Log::error('Error in product analytics: ' . $e->getMessage());
            return [
                'review_stats' => [],
                'stock_levels' => [],
                'total_products' => 0,
                'active_products' => 0,
                'top_products' => []
            ];
        }
    }

    /**
     * Get review statistics
     */
    private function getReviewStatistics(): array
    {
        return [
            'total' => 0,
            'average' => 0,
            'distribution' => [
                5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0
            ]
        ];
    }
    
    /**
     * Get environmental analytics data.
     */
    private function getEnvironmentalAnalytics()
    {
        // Eco-product sales vs regular products
        $ecoSales = DB::table('products')
            ->join('eco_feature_product', 'products.id', '=', 'eco_feature_product.product_id')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['completed', 'delivered', 'shipped'])
            ->distinct('order_items.id')
            ->sum('order_items.subtotal');

            
        $totalSales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['completed', 'delivered', 'shipped'])
            ->sum('order_items.subtotal');

            
        $regularSales = $totalSales - $ecoSales;
        
        // Eco features statistics
        $activeFeatures = EcoFeature::where('is_active', true)->count();
        $totalFeatures = EcoFeature::count();

        // Calculate impact if data is available
        // This is placeholder data - in a real app, you would use actual impact data
        // Получаем суммарные экологические показатели
        $ecoStats = EcoImpactRecord::select(
            DB::raw('SUM(plastic_saved) as plastic_saved'),
            DB::raw('SUM(carbon_saved) as carbon_saved'),
            DB::raw('SUM(water_saved) as water_saved')
        )->where('type', 'product')
        ->first();
        
        return [
            'eco_sales' => $ecoSales,
            'regular_sales' => $regularSales,
            'total_sales' => $totalSales,
            'eco_percentage' => $totalSales > 0 ? ($ecoSales / $totalSales) * 100 : 0,
            'initiatives' => [
                'active' => $activeFeatures,
                'total' => $totalFeatures,
            ],
            'impact' => [
                'plastic_reduced' => $ecoStats->plastic_saved ?? 0,
                'co2_reduced' => $ecoStats->carbon_saved ?? 0,
                'water_saved' => $ecoStats->water_saved ?? 0
            ],
        ];
    }
    
    /**
     * Get monthly revenue data for the last 12 months.
     */
    protected function getMonthlySalesData(): array
    {
        return cache()->remember('monthly_sales_data', 3600, function () {
            $periods = collect(['day', 'week', 'month', 'year'])->mapWithKeys(function ($period) {
                $query = Order::whereIn('status', ['completed', 'delivered', 'shipped']);
                
                switch ($period) {
                    case 'day':
                        $query->where('created_at', '>=', now()->subDay());
                        $groupBy = 'DATE_FORMAT(created_at, "%H:00")';
                        break;
                    case 'week':
                        $query->where('created_at', '>=', now()->subWeek());
                        $groupBy = 'DATE(created_at)';
                        break;
                    case 'month':
                        $query->where('created_at', '>=', now()->subMonth());
                        $groupBy = 'DATE(created_at)';
                        break;
                    case 'year':
                        $query->where('created_at', '>=', now()->subYear());
                        $groupBy = 'DATE_FORMAT(created_at, "%Y-%m")';
                        break;
                }

                $data = $query->select(
                    DB::raw($groupBy . ' as period'),
                    DB::raw('SUM(total_amount) as revenue'),
                    DB::raw('COUNT(*) as orders'),
                    DB::raw('COUNT(DISTINCT user_id) as customers'),
                    DB::raw('AVG(total_amount) as avg_order')
                )
                ->groupBy('period')
                ->orderBy('period')
                ->get();

                return [$period => $data];
            });

            return $periods->toArray();
        });
    }
    /**
     * Экспорт аналитических данных в CSV формате
     */
    public function exportCsv()
    {
        try {
            // Sales data
            $salesData = $this->getSalesAnalytics();
            $data['Общая выручка'] = $salesData['total_revenue'] ?? 0;
            $data['Количество заказов'] = $salesData['order_count'] ?? 0;
            $data['Средний чек'] = $salesData['average_order'] ?? 0;
            $data['Конверсия'] = $salesData['conversion_rates']['current'] ?? 0;
            
            // User data
            $userData = $this->getUserAnalytics();
            $data['Всего пользователей'] = $userData['total_users'] ?? 0;
            $data['Новых за 30 дней'] = $userData['new_users_30d'] ?? 0;
            
            // Environmental data
            $envData = $this->getEnvironmentalAnalytics();
            $data['Снижение CO2 (кг)'] = $envData['impact']['co2_reduced'] ?? 0;
            $data['Экономия воды (л)'] = $envData['impact']['water_saved'] ?? 0;
            $data['Снижение пластика (кг)'] = $envData['impact']['plastic_reduced'] ?? 0;

            $csvData = "Дата,Показатель,Значение\n";
            foreach ($data as $key => $value) {
                $csvData .= date('Y-m-d') . "," . $key . "," . $value . "\n";
            }

            return Response::make($csvData, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename=analytics_' . date('Y-m-d') . '.csv'
            ]);
        } catch (\Exception $e) {
            Log::error('Ошибка экспорта CSV: ' . $e->getMessage());
            return back()->with('error', 'Ошибка при экспорте данных');
        }
    }

    /**
     * Экспорт аналитических данных в PDF формате
     */
    public function exportPdf()
    {
        try {
            // Sales data
            $salesData = $this->getSalesAnalytics();
            $data['Общая выручка'] = $salesData['total_revenue'] ?? 0;
            $data['Количество заказов'] = $salesData['order_count'] ?? 0;
            $data['Средний чек'] = $salesData['average_order'] ?? 0;
            $data['Конверсия'] = $salesData['conversion_rates']['current'] ?? 0;
            
            // User data
            $userData = $this->getUserAnalytics();
            $data['Всего пользователей'] = $userData['total_users'] ?? 0;
            $data['Новых за 30 дней'] = $userData['new_users_30d'] ?? 0;
            
            // Environmental data
            $envData = $this->getEnvironmentalAnalytics();
            $data['Снижение CO2 (кг)'] = $envData['impact']['co2_reduced'] ?? 0;
            $data['Экономия воды (л)'] = $envData['impact']['water_saved'] ?? 0;
            $data['Снижение пластика (кг)'] = $envData['impact']['plastic_reduced'] ?? 0;

            $html = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <style>
                    body { font-family: DejaVu Sans, sans-serif; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #000; padding: 8px; text-align: left; }
                    th { background-color: #f5f5f5; }
                    h1 { color: #333; }
                </style>
            </head>
            <body>
                <h1>Аналитический отчет</h1>
                <p>Дата: ' . date('Y-m-d') . '</p>
                <table>
                    <tr>
                        <th>Показатель</th>
                        <th>Значение</th>
                    </tr>';
            
            foreach ($data as $key => $value) {
                $html .= '<tr>';
                $html .= '<td>' . $key . '</td>';
                $html .= '<td>' . number_format($value, 0, ',', ' ') . '</td>';
                $html .= '</tr>';
            }
            
            $html .= '</table>
            </body>
            </html>';

            $pdf = PDF::loadHTML($html);
            $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            $pdf->setPaper('A4');
            return $pdf->download('analytics_' . date('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            Log::error('Ошибка экспорта PDF: ' . $e->getMessage());
            return back()->with('error', 'Ошибка при экспорте данных');
        }
    }
}
