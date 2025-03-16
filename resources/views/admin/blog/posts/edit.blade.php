@extends('admin.layouts.app')

@section('title', 'Редактирование записи блога')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Редактирование записи блога</h1>
            <p class="text-gray-600 mt-1">Отредактируйте информацию о записи блога</p>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('admin.blog.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">Основная информация</h2>
                        
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Заголовок</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug (URL)</label>
                            <div class="flex">
                                <input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                <button type="button" onclick="generateSlug()" class="ml-2 px-3 py-2 bg-gray-200 text-gray-700 rounded-md">
                                    Создать
                                </button>
                            </div>
                            @error('slug')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="excerpt" class="block text-sm font-medium text-gray-700">Краткое описание</label>
                            <textarea name="excerpt" id="excerpt" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">{{ old('excerpt', $post->excerpt) }}</textarea>
                            @error('excerpt')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700">Содержание</label>
                            <textarea name="content" id="content" rows="15" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- SEO Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">SEO информация</h2>
                        
                        <div class="mb-4">
                            <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta заголовок</label>
                            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $post->meta_title) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                            @error('meta_title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta описание</label>
                            <textarea name="meta_description" id="meta_description" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">{{ old('meta_description', $post->meta_description) }}</textarea>
                            @error('meta_description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <!-- Publication Settings -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">Настройки публикации</h2>
                        
                        <div class="mb-4">
                            <label for="author_id" class="block text-sm font-medium text-gray-700">Автор</label>
                            <select name="author_id" id="author_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" {{ old('author_id', $post->author_id) == $author->id ? 'selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('author_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="published_at" class="block text-sm font-medium text-gray-700">Дата публикации</label>
                            <input type="datetime-local" name="published_at" id="published_at" 
                                value="{{ old('published_at', $post->published_at ? date('Y-m-d\TH:i', strtotime($post->published_at)) : '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                            @error('published_at')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Статус</label>
                            <select name="status" id="status" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Черновик</option>
                                <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Опубликовано</option>
                                <option value="scheduled" {{ old('status', $post->status) == 'scheduled' ? 'selected' : '' }}>Запланировано</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input id="featured" name="featured" type="checkbox" value="1" {{ old('featured', $post->featured) ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-gray-300 text-eco-600 focus:ring-eco-500">
                                <label for="featured" class="ml-2 block text-sm text-gray-700">Отображать в рекомендуемых</label>
                            </div>
                            @error('featured')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Categories and Tags -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">Категории и теги</h2>
                        
                        <div class="mb-4">
                            <label for="categories" class="block text-sm font-medium text-gray-700 mb-2">Категории</label>
                            <div class="max-h-48 overflow-y-auto p-2 border border-gray-300 rounded-md">
                                @foreach($categories as $category)
                                    <div class="flex items-center mb-2">
                                        <input 
                                            type="checkbox" 
                                            id="category_{{ $category->id }}" 
                                            name="categories[]" 
                                            value="{{ $category->id }}"
                                            {{ in_array($category->id, old('categories', $post->categories->pluck('id')->toArray())) ? 'checked' : '' }} 
                                            class="h-4 w-4 rounded border-gray-300 text-eco-600 focus:ring-eco-500"
                                        >
                                        <label for="category_{{ $category->id }}" class="ml-2 block text-sm text-gray-700">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('categories')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="tags" class="block text-sm font-medium text-gray-700">Теги (разделенные запятыми)</label>
                            <input type="text" name="tags" id="tags" 
                                value="{{ old('tags', $post->tags->pluck('name')->join(', ')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">Например: эко, спорт, йога</p>
                            @error('tags')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Featured Image -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">Изображение</h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Главное изображение</label>
                            
                            @if($post->image)
                                <div class="mb-3">
                                    <div class="w-full aspect-video overflow-hidden rounded-lg">
                                        <img src="{{ $post->image }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex items-center mt-2">
                                        <input type="checkbox" name="remove_image" id="remove_image" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <label for="remove_image" class="ml-2 text-sm text-red-600">Удалить текущее изображение</label>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-eco-600 hover:text-eco-500 focus-within:outline-none">
                                            <span>Загрузить новое изображение</span>
                                            <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">или перетащите файл сюда</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF до 5MB</p>
                                </div>
                            </div>
                            <div id="image-preview" class="mt-2 hidden">
                                <img src="" alt="Предпросмотр" class="h-40 object-cover rounded">
                            </div>
                            @error('image')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end mt-6">
                <a href="{{ route('admin.blog.posts.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                    Отмена
                </a>
                <button type="submit" class="bg-eco-600 hover:bg-eco-700 text-white font-bold py-2 px-4 rounded">
                    Сохранить
                </button>
            </div>
        </form>
    </div>

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        // Инициализация TinyMCE
        tinymce.init({
            selector: '#content',
            height: 500,
            menubar: true,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | bold italic backcolor | \
                     alignleft aligncenter alignright alignjustify | \
                     bullist numlist outdent indent | removeformat | help'
        });
        
        // Предпросмотр изображения
        document.getElementById('image').addEventListener('change', function(event) {
            const preview = document.getElementById('image-preview');
            const img = preview.querySelector('img') || document.createElement('img');
            img.className = 'h-40 object-cover rounded';
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    if (!preview.querySelector('img')) {
                        preview.appendChild(img);
                    }
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        });
        
        // Генерация slug
        function generateSlug() {
            const title = document.getElementById('title').value;
            const slug = title
                .toLowerCase()
                .replace(/[^\w\s-]/g, '') // Удаление спецсимволов
                .replace(/\s+/g, '-')     // Замена пробелов на дефисы
                .replace(/--+/g, '-')     // Замена множественных дефисов на один
                .trim();                  // Удаление пробелов по краям
                
            document.getElementById('slug').value = slug;
        }
        
        // Автоматическая генерация slug при вводе заголовка
        document.getElementById('title').addEventListener('blur', function() {
            if (document.getElementById('slug').value === '') {
                generateSlug();
            }
        });
    </script>
@endsection