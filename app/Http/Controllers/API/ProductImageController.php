<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ProductImageService;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    protected $imageService;

    public function __construct(ProductImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function upload(Request $request)
    {
        try {
            $uploadedImages = [];
            
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $productImage = $this->imageService->upload($image);
                    $uploadedImages[] = [
                        'id' => $productImage->id,
                        'url' => $productImage->url,
                        'order' => $productImage->order
                    ];
                }
            }

            return response()->json($uploadedImages);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        }
    }
}