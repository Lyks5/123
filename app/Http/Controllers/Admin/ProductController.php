<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\EcoImpactRecord;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\EcoFeature;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ProductRequest;
use App\Services\ProductImageService;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $imageService;

    public function __construct(ProductImageService $imageService)
    {
        $this->imageService = $imageService;
    }

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
        // Получаем все необходимые данные напрямую из базы данных
        $categories = Category::active()->get();
        $ecoFeatures = EcoFeature::all();
        $attributes = Attribute::with('values')->get();
        
        // Используем dd() для отладки
        \Log::info('Categories count: ' . $categories->count());
        \Log::info('Attributes count: ' . $attributes->count());

        return view('admin.products.create', compact('categories', 'ecoFeatures', 'attributes'));
    }
    
    /**
     * Store a new product.
     */
    public function store(ProductRequest $request)
    {
        $validated = $request->validated();
    
        // Генерация slug
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
    
        // Создаем продукт
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

        // Создаем экологический эффект для продукта
        $product->ecoImpactRecord()->create([
            'plastic_saved' => $request->input('plastic_saved', 0),
            'carbon_saved' => $request->input('carbon_saved', 0),
            'water_saved' => $request->input('water_saved', 0),
            'type' => 'product',
            'description' => $request->input('eco_description')
        ]);
    
        // Прикрепляем категории
        $product->categories()->attach($validated['categories']);
    
        // Обработка основного изображения
        if ($request->hasFile('main_image')) {
            $mainImagePaths = $this->imageService->saveMainImage($request->file('main_image'));
            $product->update($mainImagePaths);
        }

        // Обработка дополнительных изображений
        if ($request->hasFile('additional_images')) {
            $additionalImages = $this->imageService->saveAdditionalImages($request->file('additional_images'));
            $product->additional_images = json_encode($additionalImages);
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
    
        if ($request->has('continue_editing')) {
            return redirect()->route('admin.products.edit', $product)
                ->with('success', 'Товар успешно создан. Продолжайте редактирование.');
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
                
                if (!in_array($attributeId, $productAttributes)) {
                    $productAttributes[] = $attributeId;
                }
                
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
    public function update(ProductRequest $request, Product $product)
    {
        $validated = $request->validated();
        
        // Set boolean values
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['is_new'] = $request->has('is_new');
        
        // Update product
        $product->update($validated);

        // Обновляем или создаем запись экологического эффекта
        $product->ecoImpactRecord()->updateOrCreate(
            ['product_id' => $product->id],
            [
                'plastic_reduced' => $request->input('plastic_reduced', 0),
                'co2_reduced' => $request->input('co2_reduced', 0),
                'water_saved' => $request->input('water_saved', 0)
            ]
        );
        
        // Sync categories
        $product->categories()->sync($request->categories);
        
        // Sync eco features
        $product->ecoFeatures()->detach();
        if ($request->has('eco_features')) {
            foreach ($request->eco_features as $featureId => $value) {
                $product->ecoFeatures()->attach($featureId, ['value' => $value]);
            }
        }
        
        // Обработка основного изображения
        if ($request->hasFile('main_image')) {
            // Удаляем старое основное изображение если есть
            if ($product->main_image) {
                $this->imageService->deleteImage($product->main_image);
            }
            
            $mainImagePaths = $this->imageService->saveMainImage($request->file('main_image'));
            $product->update($mainImagePaths);
        }

        // Обработка дополнительных изображений
        if ($request->hasFile('additional_images')) {
            // Удаляем старые дополнительные изображения если есть
            if ($product->additional_images) {
                $oldImages = json_decode($product->additional_images, true);
                foreach ($oldImages as $image) {
                    $this->imageService->deleteImage($image['original']);
                }
            }
            
            $additionalImages = $this->imageService->saveAdditionalImages($request->file('additional_images'));
            $product->additional_images = json_encode($additionalImages);
            $product->save();
        }
        
        // Handle variants
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
                    
                    if (isset($variantData['attribute_values']) && is_array($variantData['attribute_values'])) {
                        $variant->attributeValues()->attach($variantData['attribute_values']);
                    }
                }
            }
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно обновлен.');
    }

    /**
     * Delete a product.
     */
    public function destroy(Product $product)
    {
        // Удаляем все изображения продукта
        if ($product->main_image) {
            $this->imageService->deleteImage($product->main_image);
        }
        
        if ($product->additional_images) {
            $additionalImages = json_decode($product->additional_images, true);
            foreach ($additionalImages as $image) {
                $this->imageService->deleteImage($image['original']);
            }
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
        $values = cache()->remember("attribute_values_{$attributeId}", 3600, function() use ($attributeId) {
            return AttributeValue::where('attribute_id', $attributeId)->get();
        });
        return response()->json($values);
    }

    /**
     * Дублировать существующий продукт.
     */
    public function duplicate(Product $product)
    {
        $newProduct = $product->replicate();
        $newProduct->name = $product->name . ' (Копия)';
        $newProduct->sku = $product->sku . '-copy-' . Str::random(5);
        $newProduct->slug = Str::slug($newProduct->name) . '-' . Str::random(5);
        $newProduct->save();

        // Копируем связи
        $newProduct->categories()->attach($product->categories->pluck('id'));
        $newProduct->ecoFeatures()->attach($product->ecoFeatures->pluck('id'));

        // Копируем варианты
        foreach ($product->variants as $variant) {
            $newVariant = $variant->replicate();
            $newVariant->product_id = $newProduct->id;
            $newVariant->sku = $variant->sku . '-copy';
            $newVariant->save();
            $newVariant->attributeValues()->attach($variant->attributeValues->pluck('id'));
        }

        return redirect()->route('admin.products.edit', $newProduct)
            ->with('success', 'Товар успешно скопирован');
    }
}