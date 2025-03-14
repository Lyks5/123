@extends('admin.layouts.app')

@section('title', 'Создание категории')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Новая категория</h1>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-gray">
            ← Назад к списку
        </a>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
        @csrf

        <div class="space-y-6">
            <!-- Название -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Название категории *
                </label>
                <input type="text" 
                    name="name" 
                    value="{{ old('name') }}"
                    required
                    class="w-full rounded-md border-gray-300 focus:border-eco-700 focus:ring-eco-700"
                    placeholder="Эко-товары">
            </div>

            <!-- URL-адрес -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    URL-адрес (slug) *
                </label>
                <input type="text" 
                    name="slug" 
                    value="{{ old('slug') }}"
                    required
                    class="w-full rounded-md border-gray-300 focus:border-eco-700 focus:ring-eco-700"
                    placeholder="eco-products">
                <p class="mt-2 text-sm text-gray-500">
                    Уникальный идентификатор для URL-адреса
                </p>
            </div>

            <!-- Родительская категория -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Родительская категория
                </label>
                <select name="parent_id" 
                    class="w-full rounded-md border-gray-300 focus:border-eco-700 focus:ring-eco-700">
                    <option value="">Без родительской категории</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Изображение -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Изображение категории
                </label>
                <div class="flex items-center space-x-4">
                    <div class="relative w-32 h-32 bg-gray-100 rounded-md overflow-hidden border-2 border-dashed border-gray-300">
                        <input type="file" 
                            name="image" 
                            id="imageInput"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs">Загрузить фото</span>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        Рекомендуемый размер: 500×500px<br>
                        Форматы: JPG, PNG, SVG
                    </div>
                </div>
            </div>

            <!-- Описание -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Описание категории
                </label>
                <textarea name="description" 
                    rows="4"
                    class="w-full rounded-md border-gray-300 focus:border-eco-700 focus:ring-eco-700"
                    placeholder="Краткое описание категории">{{ old('description') }}</textarea>
            </div>

            <!-- Статус -->
            <div>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" 
                        name="is_active" 
                        value="1"
                        {{ old('is_active', 1) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-eco-700 focus:ring-eco-700">
                    <span class="text-sm text-gray-700">Активная категория</span>
                </label>
            </div>

            <!-- Кнопки -->
            <div class="border-t pt-6">
                <div class="flex justify-end space-x-3">
                    <button type="reset" class="btn btn-gray">
                        Очистить форму
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        Сохранить категорию
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('imageInput').addEventListener('change', function(e) {
    const [file] = e.target.files
    if (file) {
        const reader = new FileReader()
        reader.onload = function(e) {
            const preview = document.createElement('img')
            preview.className = 'absolute inset-0 w-full h-full object-cover'
            preview.src = e.target.result
            document.querySelector('#imageInput').parentNode.appendChild(preview)
        }
        reader.readAsDataURL(file)
    }
})
</script>
@endpush
@endsection