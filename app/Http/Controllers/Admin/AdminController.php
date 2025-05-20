<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\BlogPost;
use App\Models\EcoFeature;
use Illuminate\Http\Request;

/**
 * Базовый контроллер панели администратора
 *
 * Функционал управления категориями перенесен в:
 * @see \App\Http\Controllers\Admin\CategoryManagementController
 *
 * Функционал управления блогом перенесен в:
 * @see \App\Http\Controllers\Admin\BlogManagementController
 *
 * Функционал управления эко-характеристиками перенесен в:
 * @see \App\Http\Controllers\Admin\EcoFeaturesController
 *
 * Функционал управления инициативами перенесен в:
 * @see \App\Http\Controllers\Admin\InitiativesController
 *
 * Функционал печати документов перенесен в:
 * @see \App\Http\Controllers\Admin\OrderManagementController
 *
 * Функционал аналитики перенесен в:
 * @see \App\Http\Controllers\Admin\AnalyticsController
 */
class AdminController extends \App\Http\Controllers\Controller
{
    /**
     * Конструктор с middleware авторизации и проверки прав администратора
     */
 

    /**
     * Отображение дашборда администратора
     */
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'orders' => Order::count(),
            'users' => User::count(),
            'posts' => BlogPost::count()
        ];

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        $latestProducts = Product::with('images')
            ->latest()
            ->take(5)
            ->get();

        $ecoFeaturesCount = EcoFeature::count();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'latestProducts', 'ecoFeaturesCount'));
    }
}