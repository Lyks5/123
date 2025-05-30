<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\EcoFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'ecoFeatures', 'images'])
            ->where('status', 'published');

        $query = $this->applyFiltersAndSort($query, $request);
        $products = $query->paginate(12)->withQueryString();
        
        // Получаем все категории для фильтра с количеством товаров
        $categories = Category::where('is_active', true)
            ->withCount(['products' => function ($query) {
                $query->where('status', 'published');
            }])
            ->get();
        
        // Получаем все эко-особенности для фильтра с количеством товаров
        $ecoFeatures = EcoFeature::where('is_active', true)
            ->withCount(['products' => function ($query) {
                $query->where('status', 'published');
            }])
            ->get();

        // Получаем мин и макс цены для фильтра
        $priceRange = [
            'min' => Product::where('status', 'published')->min('price'),
            'max' => Product::where('status', 'published')->max('price')
        ];

        if ($request->ajax()) {
            $html = View::make('components.product-grid', compact('products'))->render();
            return response()->json([
                'html' => $html,
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
                'total' => $products->total()
            ]);
        }

        return view('pages.shop', compact(
            'products',
            'categories',
            'ecoFeatures',
            'priceRange'
        ));
    }

    public function category(Category $category)
    {
        $products = Product::where('category_id', $category->id)
            ->where('status', 'published')
            ->paginate(12);

        return view('pages.category', compact('category', 'products'));
    }

    private function applyFiltersAndSort($query, Request $request)
    {
        // Фильтр по категориям (множественный выбор)
        if ($request->has('category')) {
            $categories = explode(',', $request->category);
            if (!in_array('all', $categories)) {
                $query->whereIn('category_id', $categories);
            }
        }

        // Фильтр по цене
        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        // Фильтр по эко-рейтингу
        if ($request->has('eco_score') && $request->eco_score > 0) {
            $query->where('eco_score', '>=', $request->eco_score);
        }

        // Фильтр по эко-особенностям
        if ($request->has('eco_features')) {
            $features = explode(',', $request->eco_features);
            $query->whereHas('ecoFeatures', function ($q) use ($features) {
                $q->whereIn('id', $features);
            });
        }

        // Сортировка
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price-low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price-high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'popular':
                    $query->withCount('reviews')->orderBy('reviews_count', 'desc');
                    break;
                case 'eco-high':
                    $query->orderBy('eco_score', 'desc');
                    break;
                default:
                    $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
        }

        return $query;
    }

    public function filter(Request $request)
    {
        $query = Product::with(['category', 'ecoFeatures', 'images'])
            ->where('status', 'published');

        $query = $this->applyFiltersAndSort($query, $request);
        $products = $query->paginate(12)->withQueryString();

        if ($request->ajax()) {
            $html = View::make('components.product-grid', compact('products'))->render();
            return response()->json([
                'html' => $html,
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
                'total' => $products->total()
            ]);
        }

        return $products;
    }
}
    