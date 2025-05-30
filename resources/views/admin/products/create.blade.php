@extends('admin.layouts.app')

@push('styles')
    @vite(['resources/css/admin/products/form.css'])
@endpush

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">Создание продукта</h2>

                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                    @csrf
                    
                    <!-- Основная информация -->
                    <div class="space-y-6">
                        <div class="form-group">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Название продукта
                            </label>
                            <input type="text" name="name" id="name" required
                                class="form-input"
                                placeholder="Введите название продукта">
                            <span class="error-message" data-error="name"></span>
                        </div>

                        <div class="form-group">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Описание
                            </label>
                            <textarea name="description" id="description" rows="4" required
                                class="form-textarea"
                                placeholder="Введите описание продукта"></textarea>
                            <span class="error-message" data-error="description"></span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Артикул (SKU)
                                </label>
                                <input type="text" name="sku" id="sku" required
                                    class="form-input"
                                    placeholder="Введите артикул">
                                <span class="error-message" data-error="sku"></span>
                            </div>

                            <div class="form-group">
                                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Цена
                                </label>
                                <input type="number" name="price" id="price" required
                                    step="0.01" min="0"
                                    class="form-input"
                                    placeholder="0.00">
                                <span class="error-message" data-error="price"></span>
                            </div>

                            <div class="form-group">
                                <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Количество в наличии
                                </label>
                                <input type="number" name="quantity" id="quantity" required
                                    min="0"
                                    class="form-input"
                                    placeholder="0">
                                <span class="error-message" data-error="quantity"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Категория
                            </label>
                            <select name="category_id" id="category_id" required
                                class="form-select">
                                <option value="">Выберите категорию</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <span class="error-message" data-error="category_id"></span>
                        </div>

                        <div class="form-group">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Статус
                            </label>
                            <select name="status" id="status" required
                                class="form-select">
                                <option value="draft">Черновик</option>
                                <option value="published">Опубликован</option>
                                <option value="archived">Архивирован</option>
                            </select>
                            <span class="error-message" data-error="status"></span>
                        </div>

                        <div class="form-group">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_featured" id="is_featured"
                                    class="rounded border-gray-300 text-eco-600 shadow-sm focus:border-eco-500 focus:ring-eco-500">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                    Рекомендуемый товар
                                </span>
                            </label>
                        </div>

                        <!-- Атрибуты -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Атрибуты
                            </label>
                            <div class="space-y-4">
                                @foreach($attributes as $attribute)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $attribute->name }}
                                        </label>
                                        <select name="attributes[{{ $attribute->id }}]"
                                                class="form-select mt-1">
                                            <option value="">Выберите значение</option>
                                            @foreach($attribute->values as $value)
                                                <option value="{{ $value->id }}">
                                                    {{ $value->value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Экологические характеристики -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Экологические характеристики
                            </label>
                            <div class="space-y-2">
                                @foreach($ecoFeatures as $feature)
                                    <div class="mb-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <input type="checkbox"
                                                       name="eco_features[]"
                                                       value="{{ $feature->id }}"
                                                       id="eco_feature_{{ $feature->id }}"
                                                       class="rounded border-gray-300 text-eco-600 shadow-sm focus:border-eco-500 focus:ring-eco-500 eco-feature-checkbox"
                                                       data-feature-id="{{ $feature->id }}">
                                                <label for="eco_feature_{{ $feature->id }}" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $feature->name }}
                                                </label>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                ({{ $feature->unit }})
                                            </div>
                                        </div>
                                        <div class="mt-2 pl-6 hidden eco-feature-value" id="eco_value_{{ $feature->id }}">
                                            <div class="flex items-center space-x-2">
                                                <input type="number"
                                                       name="eco_feature_values[{{ $feature->id }}]"
                                                       class="form-input w-full sm:w-32"
                                                       placeholder="Значение"
                                                       min="0"
                                                       step="0.01">
                                                <span class="text-sm text-gray-600">{{ $feature->unit }}</span>
                                            </div>
                                            <div class="mt-1 text-sm text-green-600 savings-text" id="savings_{{ $feature->id }}"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <span class="error-message" data-error="eco_features"></span>
                        </div>

                        <!-- Загрузка изображений -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Изображения продукта
                            </label>
                            <div class="flex items-center justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-md"
                                id="dropZone">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-eco-600 hover:text-eco-500">
                                            <span>Загрузить файлы</span>
                                            <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*">
                                        </label>
                                        <p class="pl-1">или перетащите их сюда</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF до 10MB</p>
                                </div>
                            </div>
                            <div id="imagePreviewContainer" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                            </div>
                            <span class="error-message" data-error="images"></span>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            Отмена
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Создать продукт
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite([
        'resources/js/admin/products/create-form.js',
        'resources/js/admin/products/eco-features.js'
    ])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (!window.Alpine) {
                console.error('Alpine.js not loaded');
            }
        });

        // Передача информации о характеристиках в JavaScript
        window.ecoFeatures = {!! json_encode($ecoFeatures->pluck('slug', 'id')) !!};
    </script>
@endpush