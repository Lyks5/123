<?php

namespace App\Services;

use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductImageService
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function upload(UploadedFile $file): ProductImage
    {
        // Генерируем уникальное имя файла
        $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        // Создаем изображение через intervention/image
        $image = $this->manager->read($file);
        
        // Оптимизируем размер, сохраняя пропорции
        $image->scale(width: 1200);
        
        // Сохраняем оригинал
        $path = 'products/' . $fileName;
        $success = Storage::disk('public')->put($path, $image->toJpeg());
        
        if (!$success) {
            \Log::error('Failed to save image file');
            throw new \Exception('Failed to save image file');
        }
        
        // Проверяем права доступа
        $fullPath = Storage::disk('public')->path($path);
        chmod($fullPath, 0644);
        
        \Log::info('Image file saved:', [
            'path' => $path,
            'full_disk_path' => $fullPath,
            'public_url' => Storage::disk('public')->url($path),
            'exists' => file_exists($fullPath),
            'permissions' => substr(sprintf('%o', fileperms($fullPath)), -4)
        ]);
        
        // Получаем максимальный order для текущего продукта
        $maxOrder = ProductImage::where('product_id', request()->input('product_id'))
            ->max('order');
            
        // Создаем запись в БД
        $image = ProductImage::create([
            'image_path' => $path, // Сохраняем относительный путь
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'product_id' => request()->input('product_id'),
            'order' => ($maxOrder ?? -1) + 1
        ]);
        
        \Log::info('Product image created:', [
            'image_id' => $image->id,
            'product_id' => $image->product_id,
            'image_path' => $image->image_path,
            'order' => $image->order
        ]);
        
        return $image;
    }

    public function delete(ProductImage $image): bool
    {
        // Удаляем файл
        $path = str_replace('/storage/', '', $image->image_path);
        Storage::disk('public')->delete($path);
        
        // Удаляем запись из БД
        return $image->delete();
    }

    public function getById(int $id): ProductImage
    {
        return ProductImage::findOrFail($id);
    }
}