<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\BlogPost;
use App\Models\EnvironmentalInitiative;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->take(4)
            ->get();

       
        // Получаем новые товары
        $newProducts = Product::where('is_new', true)
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        // Получаем основные категории
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->take(6)
            ->get();

        // Получаем экологические инициативы
        $initiatives = EnvironmentalInitiative::where('is_active', true)
            ->latest()
            ->take(2)
            ->get();

        // Получаем последние блог-посты
        $blogPosts = BlogPost::where('status', 'published')
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
