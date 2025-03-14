
@extends('admin.layouts.app')

@section('title', 'Добавить товар')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Добавить новый товар</h1>
        
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="bg-white rounded-lg shadow p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Название товара</label>
                    <input type="text" name="name" required 
                        class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Категории</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach($categories as $category)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                class="rounded border-gray-300 text-eco-700">
                            <span>{{ $category->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Цена</label>
                        <input type="number" name="price" step="0.01" required
                            class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Акционная цена</label>
                        <input type="number" name="sale_price" step="0.01"
                            class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- SKU -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Артикул (SKU) *</label>
        <input type="text" 
            name="sku" 
            required
            class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700"
            placeholder="ECO-123456">
    </div>

    <!-- Количество на складе -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Количество на складе *</label>
        <input type="number" 
            name="stock_quantity" 
            required
            min="0"
            class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700"
            placeholder="100">
    </div>
</div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Изображения</label>
                    <input type="file" name="images[]" multiple accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-eco-50 file:text-eco-700 hover:file:bg-eco-100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
                    <textarea name="description" rows="4" 
                        class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700"></textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-gray">Отмена</a>
                    <button type="submit" class="btn btn-primary">Сохранить товар</button>
                </div>
            </div>
        </form>
    </div>
@endsection