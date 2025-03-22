<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use App\Models\ProductQuestion;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Variant;
class ProductController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $ecoFeatures = EcoFeature::all();
        $attributes = Attribute::all();
        return view('admin.products.create', compact('categories', 'ecoFeatures', 'attributes'));
    }
    public function show(Product $product = null)
    {
        // Проверяем, существует ли товар
        if (!$product || !$product->is_active) {
            return response()->view('errors.product_not_found', [], 404);
        }

        // Загружаем связанные данные
        $product->load('categories', 'ecoFeatures', 'images', 'variants');

        // Получаем отзывы
        $reviews = $product->reviews()
            ->with('user')
            ->where('is_approved', true)
            ->latest()
            ->paginate(5);

        // Получаем связанные товары
        $relatedProducts = Product::whereHas('categories', function ($query) use ($product) {
                $query->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        // Получаем вопросы и ответы
        $questions = $product->questions()
            ->with(['user', 'answers' => function ($query) {
                $query->where('is_approved', true)
                    ->with('user')
                    ->orderBy('is_admin', 'desc')
                    ->orderBy('created_at', 'asc');
            }])
            ->where('is_approved', true)
            ->paginate(5, ['*'], 'questions_page');

        return view('pages.product', compact(
            'product',
            'reviews',
            'relatedProducts',
            'questions'
        ));
    }

    public function submitReview(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|max:100',
            'comment' => 'required|min:10',
        ]);

        // Проверяем, покупал ли пользователь этот товар
        $isVerifiedPurchase = auth()->user()->orders()
            ->whereHas('items', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->exists();

        Review::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'is_verified_purchase' => $isVerifiedPurchase,
            'is_approved' => false, // Требуется модерация
        ]);

        return redirect()->back()->with('success', 'Спасибо за ваш отзыв! Он будет опубликован после проверки.');
    }

    public function submitQuestion(Request $request, Product $product)
    {
        $request->validate([
            'question' => 'required|min:10|max:1000',
        ]);

        ProductQuestion::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'question' => $request->question,
            'is_answered' => false,
            'is_approved' => false, // Требуется модерация
        ]);

        return redirect()->back()->with('success', 'Спасибо за ваш вопрос! Он будет опубликован после проверки.');
    }

    public function submitAnswer(Request $request, ProductQuestion $question)
    {
        $request->validate([
            'answer' => 'required|min:10|max:1000',
        ]);

        $question->answers()->create([
            'user_id' => auth()->id(),
            'answer' => $request->answer,
            'is_admin' => auth()->user()->hasRole('admin'), // Предполагается, что у вас есть система ролей
            'is_approved' => false, // Требуется модерация
        ]);

        // Обновляем статус вопроса
        $question->update(['is_answered' => true]);

        return redirect()->back()->with('success', 'Спасибо за ваш ответ! Он будет опубликован после проверки.');
    }
    public function attributes()
    {
        return Attribute::whereHas('values', function($query) {
            $query->whereHas('variants', function($q) {
                $q->where('product_id', $this->id);
            });
        })->distinct();
    }
}
