export class VariantsManager {
    constructor() {
        this.selectedAttributes = [];
        this.initialized = false;
    }

    async initialize() {
        if (this.initialized) return;
        
        console.log('VariantsManager: Начало инициализации');
        
        // Проверяем существующий store
        console.log('Проверка существующего Alpine store:', Alpine.store('variants'));
        
        // Инициализируем Alpine.js store только если он еще не существует
        if (!Alpine.store('variants')) {
            console.log('Создание нового Alpine store');
            Alpine.store('variants', {
                loading: false,
                hasSelectedAttributes: false,
                hasVariants: false,
                selectedCount: 0
            });
        }
        
        // Проверяем наличие контейнеров
        const attributesContainer = document.getElementById('attributes-container');
        const selectedAttributesContainer = document.getElementById('selected-attributes');
        
        if (!attributesContainer || !selectedAttributesContainer) {
            console.error('VariantsManager: Не найдены необходимые контейнеры');
            return;
        }

        console.log('VariantsManager: Контейнеры найдены');
        
        // Инициализируем обработчики
        await this.initializeAttributeHandlers();

        // Добавляем слушатель изменения вкладок
        document.addEventListener('tabChanged', (event) => {
            if (event.detail.tabName === 'attributes') {
                console.log('Переключение на вкладку атрибутов');
                console.log('Текущее состояние Alpine store:', Alpine.store('variants'));
                console.log('Контейнеры:', {
                    attributes: document.getElementById('attributes-container'),
                    selected: document.getElementById('selected-attributes')
                });
                this.refreshAttributes();
            }
        });

        // Подписываемся на изменения атрибутов
        document.addEventListener('attribute-changed', (event) => {
            Alpine.store('variants').selectedCount = this.selectedAttributes.length;
            if (event.detail.checked) {
                Alpine.store('variants').loading = true;
            }
        });

        this.initialized = true;
        console.log('VariantsManager: Инициализация завершена');
    }

    refreshAttributes() {
        console.log('=== Начало обновления атрибутов ===');
        
        // Проверяем и обновляем контейнеры
        // Проверяем видимость секции атрибутов
        const attributesSection = document.querySelector('[data-section="attributes"]');
        console.log('Состояние секции атрибутов:', {
            element: attributesSection,
            hidden: attributesSection?.classList.contains('hidden'),
            display: attributesSection ? window.getComputedStyle(attributesSection).display : 'н/д',
            ariaHidden: attributesSection?.getAttribute('aria-hidden')
        });

        const attributesContainer = document.getElementById('attributes-container');
        const selectedAttributesContainer = document.getElementById('selected-attributes');
        
        console.log('Состояние контейнеров:', {
            attributes: {
                exists: !!attributesContainer,
                display: attributesContainer ? window.getComputedStyle(attributesContainer).display : 'н/д'
            },
            selected: {
                exists: !!selectedAttributesContainer,
                display: selectedAttributesContainer ? window.getComputedStyle(selectedAttributesContainer).display : 'н/д'
            }
        });

        if (!attributesContainer || !selectedAttributesContainer) {
            console.error('Не найдены контейнеры для атрибутов');
            return;
        }

        // Принудительно показываем контейнеры
        attributesContainer.style.display = 'block';
        selectedAttributesContainer.style.display = 'block';

        // Обновляем выбранные атрибуты
        this.selectedAttributes.forEach(attribute => {
            const checkbox = document.querySelector(`input[value="${attribute.id}"]`);
            if (checkbox && !checkbox.checked) {
                checkbox.checked = true;
            }
        });

        // Загружаем значения для выбранных атрибутов
        this.selectedAttributes.forEach(attribute => {
            if (!document.getElementById(`attribute-values-${attribute.id}`)) {
                this.fetchAttributeValues(attribute.id, attribute.name);
            }
        });

        console.log('=== Обновление атрибутов завершено ===');
        
        // Отправляем событие об успешном обновлении
        document.dispatchEvent(new CustomEvent('attributes-refreshed', {
            detail: {
                selectedCount: this.selectedAttributes.length,
                containers: {
                    attributes: attributesContainer.style.display,
                    selected: selectedAttributesContainer.style.display
                }
            }
        }));
    }

    async initializeAttributeHandlers() {
        const attributeCheckboxes = document.querySelectorAll('.attribute-checkbox');
        console.log('VariantsManager: Найдено чекбоксов атрибутов:', attributeCheckboxes.length);
        
        if (attributeCheckboxes.length === 0) {
            console.warn('VariantsManager: Не найдены чекбоксы атрибутов');
            return;
        }
        
        // Обработка выбора атрибутов
        attributeCheckboxes.forEach(checkbox => {
            // Добавляем отслеживание состояния через Alpine
            checkbox.setAttribute('x-on:change',
                'Alpine.store(\'variants\').loading = true; ' +
                '$dispatch(\'attribute-changed\', { checked: $event.target.checked })'
            );
            
            // Добавляем основной обработчик
            checkbox.addEventListener('change', (e) => {
                this.handleAttributeChange(e);
                // Обновляем счетчик выбранных атрибутов
                Alpine.store('variants').selectedCount = this.selectedAttributes.length;
            });
        });

        // Инициализируем начальное состояние
        if (this.selectedAttributes.length > 0) {
            Alpine.store('variants').hasSelectedAttributes = true;
            Alpine.store('variants').selectedCount = this.selectedAttributes.length;
            this.updateVariants();
        }
    }

    handleAttributeChange(e) {
        const checkbox = e.target;
        const attributeId = checkbox.value;
        const attributeName = checkbox.dataset.attributeName;
        const variantsContainer = document.getElementById('variants-container');
        
        if (checkbox.checked) {
            this.selectedAttributes.push({
                id: attributeId,
                name: attributeName,
                values: []
            });
            this.fetchAttributeValues(attributeId, attributeName);
        } else {
            this.selectedAttributes = this.selectedAttributes.filter(attr => attr.id !== attributeId);
            const attributeElement = document.getElementById(`attribute-values-${attributeId}`);
            if (attributeElement) {
                attributeElement.remove();
            }
            this.updateVariants();
        }

        // Обновляем состояние в Alpine.js store
        Alpine.store('variants').hasSelectedAttributes = this.selectedAttributes.length > 0;
        Alpine.store('variants').selectedCount = this.selectedAttributes.length;
        Alpine.store('variants').loading = false;

        // Отправляем событие изменения атрибута
        document.dispatchEvent(new CustomEvent('attribute-changed', {
            detail: {
                id: attributeId,
                name: attributeName,
                checked: checkbox.checked,
                count: this.selectedAttributes.length
            }
        }));
    }

    async fetchAttributeValues(attributeId, attributeName) {
        try {
            const response = await fetch(`/admin/attributes/${attributeId}/values/list`);
            const data = await response.json();
            this.createAttributeValuesUI(attributeId, attributeName, data);
        } catch (error) {
            console.error('Ошибка при загрузке значений атрибута:', error);
        }
    }

    createAttributeValuesUI(attributeId, attributeName, values) {
        const container = document.getElementById('selected-attributes');
        const attributeDiv = document.createElement('div');
        
        attributeDiv.id = `attribute-values-${attributeId}`;
        attributeDiv.className = 'bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700';
        
        // Создаем разметку для атрибута
        attributeDiv.innerHTML = `
            <div class="p-4 space-y-4" x-data="{ showValues: true }">
                <!-- Заголовок -->
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-base font-medium text-gray-900 dark:text-white flex items-center">
                            ${attributeName}
                            <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-eco-100 text-eco-800 dark:bg-eco-800 dark:text-eco-100">
                                ${values.length} значений
                            </span>
                        </h4>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" @click="showValues = !showValues"
                            class="p-1 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300"
                            :aria-expanded="showValues">
                            <svg class="w-5 h-5 transition-transform"
                                :class="{ 'rotate-180': !showValues }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <button type="button"
                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-eco-600 hover:bg-eco-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500"
                            @click="$dispatch('add-attribute-value', { attributeId: '${attributeId}' })">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Добавить
                        </button>
                    </div>
                </div>

                <!-- Значения атрибута -->
                <div x-show="showValues" x-transition>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3" x-data>
                        ${values.map(value => `
                            <label class="relative flex items-start p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-eco-500 cursor-pointer group">
                                <input type="checkbox"
                                    name="attribute_values[${attributeId}][]"
                                    value="${value.id}"
                                    class="mt-0.5 rounded border-gray-300 dark:border-gray-600 text-eco-600 focus:ring-eco-500"
                                    @change="$dispatch('value-changed', {
                                        attributeId: '${attributeId}',
                                        valueId: '${value.id}',
                                        checked: $event.target.checked
                                    })">
                                <div class="ml-3 flex-1 min-w-0">
                                    <span class="block text-sm font-medium text-gray-900 dark:text-white truncate group-hover:text-eco-600">
                                        ${value.value}
                                    </span>
                                    ${value.products_count ? `
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">
                                            Используется: ${value.products_count}
                                        </span>
                                    ` : ''}
                                </div>
                            </label>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;

        // Добавляем обработчики событий
        const checkboxes = attributeDiv.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const attribute = this.selectedAttributes.find(attr => attr.id === attributeId);
                if (!attribute) return;

                if (checkbox.checked) {
                    attribute.values.push({
                        id: checkbox.value,
                        value: checkbox.closest('label').querySelector('.text-sm').textContent.trim()
                    });
                } else {
                    attribute.values = attribute.values.filter(v => v.id !== checkbox.value);
                }

                // Откладываем обновление вариантов для избежания частых перерисовок
                clearTimeout(this.updateTimer);
                this.updateTimer = setTimeout(() => this.updateVariants(), 300);
            });
        });

        container.appendChild(attributeDiv);
    }

    async createNewAttributeValue(attributeId) {
        // Добавляем обработчик события создания значения атрибута
        document.addEventListener('add-attribute-value', async (event) => {
            if (event.detail.attributeId !== attributeId) return;

            // Показываем модальное окно для ввода значения
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-lg w-full mx-4 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Добавить новое значение
                        </h3>
                    </div>
                    <div class="p-4">
                        <input type="text"
                            id="new-attribute-value"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50"
                            placeholder="Введите значение">
                        <p class="mt-2 text-sm text-gray-500">Значение должно быть уникальным</p>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 flex justify-end space-x-3">
                        <button type="button" class="btn-secondary-admin" id="cancel-value">
                            Отмена
                        </button>
                        <button type="button" class="btn-primary-admin" id="save-value">
                            Сохранить
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);

            // Обработчики модального окна
            const cancelBtn = modal.querySelector('#cancel-value');
            const saveBtn = modal.querySelector('#save-value');
            const input = modal.querySelector('#new-attribute-value');

            cancelBtn.onclick = () => {
                modal.remove();
            };

            saveBtn.onclick = async () => {
                const value = input.value.trim();
                if (!value) {
                    input.focus();
                    return;
                }

                // Показываем индикатор загрузки
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<div class="spinner w-5 h-5"></div>';

                try {
                    const response = await fetch(`/admin/attributes/${attributeId}/values`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ value })
                    });

                    if (response.ok) {
                        const newValue = await response.json();
                        this.addNewAttributeValue(attributeId, newValue);
                        
                        // Отправляем событие об успешном создании
                        document.dispatchEvent(new CustomEvent('notification-show', {
                            detail: {
                                type: 'success',
                                message: `Добавлено новое значение: ${value}`
                            }
                        }));

                        modal.remove();
                    } else {
                        throw new Error('Не удалось создать значение');
                    }
                } catch (error) {
                    console.error('Ошибка при создании значения:', error);
                    document.dispatchEvent(new CustomEvent('notification-show', {
                        detail: {
                            type: 'error',
                            message: 'Не удалось создать значение атрибута'
                        }
                    }));
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = 'Сохранить';
                }
            };

            // Фокус на поле ввода
            input.focus();
        });
    }

    addNewAttributeValue(attributeId, value) {
        const container = document.querySelector(`#attribute-values-${attributeId} .grid`);
        if (!container) return;

        const label = document.createElement('label');
        label.className = 'relative flex items-start p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-eco-500 cursor-pointer group animate-fade-in';
        label.innerHTML = `
            <input type="checkbox"
                name="attribute_values[${attributeId}][]"
                value="${value.id}"
                class="mt-0.5 rounded border-gray-300 dark:border-gray-600 text-eco-600 focus:ring-eco-500"
                @change="$dispatch('value-changed', {
                    attributeId: '${attributeId}',
                    valueId: '${value.id}',
                    checked: $event.target.checked
                })">
            <div class="ml-3 flex-1 min-w-0">
                <span class="block text-sm font-medium text-gray-900 dark:text-white truncate group-hover:text-eco-600">
                    ${value.value}
                </span>
                <span class="inline-flex items-center mt-1 text-xs text-eco-600 dark:text-eco-400">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Новое значение
                </span>
            </div>
        `;

        // Добавляем обработчик события
        const checkbox = label.querySelector('input[type="checkbox"]');
        checkbox.addEventListener('change', () => {
            const attribute = this.selectedAttributes.find(attr => attr.id === attributeId);
            if (!attribute) return;

            if (checkbox.checked) {
                attribute.values.push({
                    id: value.id,
                    value: value.value
                });
            } else {
                attribute.values = attribute.values.filter(v => v.id !== value.id);
            }

            // Откладываем обновление вариантов
            clearTimeout(this.updateTimer);
            this.updateTimer = setTimeout(() => this.updateVariants(), 300);
        });

        // Добавляем в начало списка
        container.insertBefore(label, container.firstChild);
        
        // Автоматически выбираем новое значение
        checkbox.checked = true;
        checkbox.dispatchEvent(new Event('change'));

        // Обновляем счетчик значений
        const countBadge = document.querySelector(`#attribute-values-${attributeId} .bg-eco-100`);
        if (countBadge) {
            const currentCount = parseInt(countBadge.textContent);
            countBadge.textContent = `${currentCount + 1} значений`;
        }
    }

    generateVariantCombinations(attributes, index = 0, current = {}) {
        if (index === attributes.length) {
            return [current];
        }
        
        if (attributes[index].values.length === 0) {
            return this.generateVariantCombinations(attributes, index + 1, current);
        }
        
        let result = [];
        attributes[index].values.forEach(value => {
            let newCombination = {...current};
            newCombination[attributes[index].name] = value.value;
            newCombination[`attribute_${attributes[index].id}`] = value.id;
            
            let combinations = this.generateVariantCombinations(attributes, index + 1, newCombination);
            result = result.concat(combinations);
        });
        
        return result;
    }

    async updateVariants() {
        try {
            // Включаем индикатор загрузки
            Alpine.store('variants').loading = true;

            const attributesWithValues = this.selectedAttributes.filter(attr => attr.values.length > 0);
            const variantsTableBody = document.getElementById('variants-table-body');
            
            // Обновляем состояние в Alpine.js store
            Alpine.store('variants').hasSelectedAttributes = this.selectedAttributes.length > 0;
            Alpine.store('variants').hasVariants = attributesWithValues.length > 0;
            
            if (attributesWithValues.length === 0) {
                Alpine.store('variants').hasVariants = false;
                return;
            }
            
            // Очищаем таблицу и показываем состояние загрузки
            variantsTableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center">
                        <div class="flex items-center justify-center space-x-3">
                            <div class="spinner"></div>
                            <span class="text-sm text-gray-500">Генерация вариантов...</span>
                        </div>
                    </td>
                </tr>
            `;
            
            // Даем время для отрисовки состояния загрузки
            await new Promise(resolve => setTimeout(resolve, 100));
            
            // Генерируем комбинации и обновляем таблицу
            const combinations = this.generateVariantCombinations(attributesWithValues);
            variantsTableBody.innerHTML = '';
        
            combinations.forEach((combination, index) => {
                const row = document.createElement('tr');
                row.className = index % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700';
                
                // Создаем ячейки для комбинации
                let combinationText = [];
                attributesWithValues.forEach(attr => {
                    if (combination[attr.name]) {
                        combinationText.push(`${attr.name}: ${combination[attr.name]}`);
                        
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = `variants[${index}][attribute_values][]`;
                        hiddenInput.value = combination[`attribute_${attr.id}`];
                        row.appendChild(hiddenInput);
                    }
                });
                
                // Добавляем ячейки в строку
                this.addVariantCell(row, combinationText.join(', '), 'text');
                this.addVariantCell(row, this.generateSku(combination), 'sku', index);
                this.addVariantCell(row, document.getElementById('price').value, 'price', index);
                this.addVariantCell(row, document.getElementById('sale_price').value, 'sale_price', index);
                this.addVariantCell(row, document.getElementById('stock_quantity').value, 'stock_quantity', index);
                
                variantsTableBody.appendChild(row);
            });

            // Отправляем событие об обновлении вариантов
            document.dispatchEvent(new CustomEvent('variants-updated', {
                detail: {
                    count: combinations.length
                }
            }));
        } catch (error) {
            console.error('Ошибка при обновлении вариантов:', error);
        } finally {
            Alpine.store('variants').loading = false;
        }
        
        variantsContainer.classList.remove('hidden');
    }

    generateSku(combination) {
        const baseSku = document.getElementById('sku').value || 'SKU';
        let variantSku = baseSku + '-';
        Object.values(combination)
            .filter(value => typeof value === 'string')
            .forEach(value => {
                variantSku += value.substring(0, 3);
            });
        return variantSku;
    }

    addVariantCell(row, value, type, index) {
        const cell = document.createElement('td');
        cell.className = 'px-4 py-2';
        
        if (type === 'text') {
            cell.textContent = value;
        } else {
            const input = document.createElement('input');
            input.type = type === 'sku' ? 'text' : 'number';
            input.name = `variants[${index}][${type}]`;
            input.value = value || '';
            input.className = 'w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50';
            
            if (type === 'price' || type === 'sale_price') {
                input.min = '0';
                input.step = '0.01';
            } else if (type === 'stock_quantity') {
                input.min = '0';
            }
            
            cell.appendChild(input);
        }
        
        row.appendChild(cell);
    }
}

export default VariantsManager;