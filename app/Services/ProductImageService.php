<?php

namespace App\Services;

use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Config;

class ProductImageService
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(Driver::class);
    }

    public function upload(UploadedFile $file): ProductImage
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();
        
        // Проверяем MIME-тип файла
        $allowedMimeTypes = Config::get('images.products.mime_types', []);
        if (!in_array($mimeType, $allowedMimeTypes)) {
            throw new \Exception('Неподдерживаемый тип файла. Разрешены: JPG, PNG, WebP');
        }

        // Проверяем размер файла
        $maxSize = Config::get('images.products.max_size', 5120); // 5MB по умолчанию
        if ($file->getSize() > $maxSize * 1024) {
            throw new \Exception("Файл слишком большой. Максимальный размер: {$maxSize}KB");
        }

        // Генерируем уникальное имя файла
        $fileName = uniqid() . '_' . time();
        
        try {
            // Создаем изображение через intervention/image
            $image = $this->manager->read($file);
            
            // Оптимизируем размер
            $maxWidth = Config::get('images.products.max_width', 1200);
            $quality = Config::get('images.products.quality', 80);
            
            if ($image->width() > $maxWidth) {
                $image->scale(width: $maxWidth);
            }
            
            // Определяем пути для сохранения
            $originalPath = "products/{$fileName}.{$extension}";
            $webpPath = "products/{$fileName}.webp";

            // Сохраняем оригинал
            if ($extension === 'webp') {
                $success = Storage::disk('public')->put(
                    $originalPath, 
                    $image->toWebp($quality)
                );
            } else {
                // Для JPG и PNG сохраняем оригинал и создаем WebP версию
                if ($extension === 'png') {
                    $success = Storage::disk('public')->put(
                        $originalPath,
                        $image->toPng(false) // PNG interlaced parameter should be boolean
                    );
                } else {
                    $success = Storage::disk('public')->put(
                        $originalPath, 
                        $image->toJpeg($quality)
                    );
                }

                // Создаем WebP версию
                Storage::disk('public')->put(
                    $webpPath, 
                    $image->toWebp($quality)
                );
            }
            
            if (!$success) {
                throw new \Exception('Не удалось сохранить файл изображения');
            }

            // Устанавливаем права доступа
            chmod(Storage::disk('public')->path($originalPath), 0644);
            if ($extension !== 'webp') {
                chmod(Storage::disk('public')->path($webpPath), 0644);
            }

            // Логируем информацию о сохраненных файлах
            \Log::info('Image files saved:', [
                'original' => [
                    'path' => $originalPath,
                    'url' => Storage::disk('public')->url($originalPath),
                    'size' => Storage::disk('public')->size($originalPath),
                    'mime' => $mimeType
                ],
                'webp' => $extension !== 'webp' ? [
                    'path' => $webpPath,
                    'url' => Storage::disk('public')->url($webpPath),
                    'size' => Storage::disk('public')->size($webpPath)
                ] : null
            ]);

            // Получаем product_id из request или переданных параметров
            $productId = request()->input('product_id');
            
            if (!$productId) {
                throw new \InvalidArgumentException('Product ID is required for image upload');
            }

            // Получаем максимальный order
            $maxOrder = ProductImage::where('product_id', $productId)
                ->max('order') ?? -1;

            // Создаем запись в БД
            $productImage = ProductImage::create([
                'image_path' => $originalPath,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $mimeType,
                'size' => $file->getSize(),
                'product_id' => $productId,
                'order' => $maxOrder + 1
            ]);

            return $productImage;

        } catch (\Exception $e) {
            \Log::error('Error processing image:', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'mime' => $mimeType
            ]);
            throw new \Exception('Ошибка при обработке изображения: ' . $e->getMessage());
        }
    }

    public function delete(ProductImage $image): bool
    {
        try {
            // Получаем информацию о файле
            $pathInfo = pathinfo($image->image_path);
            $baseDir = $pathInfo['dirname'];
            $fileName = $pathInfo['filename'];
            
            // Удаляем все версии файла
            Storage::disk('public')->delete([
                "{$baseDir}/{$fileName}.jpg",
                "{$baseDir}/{$fileName}.jpeg",
                "{$baseDir}/{$fileName}.png",
                "{$baseDir}/{$fileName}.webp"
            ]);
            
            return $image->delete();
        } catch (\Exception $e) {
            \Log::error('Error deleting image:', [
                'image_id' => $image->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function getById(int $id): ProductImage
    {
        return ProductImage::findOrFail($id);
    }
}