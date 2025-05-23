<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Exception;

class ProductImageService
{
    private const SMALL_WIDTH = 150;
    private const MEDIUM_WIDTH = 400;
    private const LARGE_WIDTH = 800;
    
    private $basePath = 'public/products';
    
    /**
     * Сохраняет основное изображение продукта
     */
    public function saveMainImage($image)
    {
        try {
            $mainImage = $this->processImage($image);
            return [
                'main_image' => $mainImage['original'],
                'main_image_small' => $mainImage['small'],
                'main_image_medium' => $mainImage['medium'],
                'main_image_large' => $mainImage['large']
            ];
        } catch (Exception $e) {
            throw new Exception('Ошибка при сохранении основного изображения: ' . $e->getMessage());
        }
    }
    
    /**
     * Сохраняет дополнительные изображения продукта
     */
    public function saveAdditionalImages($images)
    {
        try {
            $processedImages = [];
            foreach ($images as $image) {
                $processed = $this->processImage($image);
                $processedImages[] = $processed;
            }
            return $processedImages;
        } catch (Exception $e) {
            throw new Exception('Ошибка при сохранении дополнительных изображений: ' . $e->getMessage());
        }
    }
    
    /**
     * Обрабатывает изображение: сохраняет оригинал, создает миниатюры и оптимизирует
     */
    private function processImage($image)
    {
        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
        $paths = [];

        // Сохраняем оригинальное изображение
        $originalPath = $image->storeAs($this->basePath . '/original', $filename);
        $paths['original'] = Storage::url($originalPath);
        
        // Создаем и сохраняем миниатюры
        $img = Image::make($image);
        
        // Маленькая миниатюра
        $smallThumb = $this->createThumbnail($img, self::SMALL_WIDTH);
        $smallPath = $this->basePath . '/small/' . $filename;
        Storage::put($smallPath, $smallThumb->encode());
        $paths['small'] = Storage::url($smallPath);
        
        // Средняя миниатюра
        $mediumThumb = $this->createThumbnail($img, self::MEDIUM_WIDTH);
        $mediumPath = $this->basePath . '/medium/' . $filename;
        Storage::put($mediumPath, $mediumThumb->encode());
        $paths['medium'] = Storage::url($mediumPath);
        
        // Большая миниатюра
        $largeThumb = $this->createThumbnail($img, self::LARGE_WIDTH);
        $largePath = $this->basePath . '/large/' . $filename;
        Storage::put($largePath, $largeThumb->encode());
        $paths['large'] = Storage::url($largePath);
        
        return $paths;
    }
    
    /**
     * Создает миниатюру указанной ширины с сохранением пропорций
     */
    private function createThumbnail($image, $width)
    {
        return $image->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode(null, 80); // Оптимизация качества
    }
    
    /**
     * Удаляет изображение и все его миниатюры
     */
    public function deleteImage($imagePath)
    {
        try {
            $sizes = ['original', 'small', 'medium', 'large'];
            foreach ($sizes as $size) {
                $path = str_replace('/storage/', '', $imagePath);
                $path = str_replace('/original/', "/$size/", $path);
                Storage::delete('public/' . $path);
            }
            return true;
        } catch (Exception $e) {
            throw new Exception('Ошибка при удалении изображения: ' . $e->getMessage());
        }
    }
}