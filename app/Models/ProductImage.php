<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    protected $fillable = [
        'image_path',
        'original_name',
        'mime_type',
        'size',
        'order',
        'product_id'
    ];

    protected $appends = ['url'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute()
    {
        if (empty($this->image_path)) {
            \Log::warning("Empty image_path for ProductImage ID: " . $this->id);
            return null;
        }

        // Убираем лишние слеши в начале пути
        $path = ltrim($this->image_path, '/');
        
        // Проверяем, существует ли файл
        $storagePath = storage_path('app/public/' . $path);
        
        if (!file_exists($storagePath)) {
            \Log::warning("Image file not found: {$storagePath} for ProductImage ID: " . $this->id);
            return null;
        }

        // Возвращаем публичный URL с правильным префиксом
        $url = asset('storage/' . $path);
        \Log::debug("Generated URL for ProductImage ID: " . $this->id . ", URL: " . $url);
        return $url;
    }

    public function setImagePathAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['image_path'] = null;
            return;
        }

        // Убираем storage из пути если он есть
        $path = str_replace(['/storage/', 'storage/'], '', $value);
        
        // Убираем лишние слеши
        $path = ltrim($path, '/');
        
        $this->attributes['image_path'] = $path;
    }
}
