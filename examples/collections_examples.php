<?php

use App\Models\Product;

/**
 * Примеры работы с коллекциями в Laravel
 */

/**
 * 1. Примеры отладки данных
 */
function demonstrateDebugging() {
    $products = Product::with('images')->limit(5)->get();
    
    // Пример проверки типа данных
    var_dump(get_class($products)); // Illuminate\Database\Eloquent\Collection
    var_dump($products instanceof \Illuminate\Support\Collection); // true
    
    // Пример вывода структуры коллекции
    print_r($products->toArray()); // Показывает массив данных
    
    // Примеры использования dump() и dd()
    dump($products); // Выводит детальную информацию и продолжает выполнение
    // dd($products); // Выводит информацию и прерывает выполнение
    
    // Отладка конкретных значений в коллекции
    dump($products->pluck('name')); // Только имена продуктов
}

/**
 * 2. Демонстрация типичной ошибки
 * Попытка обратиться к свойству коллекции как к свойству модели
 */
function demonstrateCommonMistake() {
    $products = Product::where('status', 1)->get(); // Возвращает Collection
    // Это вызовет ошибку, так как $products это коллекция, а не модель:
    // $imagePath = $products->image_path; // Property [image_path] does not exist on this collection instance.
}

/**
 * 3. Правильная проверка типа возвращаемого значения
 */
function demonstrateTypeChecking() {
    // Получение одной записи - возвращает Model или null
    $product = Product::where('status', 1)->first();
    if ($product instanceof Product) {
        $imagePath = $product->image_path;
    }

    // Получение коллекции - возвращает Collection
    $products = Product::where('status', 1)->get();
    if ($products->isNotEmpty()) {
        // Теперь мы точно знаем, что коллекция не пуста
    }
}

/**
 * 4. Безопасное получение значения у первого элемента
 */
function demonstrateSafeFirstAccess() {
    $products = Product::where('status', 1)->get();
    
    // Вариант 1: Проверка через first()
    $firstProduct = $products->first();
    $imagePath = $firstProduct ? $firstProduct->image_path : null;

    // Вариант 2: Цепочка методов с when()
    $imagePath = $products->first()?->image_path;
}

/**
 * 5. Правильная итерация по коллекции
 */
function demonstrateIteration() {
    $products = Product::where('status', 1)->get();

    // Простой foreach для обработки каждого элемента
    foreach ($products as $product) {
        echo $product->name;
    }

    // Использование each() для более элегантной обработки
    $products->each(function ($product) {
        echo $product->name;
    });
    
    // Использование map() для преобразования данных
    $transformed = $products->map(function ($product) {
        return [
            'name' => $product->name,
            'price_with_tax' => $product->price * 1.2
        ];
    });
}

/**
 * 6. Использование pluck() для получения массива значений
 */
function demonstratePluck() {
    $products = Product::where('status', 1)->get();

    // Получаем массив путей к изображениям
    $imagePaths = $products->pluck('image_path');

    // Получаем ассоциативный массив путей с ID в качестве ключей
    $imagePathsWithIds = $products->pluck('image_path', 'id');
}

/**
 * 7. Примеры обработки ошибок и безопасного доступа
 */
function demonstrateErrorHandling() {
    $products = Product::query()
        ->with('images')
        ->get();

    // Безопасное получение вложенных свойств
    $firstImagePath = $products->first()?->images?->first()?->path ?? 'default.jpg';

    // Проверка существования свойства
    if ($products->contains('status')) {
        // Работаем со статусом
    }

    // Безопасная цепочка методов с обработкой исключений
    try {
        $result = $products
            ->filter->isActive() // Вызовет ошибку если метод не существует
            ->sortBy('price')
            ->values();
    } catch (\Exception $e) {
        // Логируем ошибку
        \Log::error('Ошибка при обработке коллекции: ' . $e->getMessage());
        $result = collect(); // Возвращаем пустую коллекцию
    }
}

/**
 * 8. Расширенный пример с дополнительными проверками безопасности
 */
function demonstrateSafePractices() {
    $products = Product::query()
        ->where('status', 1)
        ->with('images') // Загружаем связанные изображения
        ->get();

    // Безопасная обработка коллекции с множеством проверок
    $processedData = $products
        ->filter(function ($product) {
            // Проверяем наличие необходимых данных
            return $product->images !== null && 
                   $product->price > 0 &&
                   !empty($product->name);
        })
        ->map(function ($product) {
            // Преобразуем данные безопасным способом
            return [
                'id' => $product->id,
                'name' => strip_tags($product->name), // Очищаем от HTML
                'image_path' => $product->images->first()?->path ?? 'default.jpg',
                'price' => (float) $product->price // Приводим к числу
            ];
        })
        ->values() // Переиндексируем массив
        ->all(); // Получаем обычный массив

    return $processedData;
}