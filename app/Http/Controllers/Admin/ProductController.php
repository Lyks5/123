<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
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
        $products = Product::with('primaryImage')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create', [
            'categories' => $this->categoryService->getAll(),
            'attributes' => $this->attributeService->getAll()
        ]);
    }

    public function store(ProductRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $validatedData = $request->validated();
            \Log::info('Creating product with data:', [
                'category_id' => [
                    'value' => $validatedData['category_id'],
                    'type' => gettype($validatedData['category_id'])
                ],
                'attributes' => $validatedData['attributes'] ?? [],
                'all_data' => $validatedData
            ]);
            
            $product = $this->productService->create($validatedData);
            
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

    public function show(Product $product): JsonResponse
    {
        $product->load(['attributes', 'images', 'category']);
        return response()->json([
            'status' => 'success',
            'data' => new ProductResource($product)
        ]);
    }

    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $validatedData = $request->validated();
            \Log::info('Updating product with data:', [
                'category_id' => [
                    'value' => $validatedData['category_id'],
                    'type' => gettype($validatedData['category_id'])
                ],
                'attributes' => $validatedData['attributes'] ?? [],
                'all_data' => $validatedData
            ]);
            
            $product = $this->productService->update($product, $validatedData);
            
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