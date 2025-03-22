@extends('admin.layouts.app')

@section('title', 'Добавление товара')

@section('content')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Добавление товара</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Заполните информацию о новом товаре</p>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
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
            
            <!-- Attributes Section -->
            <div class="mb-6 border rounded-lg p-4 dark:border-gray-700">
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
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Изображения товара</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md">
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
                    <div id="preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2"></div>
                </div>
            </div>
            
            <div class="mb-6">
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
            
            <div class="flex justify-end">
                <a href="{{ route('admin.products.index') }}" class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-bold py-2 px-4 rounded mr-2">
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
                    radioDiv.className = 'absolute bottom-2 right-2 bg-white dark:bg-gray-700 p-1 rounded-full shadow';
                    
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

        // Атрибуты и варианты товара
        document.addEventListener('DOMContentLoaded', function() {
            const attributeCheckboxes = document.querySelectorAll('.attribute-checkbox');
            const selectedAttributesContainer = document.getElementById('selected-attributes');
            const variantsContainer = document.getElementById('variants-container');
            const variantsTableBody = document.getElementById('variants-table-body');
            
            // Структура для хранения выбранных атрибутов и их значений
            let selectedAttributes = [];
            
            // Обработка выбора/отмены атрибутов
            attributeCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const attributeId = this.value;
                    const attributeName = this.dataset.attributeName;
                    
                    if (this.checked) {
                        // Добавляем атрибут в массив выбранных
                        selectedAttributes.push({
                            id: attributeId,
                            name: attributeName,
                            values: []
                        });
                        
                        // Создаем UI для выбора значений атрибута
                        fetchAttributeValues(attributeId, attributeName);
                    } else {
                        // Удаляем атрибут из выбранных
                        selectedAttributes = selectedAttributes.filter(attr => attr.id !== attributeId);
                        
                        // Удаляем соответствующий UI элемент
                        const attributeElement = document.getElementById(`attribute-values-${attributeId}`);
                        if (attributeElement) {
                            attributeElement.remove();
                        }
                        
                        // Обновляем варианты
                        updateVariants();
                    }
                });
            });
            
            // Получение значений атрибута с сервера
            function fetchAttributeValues(attributeId, attributeName) {
                // Запрос к API для получения значений атрибута
                fetch(`/admin/attributes/${attributeId}/values/list`)
                    .then(response => response.json())
                    .then(data => {
                        createAttributeValuesUI(attributeId, attributeName, data);
                    })
                    .catch(error => {
                        console.error('Ошибка при загрузке значений атрибута:', error);
                    });
            }
            
            // Создание UI для выбора значений атрибута
            function createAttributeValuesUI(attributeId, attributeName, values) {
                const attributeDiv = document.createElement('div');
                attributeDiv.id = `attribute-values-${attributeId}`;
                attributeDiv.className = 'p-4 border rounded-md dark:border-gray-700';
                
                const attributeHeader = document.createElement('h4');
                attributeHeader.className = 'font-medium text-gray-700 dark:text-gray-300 mb-2';
                attributeHeader.textContent = attributeName;
                
                const valuesContainer = document.createElement('div');
                valuesContainer.className = 'grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3';
                
                values.forEach(value => {
                    const valueLabel = document.createElement('label');
                    valueLabel.className = 'inline-flex items-center';
                    
                    const valueCheckbox = document.createElement('input');
                    valueCheckbox.type = 'checkbox';
                    valueCheckbox.name = `attribute_values[${attributeId}][]`;
                    valueCheckbox.value = value.id;
                    valueCheckbox.className = 'rounded border-gray-300 dark:border-gray-600 text-eco-600 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50';
                    
                    valueCheckbox.addEventListener('change', function() {
                        // Находим атрибут в массиве выбранных
                        const attribute = selectedAttributes.find(attr => attr.id === attributeId);
                        
                        if (this.checked) {
                            // Добавляем значение в массив
                            attribute.values.push({
                                id: value.id,
                                value: value.value
                            });
                        } else {
                            // Удаляем значение из массива
                            attribute.values = attribute.values.filter(val => val.id !== value.id);
                        }
                        
                        // Обновляем варианты
                        updateVariants();
                    });
                    
                    const valueText = document.createElement('span');
                    valueText.className = 'ml-2 text-gray-700 dark:text-gray-300';
                    valueText.textContent = value.value;
                    
                    valueLabel.appendChild(valueCheckbox);
                    valueLabel.appendChild(valueText);
                    valuesContainer.appendChild(valueLabel);
                });
                
                attributeDiv.appendChild(attributeHeader);
                attributeDiv.appendChild(valuesContainer);
                
                selectedAttributesContainer.appendChild(attributeDiv);
            }
            
            // Генерация комбинаций вариантов товара
            function generateVariantCombinations(attributes, index = 0, current = {}) {
                // Базовый случай: достигли конца массива атрибутов
                if (index === attributes.length) {
                    return [current];
                }
                
                // Если у атрибута нет выбранных значений, пропускаем его
                if (attributes[index].values.length === 0) {
                    return generateVariantCombinations(attributes, index + 1, current);
                }
                
                let result = [];
                
                // Для каждого значения текущего атрибута создаем комбинацию
                attributes[index].values.forEach(value => {
                    // Копируем текущую комбинацию
                    let newCombination = {...current};
                    
                    // Добавляем текущее значение атрибута
                    newCombination[attributes[index].name] = value.value;
                    newCombination[`attribute_${attributes[index].id}`] = value.id;
                    
                    // Рекурсивно генерируем комбинации для следующих атрибутов
                    let combinations = generateVariantCombinations(attributes, index + 1, newCombination);
                    result = result.concat(combinations);
                });
                
                return result;
            }
            
            // Обновление таблицы вариантов
            function updateVariants() {
                // Фильтруем атрибуты, чтобы использовать только те, у которых есть выбранные значения
                const attributesWithValues = selectedAttributes.filter(attr => attr.values.length > 0);
                
                // Если нет атрибутов с выбранными значениями, скрываем таблицу вариантов
                if (attributesWithValues.length === 0) {
                    variantsContainer.classList.add('hidden');
                    return;
                }
                
                // Генерируем все возможные комбинации
                const combinations = generateVariantCombinations(attributesWithValues);
                
                // Очищаем таблицу
                variantsTableBody.innerHTML = '';
                
                // Заполняем таблицу вариантами
                combinations.forEach((combination, index) => {
                    const row = document.createElement('tr');
                    row.className = index % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700';
                    
                    // Ячейка с комбинацией атрибутов
                    const combinationCell = document.createElement('td');
                    combinationCell.className = 'px-4 py-2 text-sm text-gray-700 dark:text-gray-300';
                    
                    let combinationText = [];
                    attributesWithValues.forEach(attr => {
                        if (combination[attr.name]) {
                            combinationText.push(`${attr.name}: ${combination[attr.name]}`);
                            
                            // Добавляем скрытые поля для отправки данных на сервер
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = `variants[${index}][attribute_values][]`;
                            hiddenInput.value = combination[`attribute_${attr.id}`];
                            row.appendChild(hiddenInput);
                        }
                    });
                    
                    combinationCell.textContent = combinationText.join(', ');
                    row.appendChild(combinationCell);
                    
                    // Ячейка для SKU
                    const skuCell = document.createElement('td');
                    skuCell.className = 'px-4 py-2';
                    
                    const skuInput = document.createElement('input');
                    skuInput.type = 'text';
                    skuInput.name = `variants[${index}][sku]`;
                    skuInput.required = true;
                    skuInput.className = 'w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50';
                    
                    // Генерируем уникальный SKU на основе основного SKU и комбинации
                    const baseSku = document.getElementById('sku').value || 'SKU';
                    let variantSku = baseSku + '-';
                    attributesWithValues.forEach(attr => {
                        if (combination[attr.name]) {
                            variantSku += combination[attr.name].substring(0, 3);
                        }
                    });
                    skuInput.value = variantSku;
                    
                    skuCell.appendChild(skuInput);
                    row.appendChild(skuCell);
                    
                    // Ячейка для цены
                    const priceCell = document.createElement('td');
                    priceCell.className = 'px-4 py-2';
                    
                    const priceInput = document.createElement('input');
                    priceInput.type = 'number';
                    priceInput.name = `variants[${index}][price]`;
                    priceInput.min = '0';
                    priceInput.step = '0.01';
                    priceInput.value = document.getElementById('price').value || '';
                    priceInput.className = 'w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50';
                    
                    priceCell.appendChild(priceInput);
                    row.appendChild(priceCell);
                    
                    // Ячейка для цены со скидкой
                    const salePriceCell = document.createElement('td');
                    salePriceCell.className = 'px-4 py-2';
                    
                    const salePriceInput = document.createElement('input');
                    salePriceInput.type = 'number';
                    salePriceInput.name = `variants[${index}][sale_price]`;
                    salePriceInput.min = '0';
                    salePriceInput.step = '0.01';
                    salePriceInput.value = document.getElementById('sale_price').value || '';
                    salePriceInput.className = 'w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50';
                    
                    salePriceCell.appendChild(salePriceInput);
                    row.appendChild(salePriceCell);
                    
                    // Ячейка для количества
                    const stockCell = document.createElement('td');
                    stockCell.className = 'px-4 py-2';
                    
                    const stockInput = document.createElement('input');
                    stockInput.type = 'number';
                    stockInput.name = `variants[${index}][stock_quantity]`;
                    stockInput.min = '0';
                    stockInput.value = document.getElementById('stock_quantity').value || '0';
                    stockInput.className = 'w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50';
                    
                    stockCell.appendChild(stockInput);
                    row.appendChild(stockCell);
                    
                    // Добавляем строку в таблицу
                    variantsTableBody.appendChild(row);
                });
                
                // Показываем таблицу вариантов
                variantsContainer.classList.remove('hidden');
            }
            
            // Обновляем варианты при изменении значений полей SKU, цены и количества
            ['sku', 'price', 'sale_price', 'stock_quantity'].forEach(fieldId => {
                const field = document.getElementById(fieldId);
                field.addEventListener('input', () => {
                    if (variantsTableBody.innerHTML !== '') {
                        updateVariants();
                    }
                });
            });
        });
    </script>
@endsection