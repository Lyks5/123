@extends('admin.layouts.app')

@section('title', 'Редактирование категории')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Редактирование категории</h1>
            <p class="text-gray-600 mt-1">{{ $category->name }}</p>
        </div>

        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700">Название категории</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
            </div>
            
            <div class="mb-6">
                <label for="parent_id" class="block text-sm font-medium text-gray-700">Родительская категория (опционально)</label>
                <select name="parent_id" id="parent_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                    <option value="">Нет родительской категории</option>
                    @foreach($categories as $parentCategory)
                        <option value="{{ $parentCategory->id }}" {{ (old('parent_id', $category->parent_id) == $parentCategory->id) ? 'selected' : '' }}>
                            {{ $parentCategory->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700">Описание</label>
                <textarea name="description" id="description" rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">{{ old('description', $category->description) }}</textarea>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Текущее изображение</label>
                @if($category->image)
                    <div class="mt-2 mb-4">
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="h-40 object-cover rounded">
                    </div>
                @else
                    <div class="text-gray-500 mb-4">У категории нет изображения.</div>
                @endif
                
                <label class="block text-sm font-medium text-gray-700 mt-4">Загрузить новое изображение</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-eco-600 hover:text-eco-500 focus-within:outline-none">
                                <span>Загрузить изображение</span>
                                <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                            </label>
                            <p class="pl-1">или перетащите файл сюда</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF до 2MB</p>
                    </div>
                </div>
                <div id="image-preview" class="mt-2 hidden">
                    <img src="" alt="Предпросмотр" class="h-40 object-cover rounded">
                </div>
            </div>
            
            <div class="mb-6">
                <div class="flex items-center">
                    <input id="status" name="status" type="checkbox" value="active" {{ old('status', $category->status) === 'active' ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-eco-600 focus:ring-eco-500">
                    <label for="status" class="ml-2 block text-sm text-gray-700">Категория активна</label>
                </div>
            </div>
            
            <div class="flex justify-end">
                <a href="{{ route('admin.categories.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                    Отмена
                </a>
                <button type="submit" class="bg-eco-600 hover:bg-eco-700 text-white font-bold py-2 px-4 rounded">
                    Сохранить изменения
                </button>
            </div>
        </form>
    </div>

    <script>
        // Предпросмотр изображения
        document.getElementById('image').addEventListener('change', function(event) {
            const preview = document.getElementById('image-preview');
            const img = preview.querySelector('img');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        });
    </script>
@endsection