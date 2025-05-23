@extends('admin.layouts.app')

@section('title', 'Добавление товара')

@section('styles')
@vite(['resources/css/components/spinner.css'])
@endsection

@section('scripts')
<script>
    window.closePreviewModal = () => {
        document.getElementById('previewModal').classList.add('hidden');
    };
</script>
@endsection

@section('content')
    <!-- Прелоадер -->
    <div id="preloader" class="fixed inset-0 bg-white dark:bg-gray-900 z-50 flex items-center justify-center">
        <div class="loading-state">
            <div class="spinner"></div>
            <div class="loading-state__text dark:text-gray-300">
                <div class="text-sm font-medium">Загрузка компонентов...</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Пожалуйста, подождите</div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <!-- Вкладки -->
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button type="button"
                    class="tab-button border-eco-500 text-eco-600 py-4 px-1 border-b-2 font-medium text-sm"
                    data-tab="basic">
                    Основное
                </button>
                <button type="button"
                    class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 border-b-2 font-medium text-sm"
                    data-tab="attributes">
                    Атрибуты
                </button>
                <button type="button"
                    class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 border-b-2 font-medium text-sm"
                    data-tab="images">
                    Изображения
                </button>
            </nav>
        </div>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Добавление товара</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Заполните информацию о новом товаре</p>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm(event)">
            @csrf
            
            <!-- Основная информация -->
            <div data-section="basic" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="required-field">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Название товара</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SKU (артикул)</label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Цена</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" name="price" id="price" min="0" step="0.01" value="{{ old('price') }}" required
                            class="block w-full pr-12 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">₽</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label for="sale_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Цена со скидкой (опционально)</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" name="sale_price" id="sale_price" min="0" step="0.01" value="{{ old('sale_price') }}"
                            class="block w-full pr-12 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">₽</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Количество на складе</label>
                    <input type="number" name="stock_quantity" id="stock_quantity" min="0" value="{{ old('stock_quantity', 0) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                </div>
            </div>
            
            <div class="mb-6">
                <label for="short_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Краткое описание</label>
                <textarea name="short_description" id="short_description" rows="2"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">{{ old('short_description') }}</textarea>
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Полное описание</label>
                <textarea name="description" id="description" rows="6" required
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">{{ old('description') }}</textarea>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Категории</label>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($categories as $category)
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                    class="rounded border-gray-300 dark:border-gray-600 text-eco-600 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $category->name }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Эко-характеристики</label>
                @foreach($ecoFeatures as $feature)
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $feature->name }}</label>
                        <input type="text" name="eco_features[{{ $feature->id }}]" value="{{ old('eco_features.' . $feature->id) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50"
                            placeholder="{{ $feature->description }}">
                    </div>
                @endforeach
            </div>
            
            <!-- Атрибуты -->
            <div data-section="attributes" class="hidden space-y-6">
                <div class="border rounded-lg p-4 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Атрибуты и варианты</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Выберите атрибуты для этого товара</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="attributes-container">
                        @foreach($attributes as $attribute)
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="product_attributes[]" value="{{ $attribute->id }}" 
                                        class="attribute-checkbox rounded border-gray-300 dark:border-gray-600 text-eco-600 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50"
                                        data-attribute-name="{{ $attribute->name }}">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $attribute->name }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div id="selected-attributes" class="space-y-4 mb-4">
                    <!-- Динамически заполняемый контейнер для выбранных атрибутов -->
                </div>
                
                <div id="variants-container" class="hidden space-y-4 border-t dark:border-gray-700 pt-4 mt-4">
                    <h4 class="text-md font-medium text-gray-900 dark:text-white mb-2">Варианты товара</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Комбинация</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">SKU</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Цена</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Цена со скидкой</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Количество</th>
                                </tr>
                            </thead>
                            <tbody id="variants-table-body" class="divide-y divide-gray-200 dark:divide-gray-700">
                                <!-- Динамически заполняемая таблица вариантов -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Изображения -->
            <div data-section="images" class="hidden space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Основное изображение</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md relative"
                         id="main-image-dropzone">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label for="main_image" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-eco-600 hover:text-eco-500 focus-within:outline-none">
                                    <span>Загрузить основное изображение</span>
                                    <input id="main_image" name="main_image" type="file" class="sr-only" accept="image/*">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Рекомендуемый размер: 1200x1200px. PNG, JPG до 2MB</p>
                        </div>
                        <!-- Индикатор загрузки -->
                        <div id="main-image-progress" class="hidden absolute inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
                            <div class="bg-white dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="spinner"></div>
                                    <span class="text-sm font-medium">Загрузка...</span>
                                </div>
                                <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                                    <div id="main-image-progress-bar" class="bg-eco-600 h-2.5 rounded-full w-0 transition-all"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Предпросмотр основного изображения -->
                    <div id="main-image-preview" class="hidden mt-4">
                        <div class="relative inline-block">
                            <img src="" alt="Предпросмотр" class="max-w-full h-48 object-cover rounded-lg">
                            <button type="button" onclick="window.imageHandler.removeMainImage()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Дополнительные изображения</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md relative"
                         id="additional-images-dropzone">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label for="additional_images" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-eco-600 hover:text-eco-500 focus-within:outline-none">
                                    <span>Загрузить дополнительные изображения</span>
                                    <input id="additional_images" name="additional_images[]" type="file" class="sr-only" multiple accept="image/*">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">До 5 изображений. PNG, JPG до 2MB каждое</p>
                        </div>
                        <!-- Индикатор загрузки -->
                        <div id="additional-images-progress" class="hidden absolute inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
                            <div class="bg-white dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="spinner"></div>
                                    <span class="text-sm font-medium">Загрузка...</span>
                                </div>
                                <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                                    <div id="additional-images-progress-bar" class="bg-eco-600 h-2.5 rounded-full w-0 transition-all"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Контейнер для предпросмотра дополнительных изображений -->
                    <div id="additional-images-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                </div>
            </div>
            
            <div data-section="basic" class="space-y-6">
                <div class="flex items-center">
                    <input id="is_featured" name="is_featured" type="checkbox" value="1" {{ old('is_featured') ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-eco-600 focus:ring-eco-500">
                    <label for="is_featured" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Отображать на главной</label>
                </div>
            </div>
            
            <div class="mb-6">
                <div class="flex items-center">
                    <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', 1) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-eco-600 focus:ring-eco-500">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Товар активен</label>
                </div>
            </div>
            
            <div class="mb-6">
                <div class="flex items-center">
                    <input id="is_new" name="is_new" type="checkbox" value="1" {{ old('is_new') ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-eco-600 focus:ring-eco-500">
                    <label for="is_new" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Пометить как новинку</label>
                </div>
            </div>
            
            <!-- Фиксированная панель действий -->
            <div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-4 z-40">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <button type="button" id="previewButton"
                            class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-medium py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Предпросмотр
                        </button>
                        
                        <button type="button" id="saveAsDraftButton"
                            class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-medium py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            Сохранить черновик
                        </button>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <span id="autoSaveStatus" class="text-sm text-gray-500 dark:text-gray-400 hidden">
                            Автосохранение...
                        </span>
                        <a href="{{ route('admin.products.index') }}"
                            class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-bold py-2 px-4 rounded">
                            Отмена
                        </a>
                        <button type="submit" class="bg-eco-600 hover:bg-eco-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Создать товар
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Модальное окно предпросмотра -->
    <div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="absolute inset-10 bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden flex flex-col">
            <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium">Предпросмотр товара</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closePreviewModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-auto p-4">
                <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div id="preview-gallery" class="splide">
                            <div class="splide__track">
                                <ul class="splide__list"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h2 id="preview-name" class="text-2xl font-bold"></h2>
                        <div id="preview-price" class="text-xl font-medium text-eco-600"></div>
                        <div id="preview-description" class="text-gray-600 dark:text-gray-300"></div>
                        <div id="preview-attributes" class="space-y-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
           