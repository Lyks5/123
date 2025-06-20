@extends('admin.layouts.app')

@section('title', 'Добавление категории')

@section('content')
    <div class="bg-white shadow rounded-lg p-6 ">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Добавление категории</h1>
            <p class="text-gray-600 mt-1">Заполните информацию о новой категории</p>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @if ($errors->has('general'))
                <div class="mb-4 bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ $errors->first('general') }}
                </div>
            @endif

            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700">Название категории</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="parent_id" class="block text-sm font-medium text-gray-700">Родительская категория (опционально)</label>
                <select name="parent_id" id="parent_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 @error('parent_id') border-red-500 @enderror">
                    <option value="">Нет родительской категории</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700">Описание</label>
                <textarea name="description" id="description" rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-gray-700">Изображение категории</label>
                @error('image')
                    <p class="mb-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div class="mt-1 flex items-center">
                    <input type="file"
                           id="image"
                           name="image"
                           accept="image/*"
                           class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-eco-50 file:text-eco-700
                                  hover:file:bg-eco-100"
                           onchange="previewImage(this)">
                </div>
                <div id="image-preview" class="mt-2 hidden">
                    <img src="" alt="Предпросмотр" class="h-40 object-cover rounded">
                </div>
                <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF до 2MB</p>
            </div>
            
            <div class="mb-6">
                <div class="flex items-center">
                    <input id="is_active" name="is_active" type="hidden" value="0">
                    <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-eco-600 focus:ring-eco-500">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Категория активна</label>
                </div>
                @error('is_active')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end">
                <a href="{{ route('admin.categories.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                    Отмена
                </a>
                <button type="submit" class="bg-eco-600 hover:bg-eco-700 text-white font-bold py-2 px-4 rounded">
                    Создать категорию
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const img = preview.querySelector('img');
            const file = input.files[0];
            
            if (file) {
                // Проверка типа файла
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Пожалуйста, выберите изображение формата JPG, PNG или GIF');
                    input.value = '';
                    preview.classList.add('hidden');
                    return;
                }
                
                // Проверка размера файла (2MB = 2 * 1024 * 1024 bytes)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Размер файла не должен превышать 2MB');
                    input.value = '';
                    preview.classList.add('hidden');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        }
    </script>
@endsection