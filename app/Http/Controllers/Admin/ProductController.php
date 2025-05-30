<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\EcoFeature;
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

    public function index()
    {
        // Создаем подзапрос для выбора первого изображения по order
        $firstImageSubquery = \DB::table('product_images as pi1')
            ->select('pi1.image_path')
            ->whereColumn('pi1.product_id', 'products.id')
            ->orderBy('pi1.order')
            ->limit(1);

        $products = Product::select('products.*')
            ->selectSub($firstImageSubquery, 'image_url')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Отладочная информация
        foreach ($products as $product) {
            \Log::info("Product image data:", [
                'product_id' => $product->id,
                'image_url' => $product->image_url,
                'full_url' => $product->image_url ? asset($product->image_url) : null
            ]);
        }

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create', [
            'categories' => $this->categoryService->getAll(),
            'attributes' => $this->attributeService->getAll(),
            'ecoFeatures' => EcoFeature::all()
        ]);
    }

    public function store(ProductRequest $request)
    {
        try {
            DB::beginTransaction();
            
            // Удаляем изображения из validatedData, чтобы не передавать их в ProductService
            $validatedData = $request->validated();
            $images = $request->file('images');
            unset($validatedData['images']);

            // Создаем продукт
            $product = $this->productService->create($validatedData);
            
            // Привязываем экохарактеристики
            if (!empty($validatedData['eco_features'])) {
                $product->ecoFeatures()->sync($validatedData['eco_features']);
            }
            
            // Загружаем изображения
            if ($images) {
                \Log::info('Uploading product images:', [
                    'product_id' => $product->id,
                    'images_count' => count($images)
                ]);
                
                foreach ($images as $index => $imageFile) {
                    try {
                        // Создаем временный request с product_id
                        $currentRequest = request();
                        $currentRequest->merge(['product_id' => $product->id]);
                        
                        $image = $this->productImageService->upload($imageFile);
                        
                        \Log::info('Image uploaded successfully:', [
                            'product_id' => $product->id,
                            'image_id' => $image->id,
                            'image_url' => $image->url,
                            'order' => $image->order,
                            'index' => $index
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Ошибка загрузки изображения:', [
                            'product_id' => $product->id,
                            'index' => $index,
                            'error' => $e->getMessage(),
                            'file' => $e->getFile(),
                            'line' => $e->getLine()
                        ]);
                        continue;
                    }
                }
                
                // Проверяем успешность загрузки
                $product->load('images');
                \Log::info('Product images after upload:', [
                    'product_id' => $product->id,
                    'images_count' => $product->images->count(),
                    'first_image' => $product->images->first() ? [
                        'id' => $product->images->first()->id,
                        'url' => $product->images->first()->url,
                        'order' => $product->images->first()->order
                    ] : null
                ]);
            }
            
            DB::commit();
            
            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Продукт успешно создан');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Ошибка создания продукта: ' . $e->getMessage());
            
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

    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $validatedData = $request->validated();
            
            // Очищаем кэш перед обновлением
            cache()->forget("product_{$product->id}_details");
            
            $product = $this->productService->update($product, $validatedData);
            
            // Обновляем эко-характеристики, если они предоставлены
            if (isset($validatedData['eco_features'])) {
                $product->ecoFeatures()->sync($validatedData['eco_features']);
            }
            
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
}