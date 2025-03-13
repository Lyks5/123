<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\BlogPost;
use App\Models\EcoFeature;
use App\Models\EnvironmentalInitiative;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['categories', 'ecoFeatures', 'images'])
            ->where('is_active', true);
        


        // Фильтр по категории
        if ($request->has('category') && $request->category !== 'all') {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Фильтр по цене
        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }
        // Фильтр по статусу новинки
        if ($request->has('is_new') && $request->is_new) {
            $query->where('is_new', true);
        }
        // Фильтр по эко-характеристикам
        if ($request->has('eco_feature')) {
            $query->whereHas('ecoFeatures', function ($q) use ($request) {
                $q->where('slug', $request->eco_feature);
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
                    default:
                        $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                        break;
                }
            } else {
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
            }
    
            $products = $query->paginate(12)->withQueryString();
            
            // Получаем все категории для фильтра
            $categories = Category::where('is_active', true)->get();
            
            // Получаем все эко-особенности для фильтра
            $ecoFeatures = EcoFeature::all();
    
            // Получаем мин и макс цены для фильтра
            $priceRange = [
                'min' => Product::where('is_active', true)->min('price'),
                'max' => Product::where('is_active', true)->max('price')
            ];
    
            return view('pages.shop', compact(
                'products', 
                'categories', 
                'ecoFeatures',
                'priceRange'
            ));
        }
    
        public function category(Category $category)
        {
            $products = Product::whereHas('categories', function ($query) use ($category) {
                    $query->where('categories.id', $category->id);
                })
                ->where('is_active', true)
                ->paginate(12);
    
            return view('pages.category', compact('category', 'products'));
        }
    }
    