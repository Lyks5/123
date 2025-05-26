<?php

namespace App\Services;

use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductImageService
{
    public function upload(UploadedFile $file): ProductImage
    {
        // Генерируем уникальное имя файла
        $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        // Создаем изображение через intervention/image
        $image = Image::make($file);
        
        // Оптимизируем размер, сохраняя пропорции
        $image->resize(1200, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        // Сохраняем оригинал
        $path = 'products/' . $fileName;
        Storage::disk('public')->put($path, $image->encode());
        
        // Создаем запись в БД
        return ProductImage::create([
            'url' => Storage::url($path),
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize()
        ]);
    }

    public function delete(ProductImage $image): bool
    {
        // Удаляем файл
        $path = str_replace('/storage/', '', $image->url);
        Storage::disk('public')->delete($path);
        
        // Удаляем запись из БД
        return $image->delete();
    }

    public function getById(int $id): ProductImage
    {
        return ProductImage::findOrFail($id);
    }
}