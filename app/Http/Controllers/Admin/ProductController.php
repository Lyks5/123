<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\EcoFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    /**
     * Show the product management page.
     */
    public function index(Request $request)
    {
        $query = Product::with(['categories', 'ecoFeatures']);
        
        // Apply filters if any
        if ($request->has('category')) {
            $query->whereHas('categories', function($q) use($request) {
                $q->where('categories.id', $request->category);
            });
        }
        
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        if ($request->has('stock')) {
            if ($request->stock === 'in') {
                $query->where('stock_quantity', '>', 0);
            } elseif ($request->stock === 'out') {
                $query->where('stock_quantity', 0);
            } elseif ($request->stock === 'low') {
                $query->where('stock_quantity', '>', 0)
                      ->where('stock_quantity', '<', 5);
            }
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Default sorting
        $sort = $request->sort ?? 'latest';
        
        switch($sort) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'price_low':
                $query->orderBy('price');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'stock_low':
                $query->orderBy('stock_quantity');
                break;
            case 'stock_high':
                $query->orderBy('stock_quantity', 'desc');
                break;
            default:
                $query->latest();
        }
        
        $products = $query->paginate(15)->withQueryString();
        $categories = Category::all();
        
        return view('admin.products.index', compact('products', 'categories'));
    }
    
    /**
     * Show the form to create a new product.
     */
    public function create()
    {
        $categories = Category::all();
        $ecoFeatures = EcoFeature::all();
        $attributes = Attribute::all();
        return view('admin.products.create', compact('categories', 'ecoFeatures', 'attributes'));
    }
    
    /**
     * Store a new product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|max:100|unique:products',
            'stock_quantity' => 'required|integer|min:0',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'eco_features' => 'nullable|array',
            'variants' => 'nullable|array',
            'variants.*.sku' => 'required_with:variants|string|max:100|distinct',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.attributes' => 'nullable|array',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_image' => 'nullable|integer',
        ]);
    
        // Генерация slug
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
    
        // Создаем продукт, передавая только нужные поля
        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'short_description' => $validated['short_description'] ?? null,
            'price' => $validated['price'],
            'sale_price' => $validated['sale_price'] ?? null,
            'sku' => $validated['sku'],
            'stock_quantity' => $validated['stock_quantity'],
            'slug' => $validated['slug'],
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
            'is_new' => $request->boolean('is_new'),
            'eco_features' => $validated['eco_features'] ?? null,
        ]);
    
        // Прикрепляем категории
        $product->categories()->attach($validated['categories']);
    
        // Обработка и сохранение изображений
        if ($request->hasFile('images')) {
            $imageBlobs = [];
    
            foreach ($request->file('images') as $imageFile) {
                $image = Image::make($imageFile->getRealPath());
    
                // Изменяем размер по ширине 1680px с сохранением пропорций
                $image->resize(1680, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
    
                // Кодируем в WebP с качеством 80%
                $imageContent = (string) $image->encode('webp', 80);
    
                $imageBlobs[] = $imageContent;
            }
    
            // Сохраняем все изображения в JSON формате в отдельное поле
            $product->image_blobs = json_encode($imageBlobs);
    
            // Если нужно, сохраняем первичное изображение отдельно
            if (isset($validated['primary_image']) && isset($imageBlobs[$validated['primary_image']])) {
                $product->image_blob = $imageBlobs[$validated['primary_image']];
            } else {
                // Если primary_image не указан, берем первое изображение
                $product->image_blob = $imageBlobs[0];
            }
    
            $product->save();
        }
    
        // Создание вариантов
        if (!empty($validated['variants'])) {
            foreach ($validated['variants'] as $variantData) {
                $product->variants()->create([
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'] ?? $product->price,
                    'sale_price' => $variantData['sale_price'] ?? $product->sale_price,
                    'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                    'attributes' => $variantData['attributes'] ?? [],
                ]);
            }
        }
    
        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно создан.');
    }
    
    /**
     * Show the form to edit a product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $ecoFeatures = EcoFeature::all();
        $attributes = Attribute::all();
        $productEcoFeatures = $product->ecoFeatures->pluck('pivot.value', 'id')->toArray();
        
        // Get attributes and their values for this product
        $productAttributes = [];
        $productAttributeValues = [];
        
        foreach ($product->variants as $variant) {
            foreach ($variant->attributeValues as $value) {
                $attributeId = $value->attribute_id;
                
                // Store used attributes
                if (!in_array($attributeId, $productAttributes)) {
                    $productAttributes[] = $attributeId;
                }
                
                // Store values for each attribute
                if (!isset($productAttributeValues[$attributeId])) {
                    $productAttributeValues[$attributeId] = [];
                }
                
                if (!in_array($value->id, $productAttributeValues[$attributeId])) {
                    $productAttributeValues[$attributeId][] = $value->id;
                }
            }
        }
        
        return view('admin.products.edit', compact(
            'product', 
            'categories', 
            'ecoFeatures', 
            'attributes',
            'productEcoFeatures', 
            'productAttributes', 
            'productAttributeValues'
        ));
    }
    
    /**
     * Update a product.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'stock_quantity' => 'required|integer|min:0',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'eco_features' => 'nullable|array',
            'eco_features.*' => 'exists:eco_features,id',
            'product_attributes' => 'nullable|array',
            'product_attributes.*' => 'exists:attributes,id',
            'attribute_values' => 'nullable|array',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:variants,id',
            'variants.*.sku' => 'required_with:variants|string|max:100',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.attribute_values' => 'required_with:variants|array',
            'delete_variants' => 'nullable|array',
            'delete_variants.*' => 'exists:variants,id',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_image' => 'nullable|integer',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer|exists:product_images,id',
        ]);
        
        // Set boolean values
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['is_new'] = $request->has('is_new');
        
        // Update product
        $product->update($validated);
        
        // Sync categories
        $product->categories()->sync($request->categories);
        
        // Sync eco features
        $product->ecoFeatures()->detach();
        if ($request->has('eco_features')) {
            foreach ($request->eco_features as $featureId => $value) {
                $product->ecoFeatures()->attach($featureId, ['value' => $value]);
            }
        }
        
        // Handle variants delete
        if ($request->has('delete_variants')) {
            $product->variants()->whereIn('id', $request->delete_variants)->delete();
        }
        
        // Handle product variants
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                if (isset($variantData['id'])) {
                    // Update existing variant
                    $variant = $product->variants()->findOrFail($variantData['id']);
                    $variant->update([
                        'sku' => $variantData['sku'],
                        'price' => $variantData['price'] ?? $product->price,
                        'sale_price' => $variantData['sale_price'] ?? $product->sale_price,
                        'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                    ]);
                    
                    // Sync attribute values
                    if (isset($variantData['attribute_values']) && is_array($variantData['attribute_values'])) {
                        $variant->attributeValues()->sync($variantData['attribute_values']);
                    }
                } else {
                    // Create new variant
                    $variant = $product->variants()->create([
                        'sku' => $variantData['sku'],
                        'price' => $variantData['price'] ?? $product->price,
                        'sale_price' => $variantData['sale_price'] ?? $product->sale_price,
                        'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                        'is_active' => true,
                    ]);
                    
                    // Attach attribute values
                    if (isset($variantData['attribute_values']) && is_array($variantData['attribute_values'])) {
                        $variant->attributeValues()->attach($variantData['attribute_values']);
                    }
                }
            }
        }
        
        // Handle new images
        if ($request->hasFile('images')) {
            $imageFile = $request->file('images')[0];
            $imageContent = file_get_contents($imageFile->getRealPath());
            $product->image_blob = $imageContent;
            $product->save();
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно обновлен.');
    }

    /**
     * Delete a product.
     */
    public function destroy(Product $product)
    {
        // Delete associated images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно удален.');
    }

    /**
     * Get attribute values list (AJAX endpoint).
     */
    public function getAttributeValues($attributeId)
    {
        $values = AttributeValue::where('attribute_id', $attributeId)->get();
        return response()->json($values);
    }
}