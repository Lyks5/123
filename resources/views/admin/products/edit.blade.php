@extends('admin.layouts.app')

@section('title', 'Редактирование товара')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Редактирование товара</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-gray">
            ← Назад к списку
        </a>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow p-6 space-y-6">
            <!-- Основная информация -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Название товара *</label>
                    <input type="text" 
                        name="name" 
                        value="{{ old('name', $product->name) }}"
                        required
                        class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Артикул (SKU) *</label>
                    <input type="text" 
                        name="sku" 
                        value="{{ old('sku', $product->sku) }}"
                        required
                        class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Цена *</label>
                    <input type="number" 
                        name="price" 
                        step="0.01"
                        value="{{ old('price', $product->price) }}"
                        required
                        class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Количество на складе *</label>
                    <input type="number" 
                        name="stock_quantity" 
                        value="{{ old('stock_quantity', $product->stock_quantity) }}"
                        min="0"
                        required
                        class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700">
                </div>
            </div>

            <!-- Категории -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Категории *</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($categories as $category)
                    <label class="flex items-center space-x-2 p-2 border rounded hover:bg-gray-50">
                        <input type="checkbox" 
                            name="categories[]" 
                            value="{{ $category->id }}"
                            {{ in_array($category->id, $product->categories->pluck('id')->toArray()) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-eco-700">
                        <span>{{ $category->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Изображения -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Изображения</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Текущие изображения -->
                    @foreach($product->images as $image)
                    <div class="relative group">
                        <img src="{{ asset('storage/'.$image->image_path) }}" 
                            alt="Изображение товара"
                            class="w-full h-40 object-cover rounded border">
                        
                        <div class="absolute inset-0 bg-black bg-opacity-50 hidden group-hover:flex items-center justify-center space-x-2">
                            <input type="checkbox" 
                                name="delete_images[]" 
                                value="{{ $image->id }}"
                                class="rounded border-gray-300 text-red-600">
                            <span class="text-white text-sm">Удалить</span>
                        </div>
                    </div>
                    @endforeach

                    <!-- Новые изображения -->
                    <div class="border-2 border-dashed rounded flex items-center justify-center h-40 cursor-pointer">
                        <input type="file" 
                            name="images[]" 
                            multiple 
                            accept="image/*"
                            class="hidden"
                            id="imageUpload">
                        <label for="imageUpload" class="text-gray-400 text-center p-4">
                            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs">Добавить фото</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Дополнительные настройки -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" 
                            name="is_active" 
                            value="1"
                            {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-eco-700">
                        <span class="text-sm text-gray-700">Активный товар</span>
                    </label>
                    
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" 
                            name="is_featured" 
                            value="1"
                            {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-eco-700">
                        <span class="text-sm text-gray-700">Рекомендуемый</span>
                    </label>
                    
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" 
                            name="is_new" 
                            value="1"
                            {{ old('is_new', $product->is_new) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-eco-700">
                        <span class="text-sm text-gray-700">Новинка</span>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Акционная цена</label>
                    <input type="number" 
                        name="sale_price" 
                        step="0.01"
                        value="{{ old('sale_price', $product->sale_price) }}"
                        class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Порядок сортировки</label>
                    <input type="number" 
                        name="sort_order" 
                        value="{{ old('sort_order', $product->sort_order) }}"
                        class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700">
                </div>
            </div>

            <!-- Описание -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
                <textarea name="description" 
                    rows="5"
                    class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700">{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- Кнопки -->
            <div class="border-t pt-6">
                <div class="flex justify-end space-x-3">
                    <button type="reset" class="btn btn-gray">Сбросить изменения</button>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Сохранить изменения
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Динамическое обновление превью изображений
document.getElementById('imageUpload').addEventListener('change', function(e) {
    const files = e.target.files
    const container = document.querySelector('.grid-cols-4')
    
    for(let file of files) {
        const reader = new FileReader()
        reader.onload = function(e) {
            const div = document.createElement('div')
            div.className = 'relative group'
            div.innerHTML = `
                <img src="${e.target.result}" 
                    class="w-full h-40 object-cover rounded border">
            `
            container.insertBefore(div, container.lastElementChild)
        }
        reader.readAsDataURL(file)
    }
})
</script>
@endpush