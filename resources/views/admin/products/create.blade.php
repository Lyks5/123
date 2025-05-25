@extends('admin.layouts.app')

@section('title', 'Добавление товара')

@push('styles')
    @vite('resources/css/admin/products/form.css')
@endpush

@section('content')
    <div class="product-form-container">
        <div class="mb-6">
            <h1 class="section-title">Добавление нового товара</h1>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-grid-2">
                <div>
                    <label for="name" class="form-label">Название товара</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="form-input">
                </div>
                
                <div>
                    <label for="sku" class="form-label">SKU (артикул)</label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku') }}" required class="form-input">
                </div>
            </div>
            
            <div class="form-grid-3">
                <div>
                    <label for="price" class="form-label">Цена</label>
                    <div class="price-input-group">
                        <input type="number" name="price" id="price" min="0" step="0.01" value="{{ old('price') }}" required>
                        <div class="currency">₽</div>
                    </div>
                </div>
                
                <div>
                    <label for="sale_price" class="form-label">Цена со скидкой (опционально)</label>
                    <div class="price-input-group">
                        <input type="number" name="sale_price" id="sale_price" min="0" step="0.01" value="{{ old('sale_price') }}">
                        <div class="currency">₽</div>
                    </div>
                </div>
                
                <div>
                    <label for="stock_quantity" class="form-label">Количество на складе</label>
                    <input type="number" name="stock_quantity" id="stock_quantity" min="0" value="{{ old('stock_quantity', 0) }}" required class="form-input">
                </div>
            </div>
            
            <div class="mb-6">
                <label for="short_description" class="form-label">Краткое описание</label>
                <textarea name="short_description" id="short_description" rows="2" class="form-textarea">{{ old('short_description') }}</textarea>
            </div>
            
            <div class="mb-6">
                <label for="description" class="form-label">Полное описание</label>
                <textarea name="description" id="description" rows="6" required class="form-textarea">{{ old('description') }}</textarea>
            </div>
            
            <div class="mb-6">
                <label class="form-label mb-2">Категории</label>
                <div class="attributes-grid">
                    @foreach($categories as $category)
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                    {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
                                    class="attribute-checkbox">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $category->name }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="mb-6">
                <label class="form-label mb-2">Эко-характеристики</label>
                @foreach($ecoFeatures as $feature)
                    <div class="mb-3">
                        <label class="form-label mb-1">{{ $feature->name }}</label>
                        <input type="text" name="eco_features[{{ $feature->id }}]" 
                            value="{{ old('eco_features.' . $feature->id) }}"
                            class="form-input"
                            placeholder="{{ $feature->description }}">
                    </div>
                @endforeach
            </div>
            
            <div class="mb-6">
                <label class="form-label mb-2">Загрузить изображения</label>
                <div class="image-upload-container">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                            <label for="images" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-eco-600 hover:text-eco-500 focus-within:outline-none">
                                <span>Загрузить изображения</span>
                                <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*">
                            </label>
                            <p class="pl-1">или перетащите файлы сюда</p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF до 2MB</p>
                    </div>
                </div>
                <div class="mt-2">
                    <input type="hidden" name="primary_image" id="primary_image" value="">
                    <div id="preview-container" class="preview-container"></div>
                </div>
            </div>
            
            <!-- Attributes Section -->
            <div class="attributes-container">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Атрибуты и варианты</h3>
                
                <div class="mb-4">
                    <label class="form-label mb-2">Выберите атрибуты для этого товара</label>
                    <div class="attributes-grid" id="attributes-container">
                        @foreach($attributes as $attribute)
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="product_attributes[]" value="{{ $attribute->id }}" 
                                        class="attribute-checkbox"
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
                        <table class="variants-table">
                            <thead>
                                <tr>
                                    <th>Комбинация</th>
                                    <th>SKU</th>
                                    <th>Цена</th>
                                    <th>Цена со скидкой</th>
                                    <th>Количество</th>
                                </tr>
                            </thead>
                            <tbody id="variants-table-body">
                                <!-- Варианты будут добавлены динамически -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mb-6">
                <div class="checkbox-group">
                    <input id="is_featured" name="is_featured" type="checkbox" value="1" {{ old('is_featured') ? 'checked' : '' }} class="checkbox-input">
                    <label for="is_featured" class="checkbox-label">Отображать на главной</label>
                </div>
            </div>
            
            <div class="mb-6">
                <div class="checkbox-group">
                    <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="checkbox-input">
                    <label for="is_active" class="checkbox-label">Товар активен</label>
                </div>
            </div>
            
            <div class="mb-6">
                <div class="checkbox-group">
                    <input id="is_new" name="is_new" type="checkbox" value="1" {{ old('is_new') ? 'checked' : '' }} class="checkbox-input">
                    <label for="is_new" class="checkbox-label">Пометить как новинку</label>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Отмена</a>
                <button type="submit" name="action" value="save" class="btn btn-primary">Сохранить</button>
                <button type="submit" name="action" value="save_and_continue" class="btn btn-primary">
                    Сохранить и продолжить редактирование
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/admin/products/form-handler.js')
@endpush