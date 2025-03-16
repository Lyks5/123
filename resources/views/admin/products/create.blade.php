@extends('admin.layouts.app')

@section('title', 'Добавление товара')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Добавление товара</h1>
            <p class="text-gray-600 mt-1">Заполните информацию о новом товаре</p>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Название товара</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700">SKU (артикул)</label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Цена</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" name="price" id="price" min="0" step="0.01" value="{{ old('price') }}" required
                            class="block w-full pr-12 rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">₽</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label for="sale_price" class="block text-sm font-medium text-gray-700">Цена со скидкой (опционально)</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" name="sale_price" id="sale_price" min="0" step="0.01" value="{{ old('sale_price') }}"
                            class="block w-full pr-12 rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">₽</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Количество на складе</label>
                    <input type="number" name="stock_quantity" id="stock_quantity" min="0" value="{{ old('stock_quantity', 0) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                </div>
            </div>
            
            <div class="mb-6">
                <label for="short_description" class="block text-sm font-medium text-gray-700">Краткое описание</label>
                <textarea name="short_description" id="short_description" rows="2"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">{{ old('short_description') }}</textarea>
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700">Полное описание</label>
                <textarea name="description" id="description" rows="6" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">{{ old('description') }}</textarea>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Категории</label>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($categories as $category)
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                    class="rounded border-gray-300 text-eco-600 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                <span class="ml-2">{{ $category->name }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Эко-характеристики</label>
                @foreach($ecoFeatures as $feature)
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $feature->name }}</label>
                        <input type="text" name="eco_features[{{ $feature->id }}]" value="{{ old('eco_features.' . $feature->id) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50"
                            placeholder="{{ $feature->description }}">
                    </div>
                @endforeach
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Изображения товара</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-eco-600 hover:text-eco-500 focus-within:outline-none">
                                <span>Загрузить изображения</span>
                                <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*">
                            </label>
                            <p class="pl-1">или перетащите файлы сюда</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF до 2MB</p>
                    </div>
                </div>
                <div class="mt-2">
                    <input type="hidden" name="primary_image" id="primary_image" value="">
                    <div id="preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2"></div>
                </div>
            </div>
            
            <div class="mb-6">
                <div class="flex items-center">
                    <input id="is_featured" name="is_featured" type="checkbox" value="1" {{ old('is_featured') ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-eco-600 focus:ring-eco-500">
                    <label for="is_featured" class="ml-2 block text-sm text-gray-700">Отображать на главной</label>
                </div>
            </div>
            
            <div class="mb-6">
                <div class="flex items-center">
                    <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', 1) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-eco-600 focus:ring-eco-500">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Товар активен</label>
                </div>
            </div>
            
            <div class="mb-6">
                <div class="flex items-center">
                    <input id="is_new" name="is_new" type="checkbox" value="1" {{ old('is_new') ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-eco-600 focus:ring-eco-500">
                    <label for="is_new" class="ml-2 block text-sm text-gray-700">Пометить как новинку</label>
                </div>
            </div>
            
            <div class="flex justify-end">
                <a href="{{ route('admin.products.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                    Отмена
                </a>
                <button type="submit" class="bg-eco-600 hover:bg-eco-700 text-white font-bold py-2 px-4 rounded">
                    Создать товар
                </button>
            </div>
        </form>
    </div>

    <script>
        // Предпросмотр изображений
        document.getElementById('images').addEventListener('change', function(event) {
            const previewContainer = document.getElementById('preview-container');
            previewContainer.innerHTML = '';
            
            Array.from(event.target.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-32 object-cover rounded';
                    div.appendChild(img);
                    
                    const radioDiv = document.createElement('div');
                    radioDiv.className = 'absolute bottom-2 right-2 bg-white p-1 rounded-full shadow';
                    
                    const radioInput = document.createElement('input');
                    radioInput.type = 'radio';
                    radioInput.name = 'primary_image_selector';
                    radioInput.value = index;
                    radioInput.className = 'h-4 w-4 text-eco-600 focus:ring-eco-500';
                    radioInput.addEventListener('change', function() {
                        document.getElementById('primary_image').value = this.value;
                    });
                    
                    if (index === 0) {
                        radioInput.checked = true;
                        document.getElementById('primary_image').value = 0;
                    }
                    
                    radioDiv.appendChild(radioInput);
                    div.appendChild(radioDiv);
                    
                    previewContainer.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        });
    </script>
@endsection