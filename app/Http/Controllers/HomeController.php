<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\BlogPost;
use App\Models\EcoFeature; // Заменяем EnvironmentalInitiative на EcoFeature
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        // Избранные товары (без изменений)
        $featuredProducts = Product::where('is_featured', true)
            ->where('status', 'published')
            ->take(4)
            ->get();

        // Новые товары (последние добавленные)
        $newProducts = Product::where('status', 'published')
            ->latest()
            ->take(8)
            ->get();

        // Основные категории (обновлено)
        $categories = Category::whereNull('parent_id')
            ->whereNull('parent_id')
            ->take(6)
            ->get();

        // Экологические инициативы заменены на EcoFeature
        $initiatives = EcoFeature::query()
            ->when(
                Schema::hasColumn('eco_features', 'is_active'),
                function ($query) {
                    $query->where('is_active', true); // Используем is_active если есть
                },
                function ($query) {
                    $query->whereNotNull('created_at'); // Альтернативная логика
                }
            )
            ->latest()
            ->take(2)
            ->get();

        // Блог-посты (обновлённая логика связи с категориями)
        $blogPosts = BlogPost::with('category') // Предполагается наличие связи belongsTo
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('pages.home', compact(
            'featuredProducts',
            'newProducts',
            'categories',
            'initiatives',
            'blogPosts'
        ));
    }
}