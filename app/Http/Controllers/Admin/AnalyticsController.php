<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Review;
use App\Models\EnvironmentalInitiative;
use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    
    /**
     * Display the analytics dashboard.
     */
    public function index()
    {
        // Sales analytics
        $salesData['total_revenue'] = $salesData['total_revenue'] ?? 0;
        $salesData['order_count'] = $salesData['order_count'] ?? 0;
        $salesData['conversion_rates'] = $salesData['conversion_rates'] ?? ['current' => 0, 'change' => 0];
        $salesData['monthly_revenue'] = $salesData['monthly_revenue'] ?? ['revenues' => [], 'months' => []];
        $salesData['category_revenue'] = $salesData['category_revenue'] ?? [];
        
        // User analytics
        $userData = $this->getUserAnalytics();
        
        // Product analytics
        $productData = $this->getProductAnalytics();
        
        // Environmental impact analytics
        $environmentalData = $this->getEnvironmentalAnalytics();
        
        return view('admin.analytics.index', compact(
            'salesData',
            'userData',
            'productData',
            'environmentalData'
        ));
    }
    
    /**
     * Get sales analytics data.
     */
    private function getSalesAnalytics()
    {
        // Last 12 months of sales data
        $monthlyRevenue = $this->getMonthlyRevenue();
        
        // Sales by category
        $categoryRevenue = Category::select('categories.name', DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue'))
            ->join('category_product', 'categories.id', '=', 'category_product.category_id')
            ->join('products', 'category_product.product_id', '=', 'products.id')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->groupBy('categories.name')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get();
            
        // Average order value over time
        $avgOrderValues = Order::where('status', 'completed')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('AVG(total_amount) as average_value')
        )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get()
            ->reverse();
            
        // Conversion rate (orders / unique visitors) - using a placeholder
        // In a real app, you would use actual visitor data from analytics
        $conversionRates = [
            'current' => 3.2,
            'previous' => 2.8,
            'change' => 14.3
        ];
        
        return [
            'monthly_revenue' => $monthlyRevenue,
            'category_revenue' => $categoryRevenue,
            'avg_order_values' => $avgOrderValues,
            'conversion_rates' => $conversionRates,
            'average_order' => Order::where('status', 'completed')->avg('total_amount') ?: 0,
            'category_revenue' => $categoryRevenue,


        ];
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
            ->where('orders.status', 'completed')
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
        
        return [
            'months' => $months,
            'registrations' => $registrations,
            'total_users' => User::count(),
            'retention_rate' => $retentionRate,
            'top_customers' => $topCustomers,
            'new_users_30d' => User::where('created_at', '>=', Carbon::now()->subDays(30))->count(),
        ];
    }
    
    /**
     * Get product analytics data.
     */
    private function getProductAnalytics()
    {
        // Top selling products
        $topProducts = DB::table('products')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
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
            
        // Review statistics
        $reviewStats = [
            'total' => Review::count(),
            'average' => Review::avg('rating') ?: 0,
            'distribution' => [
                5 => Review::where('rating', 5)->count(),
                4 => Review::where('rating', 4)->count(),
                3 => Review::where('rating', 3)->count(),
                2 => Review::where('rating', 2)->count(),
                1 => Review::where('rating', 1)->count(),
            ],
        ];
        
        // Stock-level products
        $stockLevels = [
            'out_of_stock' => Product::where('stock_quantity', 0)->count(),
            'low_stock' => Product::where('stock_quantity', '>', 0)
                ->where('stock_quantity', '<=', 5)
                ->count(),
            'in_stock' => Product::where('stock_quantity', '>', 5)->count(),
        ];
        
        return [
            'top_products' => $topProducts,
            'review_stats' => $reviewStats,
            'stock_levels' => $stockLevels,
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
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
            ->where('orders.status', 'completed')
            ->distinct('order_items.id')
            ->sum('order_items.subtotal');

            
        $totalSales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->sum('order_items.subtotal');

            
        $regularSales = $totalSales - $ecoSales;
        
        // Environmental initiatives
        $activeInitiatives = EnvironmentalInitiative::where('is_active', true)->count();
        $totalInitiatives = EnvironmentalInitiative::count();
        
        // Calculate impact if data is available
        // This is placeholder data - in a real app, you would use actual impact data
        $impact = [
            'plastic_reduced' => 1250, // kg
            'co2_reduced' => 3500, // kg
            'water_saved' => 25000, // liters
        ];
        
        return [
            'eco_sales' => $ecoSales,
            'regular_sales' => $regularSales,
            'total_sales' => $totalSales,
            'eco_percentage' => $totalSales > 0 ? ($ecoSales / $totalSales) * 100 : 0,
            'initiatives' => [
                'active' => $activeInitiatives,
                'total' => $totalInitiatives,
            ],
            'impact' => $impact,
        ];
    }
    
    /**
     * Get monthly revenue data for the last 12 months.
     */
    private function getMonthlyRevenue()
    {
        $months = [];
        $revenues = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $revenue = Order::where('status', 'completed')
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->sum('total_amount');
            $revenues[] = $revenue;
        }
        
        return [
            'months' => $months,
            'revenues' => $revenues,
        ];
    }
}
