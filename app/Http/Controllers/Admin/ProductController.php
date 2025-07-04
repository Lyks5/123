<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\EcoFeature;
use App\Models\Arrival;

use App\Http\Requests\Admin\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use App\Services\ProductImageService;
use App\Services\CategoryService;
use App\Services\AttributeService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected $productService;
    protected $productImageService;
    protected $categoryService;
    protected $attributeService;

    public function __construct(
        ProductService $productService,
        ProductImageService $productImageService,
        CategoryService $categoryService,
        AttributeService $attributeService
    ) {
        $this->productService = $productService;
        $this->productImageService = $productImageService;
        $this->categoryService = $categoryService;
        $this->attributeService = $attributeService;
    }

    public function index(Request $request)
    {
        $query = Product::select('products.*');

        // Создаем подзапрос для выбора первого изображения по order
        $firstImageSubquery = \DB::table('product_images as pi1')
            ->select('pi1.image_path')
            ->whereColumn('pi1.product_id', 'products.id')
            ->orderBy('pi1.order')
            ->limit(1);

        $query->selectSub($firstImageSubquery, 'image_url');

        // Фильтр по названию
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Фильтр по SKU
        if ($request->filled('sku')) {
            $query->where('sku', 'like', '%' . $request->sku . '%');
        }

        // Фильтр по категории
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Фильтр по статусу
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Фильтр по цене
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Фильтр по количеству на складе
        if ($request->filled('stock_min')) {
            $query->where('stock_quantity', '>=', $request->stock_min);
        }
        if ($request->filled('stock_max')) {
            $query->where('stock_quantity', '<=', $request->stock_max);
        }

        // Сортировка
        $query->orderBy('created_at', 'desc');

        $products = $query->paginate(20)->withQueryString();
        $categories = \App\Models\Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        return view('admin.products.create', [
            'categories' => $this->categoryService->getAll(),
            'attributes' => $this->attributeService->getAll(),
            'ecoFeatures' => EcoFeature::all(),
            'arrivals' => Arrival::where('status', 'active')
                ->where('quantity', '>', 0)
                ->get()
        ]);
    }

    public function store(ProductRequest $request)
    {
        $validatedData = $request->validated();
        
        if ($request->has('arrival_id')) {
            return $this->storeFromArrival($validatedData);
        }
        
        return $this->storeRegular($validatedData);
    }
    
    protected function storeFromArrival(array $validatedData)
    {
        try {
            DB::beginTransaction();
            
            // Создаем продукт из поступления
            $product = $this->productService->createFromArrival($validatedData);
            
            DB::commit();
            
            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Продукт успешно создан из поступления');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ошибка при создании продукта: ' . $e->getMessage());
        }
    }
    
    protected function storeRegular(array $validatedData)
    {
        try {
            DB::beginTransaction();
            
            // Создаем продукт со всеми данными, включая изображения
            $product = $this->productService->create($validatedData);
            
            // Привязываем экохарактеристики
            if (!empty($validatedData['eco_features'])) {
                $product->ecoFeatures()->sync($validatedData['eco_features']);
            }
            
            DB::commit();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'data' => new ProductResource($product)
                ], 201);
            }
            
            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Продукт успешно создан');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Ошибка создания продукта: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ошибка при создании продукта: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $this->categoryService->getAll(),
            'attributes' => $this->attributeService->getAll(),
            'ecoFeatures' => EcoFeature::all()
        ]);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load([
            'attributes',
            'images',
            'ecoFeatures',
            'reviews' => function($query) {
                $query->latest()->limit(5);
            }
        ]);

        // Кэшируем данные продукта на 1 час
        $cacheKey = "product_{$product->id}_details";
        $productData = cache()->remember($cacheKey, 3600, function() use ($product) {
            return new ProductResource($product);
        });

        return response()->json([
            'status' => 'success',
            'data' => $productData
        ]);
    }

    /**
     * Обновить эко-характеристики продукта
     */
    public function updateEcoFeatures(Request $request, Product $product): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Валидация входных данных
            $validated = $request->validate([
                'eco_score' => 'required|integer|min:0|max:100',
                'sustainability_info' => 'required|string|max:1000',
                'carbon_footprint' => 'required|numeric|min:0',
                'eco_features' => 'array',
                'eco_features.*' => 'exists:eco_features,id'
            ]);

            // Обновляем основные эко-показатели
            $product->update([
                'eco_score' => $validated['eco_score'],
                'sustainability_info' => $validated['sustainability_info'],
                'carbon_footprint' => $validated['carbon_footprint']
            ]);

            // Синхронизируем эко-характеристики
            if (isset($validated['eco_features'])) {
                $product->ecoFeatures()->sync($validated['eco_features']);
            }

            // Очищаем кэш продукта
            cache()->forget("product_{$product->id}_details");

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => new ProductResource($product->load('ecoFeatures'))
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(ProductRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();
            
            $validatedData = $request->validated();
            \Log::info('Update validated data:', $validatedData);
            
            // Очищаем кэш перед обновлением
            cache()->forget("product_{$product->id}_details");
            
            $result = $this->productService->update($product, $validatedData);
            \Log::info('Updated product:', $result->toArray());
            
            // Обновляем эко-характеристики, если они предоставлены
            if (isset($validatedData['eco_features'])) {
                $product->ecoFeatures()->sync($validatedData['eco_features']);
            }
            
            DB::commit();
            
            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Продукт успешно обновлен');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadImage(Request $request): JsonResponse
    {
        try {
            $image = $this->productImageService->upload($request->file('image'));
            
            return response()->json([
                'status' => 'success',
                'data' => $image
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadImages(Request $request): JsonResponse
    {
        try {
            $uploadedImages = [];
            
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $uploadedImage = $this->productImageService->upload($image);
                    $uploadedImages[] = [
                        'id' => $uploadedImage->id,
                        'url' => url('storage/' . $uploadedImage->image_path),
                        'order' => $uploadedImage->order
                    ];
                }
            }
            
            return response()->json($uploadedImages);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function deleteImage(ProductImage $image): JsonResponse
    {
        try {
            $this->productImageService->delete($image);
            
            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeDraft(ProductRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $product = $this->productService->createDraft($request->validated());
            
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'data' => new ProductResource($product)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateDraft(ProductRequest $request, Product $product): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $product = $this->productService->updateDraft($product, $request->validated());
            
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'data' => new ProductResource($product)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function publish(Product $product): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $product = $this->productService->publish($product);
            
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'data' => new ProductResource($product)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Удалить продукт.
     */
    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();
            
            // Удаляем изображения продукта
            foreach ($product->images as $image) {
                $this->productImageService->delete($image);
            }
            
            // Удаляем сам продукт
            $product->delete();
            
            DB::commit();
            
            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Продукт успешно удален');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Ошибка при удалении продукта: ' . $e->getMessage());
        }
    }
}