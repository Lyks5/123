<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        // Проверяем активность товара
        if (!$product->is_active) {
            abort(404);
        }

        // Загружаем связанные данные
        $product->load('categories', 'ecoFeatures', 'images', 'variants.attributeValues.attribute');

        // Получаем отзывы
        $reviews = $product->reviews()
            ->with('user')
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

        // Группируем атрибуты по группам для более удобного отображения
        $productAttributes = [];
        
        if ($product->parent_id) {
            // Если это вариант - берем атрибуты из его attribute_values_json
            if ($product->attribute_values_json) {
                foreach ($product->attribute_values_json as $attrId => $valueId) {
                    $attribute = \App\Models\Attribute::find($attrId);
                    $value = \App\Models\AttributeValue::find($valueId);
                    
                    if ($attribute && $value) {
                        $group = $attribute->type ?? 'general';
                        
                        if (!isset($productAttributes[$group])) {
                            $productAttributes[$group] = [];
                        }
                        
                        $productAttributes[$group][] = (object)[
                            'name' => $attribute->display_name_or_name,
                            'value' => $value->value,
                            'hex_color' => $value->hex_color
                        ];
                    }
                }
            }
        } else {
            // Если это основной товар - собираем атрибуты из всех его вариантов
            $processedAttributes = [];
            
            foreach ($product->variants as $variant) {
                foreach ($variant->attributeValues as $attributeValue) {
                    $attribute = $attributeValue->attribute;
                    $attrId = $attribute->id;
                    
                    // Избегаем дублирования атрибутов
                    if (!in_array($attrId, $processedAttributes)) {
                        $processedAttributes[] = $attrId;
                        
                        $group = $attribute->type ?? 'general';
                        
                        if (!isset($productAttributes[$group])) {
                            $productAttributes[$group] = [];
                        }
                        
                        $values = $product->variants()
                            ->join('variant_attribute_values', 'variants.id', '=', 'variant_attribute_values.variant_id')
                            ->join('attribute_values', 'variant_attribute_values.attribute_value_id', '=', 'attribute_values.id')
                            ->where('attribute_values.attribute_id', $attrId)
                            ->select('attribute_values.value', 'attribute_values.hex_color')
                            ->distinct()
                            ->get();
                            
                        $productAttributes[$group][] = (object)[
                            'name' => $attribute->display_name_or_name,
                            'values' => $values
                        ];
                    }
                }
            }
        }
        
        // Подготавливаем Эко характеристики для отображения
        $ecoFeatures = $product->ecoFeatures->map(function($feature) {
            return (object)[
                'name' => $feature->name,
                'description' => $feature->description,
                'icon' => $feature->icon,
                'value' => $feature->pivot->value ?? null
            ];
        });

        return view('pages.product', compact(
            'product',
            'reviews',
            'relatedProducts',
            'productAttributes',
            'ecoFeatures'
        ));
    }

    public function submitReview(Request $request, Product $product)
    {
        $existingReview = $product->reviews()->where('user_id', auth()->id())->first();
        if ($existingReview) {
            return redirect()->route('product.review.edit', $product->slug);
        }

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
            'is_approved' => true, // Automatically approve review
        ]);

        return redirect()->back()->with('success', 'Спасибо за ваш отзыв! Он будет опубликован после проверки.');
    }

    public function editReview(Product $product)
    {
        $review = $product->reviews()->where('user_id', auth()->id())->firstOrFail();

        // Load related data for the product page
        $product->load('categories', 'ecoFeatures', 'images', 'variants.attributeValues.attribute');

        // Get reviews including user's own unapproved review
        $reviews = $product->reviews()
            ->with('user')
            ->where(function ($query) {
                $query->where('is_approved', true);
                if (auth()->check()) {
                    $query->orWhere('user_id', auth()->id());
                }
            })
            ->latest()
            ->paginate(5);

        // Related products
        $relatedProducts = Product::whereHas('categories', function ($query) use ($product) {
                $query->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        // Group attributes as before
        $productAttributes = [];
        if ($product->parent_id) {
            if ($product->attribute_values_json) {
                foreach ($product->attribute_values_json as $attrId => $valueId) {
                    $attribute = \App\Models\Attribute::find($attrId);
                    $value = \App\Models\AttributeValue::find($valueId);
                    if ($attribute && $value) {
                        $group = $attribute->type ?? 'general';
                        if (!isset($productAttributes[$group])) {
                            $productAttributes[$group] = [];
                        }
                        $productAttributes[$group][] = (object)[
                            'name' => $attribute->display_name_or_name,
                            'value' => $value->value,
                            'hex_color' => $value->hex_color
                        ];
                    }
                }
            }
        } else {
            $processedAttributes = [];
            foreach ($product->variants as $variant) {
                foreach ($variant->attributeValues as $attributeValue) {
                    $attribute = $attributeValue->attribute;
                    $attrId = $attribute->id;
                    if (!in_array($attrId, $processedAttributes)) {
                        $processedAttributes[] = $attrId;
                        $group = $attribute->type ?? 'general';
                        if (!isset($productAttributes[$group])) {
                            $productAttributes[$group] = [];
                        }
                        $values = $product->variants()
                            ->join('variant_attribute_values', 'variants.id', '=', 'variant_attribute_values.variant_id')
                            ->join('attribute_values', 'variant_attribute_values.attribute_value_id', '=', 'attribute_values.id')
                            ->where('attribute_values.attribute_id', $attrId)
                            ->select('attribute_values.value', 'attribute_values.hex_color')
                            ->distinct()
                            ->get();
                        $productAttributes[$group][] = (object)[
                            'name' => $attribute->display_name_or_name,
                            'values' => $values
                        ];
                    }
                }
            }
        }

        $ecoFeatures = $product->ecoFeatures->map(function ($feature) {
            return (object)[
                'name' => $feature->name,
                'description' => $feature->description,
                'icon' => $feature->icon,
                'value' => $feature->pivot->value ?? null
            ];
        });

        return view('pages.product', compact(
            'product',
            'reviews',
            'relatedProducts',
            'productAttributes',
            'ecoFeatures',
            'review' // pass the user's review for editing
        ));
    }

    public function updateReview(Request $request, Product $product)
    {
        $review = $product->reviews()->where('user_id', auth()->id())->firstOrFail();

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

        $review->update([
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'is_verified_purchase' => $isVerifiedPurchase,
            'is_approved' => true, // Automatically approve review after edit
        ]);

        return redirect()->route('product.show', $product->slug)->with('success', 'Ваш отзыв успешно обновлен и будет опубликован после проверки.');
    }
}
