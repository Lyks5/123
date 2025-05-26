export class AttributesManager {
    constructor(container) {
        this.container = container;
        this.attributes = new Map();
        this.variants = new Map();
        
        this.tabList = this.container.querySelector('.tab-list');
        this.attributesList = this.container.querySelector('.attributes-list');
        this.variantsList = this.container.querySelector('.variants-list');
        
        this.initializeTabs();
        this.bindEvents();
    }

    initializeTabs() {
        this.container.innerHTML = `
            <div class="border rounded-lg">
                <div class="tab-list flex border-b">
                    <button type="button" 
                            class="px-4 py-2 hover:bg-gray-50 border-r active" 
                            data-tab="attributes">
                        Характеристики
                    </button>
                    <button type="button" 
                            class="px-4 py-2 hover:bg-gray-50" 
                            data-tab="variants">
                        Варианты
                    </button>
                </div>
                
                <div class="p-4">
                    <div class="attributes-list" data-content="attributes"></div>
                    <div class="variants-list hidden" data-content="variants">
                        <div class="space-y-4">
                            <button type="button" 
                                    class="btn-generate-variants px-4 py-2 bg-blue-500 text-white rounded-lg">
                                Сгенерировать варианты
                            </button>
                            <div class="variants-grid space-y-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    bindEvents() {
        // Обработка переключения табов
        this.container.querySelectorAll('[data-tab]').forEach(tab => {
            tab.addEventListener('click', () => this.switchTab(tab.dataset.tab));
        });

        // Обработка добавления атрибута
        this.container.querySelector('.btn-add-attribute')?.addEventListener('click', () => {
            this.addAttributeField();
        });

        // Обработка генерации вариантов
        this.container.querySelector('.btn-generate-variants')?.addEventListener('click', () => {
            this.generateVariants();
        });
    }

    switchTab(tabName) {
        // Обновляем активный таб
        this.container.querySelectorAll('[data-tab]').forEach(tab => {
            tab.classList.toggle('active', tab.dataset.tab === tabName);
        });

        // Показываем соответствующий контент
        this.container.querySelectorAll('[data-content]').forEach(content => {
            content.classList.toggle('hidden', content.dataset.content !== tabName);
        });
    }

    addAttributeField(attribute = null) {
        const id = attribute?.id || crypto.randomUUID();
        const field = document.createElement('div');
        field.className = 'attribute-field space-y-4 mb-4';
        field.dataset.attributeId = id;

        field.innerHTML = `
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-4">
                    <label class="block text-sm font-medium mb-1">Название</label>
                    <input type="text" 
                           name="attributes[${id}][name]" 
                           class="w-full rounded-lg border px-3 py-2"
                           value="${attribute?.name || ''}"
                           required>
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium mb-1">Тип</label>
                    <select name="attributes[${id}][type]" 
                            class="w-full rounded-lg border px-3 py-2">
                        <option value="TEXT" ${attribute?.type === 'TEXT' ? 'selected' : ''}>
                            Текст
                        </option>
                        <option value="NUMBER" ${attribute?.type === 'NUMBER' ? 'selected' : ''}>
                            Число
                        </option>
                        <option value="SELECT" ${attribute?.type === 'SELECT' ? 'selected' : ''}>
                            Список
                        </option>
                        <option value="MULTI_SELECT" ${attribute?.type === 'MULTI_SELECT' ? 'selected' : ''}>
                            Мультивыбор
                        </option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium mb-1">Единица</label>
                    <input type="text" 
                           name="attributes[${id}][unit]" 
                           class="w-full rounded-lg border px-3 py-2"
                           value="${attribute?.unit || ''}">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium mb-1">&nbsp;</label>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="attributes[${id}][isVariant]"
                               ${attribute?.isVariant ? 'checked' : ''}>
                        <span class="ml-2">Вариант</span>
                    </label>
                </div>
                <div class="col-span-1">
                    <label class="block text-sm font-medium mb-1">&nbsp;</label>
                    <button type="button" 
                            class="text-red-500 hover:text-red-700"
                            data-action="delete">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="attribute-options ${attribute?.type === 'SELECT' || attribute?.type === 'MULTI_SELECT' ? '' : 'hidden'}">
                <label class="block text-sm font-medium mb-1">Варианты значений</label>
                <div class="space-y-2">
                    ${this.renderAttributeOptions(id, attribute?.options || [])}
                    <button type="button" 
                            class="text-blue-500 text-sm hover:text-blue-700"
                            data-action="add-option">
                        + Добавить значение
                    </button>
                </div>
            </div>
        `;

        // Обработчики событий
        field.querySelector('[data-action="delete"]').addEventListener('click', () => {
            this.deleteAttribute(id);
        });

        field.querySelector('[data-action="add-option"]')?.addEventListener('click', () => {
            this.addAttributeOption(id);
        });

        field.querySelector('select[name^="attributes"][name$="[type]"]').addEventListener('change', (e) => {
            const isSelect = ['SELECT', 'MULTI_SELECT'].includes(e.target.value);
            field.querySelector('.attribute-options').classList.toggle('hidden', !isSelect);
        });

        this.attributesList.appendChild(field);
        this.attributes.set(id, attribute || { id });
    }

    renderAttributeOption(attributeId, value = '', index) {
        const option = document.createElement('div');
        option.className = 'flex items-center space-x-2';
        option.innerHTML = `
            <input type="text" 
                   name="attributes[${attributeId}][options][]" 
                   class="flex-1 rounded-lg border px-3 py-2"
                   value="${value}">
            <button type="button" 
                    class="text-red-500 hover:text-red-700"
                    data-action="delete-option">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;

        option.querySelector('[data-action="delete-option"]').addEventListener('click', () => {
            option.remove();
        });

        return option;
    }

    renderAttributeOptions(attributeId, options) {
        const container = document.createElement('div');
        container.className = 'options-container space-y-2';
        
        options.forEach((option, index) => {
            container.appendChild(this.renderAttributeOption(attributeId, option, index));
        });
        
        return container.outerHTML;
    }

    deleteAttribute(id) {
        const field = this.container.querySelector(`[data-attribute-id="${id}"]`);
        if (field) {
            field.remove();
            this.attributes.delete(id);
            this.checkVariantAttributes();
        }
    }

    addAttributeOption(attributeId) {
        const container = this.container.querySelector(
            `[data-attribute-id="${attributeId}"] .options-container`
        );
        if (container) {
            const index = container.children.length;
            container.appendChild(this.renderAttributeOption(attributeId, '', index));
        }
    }

    generateVariants() {
        const variantAttributes = Array.from(this.attributes.values())
            .filter(attr => attr.isVariant);

        if (variantAttributes.length === 0) {
            this.showError('Выберите атрибуты для создания вариантов');
            return;
        }

        // Получаем все возможные комбинации значений
        const combinations = this.generateAttributeCombinations(variantAttributes);
        
        // Очищаем текущие варианты
        this.variants.clear();
        const variantsGrid = this.container.querySelector('.variants-grid');
        variantsGrid.innerHTML = '';

        // Создаем варианты
        combinations.forEach((combo, index) => {
            const variantId = crypto.randomUUID();
            this.variants.set(variantId, {
                id: variantId,
                sku: `${this.getBaseSku()}-${index + 1}`,
                price: this.getBasePrice(),
                stockQuantity: 0,
                attributes: combo
            });

            this.renderVariant(variantId);
        });
    }

    renderVariant(variantId) {
        const variant = this.variants.get(variantId);
        if (!variant) return;

        const element = document.createElement('div');
        element.className = 'variant-item border rounded-lg p-4';
        element.dataset.variantId = variantId;

        element.innerHTML = `
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-3">
                    <label class="block text-sm font-medium mb-1">Артикул (SKU)</label>
                    <input type="text" 
                           name="variants[${variantId}][sku]" 
                           class="w-full rounded-lg border px-3 py-2"
                           value="${variant.sku}">
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium mb-1">Цена</label>
                    <input type="number" 
                           name="variants[${variantId}][price]" 
                           class="w-full rounded-lg border px-3 py-2"
                           value="${variant.price}"
                           min="0"
                           step="0.01">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium mb-1">Количество</label>
                    <input type="number" 
                           name="variants[${variantId}][stockQuantity]" 
                           class="w-full rounded-lg border px-3 py-2"
                           value="${variant.stockQuantity}"
                           min="0">
                </div>
                <div class="col-span-3">
                    <label class="block text-sm font-medium mb-1">Атрибуты</label>
                    <div class="text-sm text-gray-600">
                        ${this.formatVariantAttributes(variant.attributes)}
                    </div>
                </div>
                <div class="col-span-1">
                    <label class="block text-sm font-medium mb-1">&nbsp;</label>
                    <button type="button" 
                            class="text-red-500 hover:text-red-700"
                            data-action="delete">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        `;

        element.querySelector('[data-action="delete"]').addEventListener('click', () => {
            this.deleteVariant(variantId);
        });

        this.container.querySelector('.variants-grid').appendChild(element);
    }

    generateAttributeCombinations(attributes) {
        const combinations = [];
        const attributeValues = attributes.map(attr => {
            const options = Array.from(
                this.container.querySelectorAll(
                    `[data-attribute-id="${attr.id}"] [name^="attributes"][name$="[options][]"]`
                )
            ).map(input => input.value);
            return { attributeId: attr.id, values: options };
        });

        const generate = (current, index) => {
            if (index === attributeValues.length) {
                combinations.push([...current]);
                return;
            }

            const { attributeId, values } = attributeValues[index];
            for (const value of values) {
                current.push({ attributeId, value });
                generate(current, index + 1);
                current.pop();
            }
        };

        generate([], 0);
        return combinations;
    }

    formatVariantAttributes(attributes) {
        return attributes.map(attr => {
            const attribute = this.attributes.get(attr.attributeId);
            return `${attribute?.name}: ${attr.value}`;
        }).join(', ');
    }

    deleteVariant(id) {
        const element = this.container.querySelector(`[data-variant-id="${id}"]`);
        if (element) {
            element.remove();
            this.variants.delete(id);
        }
    }

    getAttributes() {
        return Array.from(this.attributes.values()).map(attr => {
            const field = this.container.querySelector(`[data-attribute-id="${attr.id}"]`);
            if (!field) return null;

            const options = Array.from(
                field.querySelectorAll('[name^="attributes"][name$="[options][]"]')
            ).map(input => input.value);

            return {
                id: attr.id,
                name: field.querySelector('[name$="[name]"]').value,
                type: field.querySelector('[name$="[type]"]').value,
                unit: field.querySelector('[name$="[unit]"]').value,
                isVariant: field.querySelector('[name$="[isVariant]"]').checked,
                options: options.length > 0 ? options : undefined
            };
        }).filter(Boolean);
    }

    getVariants() {
        return Array.from(this.variants.values()).map(variant => {
            const element = this.container.querySelector(`[data-variant-id="${variant.id}"]`);
            if (!element) return null;

            return {
                id: variant.id,
                sku: element.querySelector('[name$="[sku]"]').value,
                price: parseFloat(element.querySelector('[name$="[price]"]').value),
                stockQuantity: parseInt(element.querySelector('[name$="[stockQuantity]"]').value),
                attributes: variant.attributes
            };
        }).filter(Boolean);
    }

    getBaseSku() {
        return document.querySelector('[name="sku"]')?.value || '';
    }

    getBasePrice() {
        return parseFloat(document.querySelector('[name="price"]')?.value || 0);
    }

    showError(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg';
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
}