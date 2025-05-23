export class VariantsManager {
    constructor() {
        this.selectedAttributes = [];
        this.initialized = false;
    }

    async initialize() {
        if (this.initialized) return;
        
        console.log('VariantsManager: Начало инициализации');
        await this.initializeAttributeHandlers();
        this.initialized = true;
        console.log('VariantsManager: Инициализация завершена');
    }

    initializeAttributeHandlers() {
        const attributeCheckboxes = document.querySelectorAll('.attribute-checkbox');
        const selectedAttributesContainer = document.getElementById('selected-attributes');
        const variantsContainer = document.getElementById('variants-container');
        
        // Обработка выбора атрибутов
        attributeCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', (e) => this.handleAttributeChange(e));
        });
    }

    handleAttributeChange(e) {
        const checkbox = e.target;
        const attributeId = checkbox.value;
        const attributeName = checkbox.dataset.attributeName;
        
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
        const attributeDiv = document.createElement('div');
        attributeDiv.id = `attribute-values-${attributeId}`;
        attributeDiv.className = 'p-4 border rounded-md dark:border-gray-700';
        
        const headerContainer = document.createElement('div');
        headerContainer.className = 'flex justify-between items-center mb-4';
        
        const attributeHeader = document.createElement('h4');
        attributeHeader.className = 'font-medium text-gray-700 dark:text-gray-300';
        attributeHeader.textContent = attributeName;

        const addButton = document.createElement('button');
        addButton.type = 'button';
        addButton.className = 'bg-eco-600 text-white text-sm px-3 py-1 rounded-md hover:bg-eco-700 flex items-center';
        addButton.innerHTML = `
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Добавить значение
        `;
        addButton.onclick = () => this.createNewAttributeValue(attributeId);

        headerContainer.appendChild(attributeHeader);
        headerContainer.appendChild(addButton);
        
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
            
            valueCheckbox.addEventListener('change', () => {
                const attribute = this.selectedAttributes.find(attr => attr.id === attributeId);
                
                if (valueCheckbox.checked) {
                    attribute.values.push({
                        id: value.id,
                        value: value.value
                    });
                } else {
                    attribute.values = attribute.values.filter(val => val.id !== value.id);
                }
                
                this.updateVariants();
            });
            
            const valueText = document.createElement('span');
            valueText.className = 'ml-2 text-gray-700 dark:text-gray-300';
            valueText.textContent = value.value;
            
            valueLabel.appendChild(valueCheckbox);
            valueLabel.appendChild(valueText);
            valuesContainer.appendChild(valueLabel);
        });
        
        attributeDiv.appendChild(headerContainer);
        attributeDiv.appendChild(valuesContainer);
        
        const selectedAttributesContainer = document.getElementById('selected-attributes');
        selectedAttributesContainer.appendChild(attributeDiv);
    }

    async createNewAttributeValue(attributeId) {
        const value = prompt('Введите новое значение атрибута:');
        if (!value) return;

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
                // Отправляем событие о создании нового значения
                document.dispatchEvent(new CustomEvent('attribute-value-added', {
                    detail: {
                        attributeId,
                        value: newValue,
                        message: `Добавлено новое значение атрибута: ${value}`
                    }
                }));
            } else {
                throw new Error('Ошибка при создании значения атрибута');
            }
        } catch (error) {
            console.error('Ошибка при создании значения атрибута:', error);
            alert('Не удалось создать новое значение атрибута');
        }
    }

    addNewAttributeValue(attributeId, value) {
        const container = document.querySelector(`#attribute-values-${attributeId} .grid`);
        const label = document.createElement('label');
        label.className = 'inline-flex items-center';
        label.innerHTML = `
            <input type="checkbox" name="attribute_values[${attributeId}][]" value="${value.id}"
                class="rounded border-gray-300 dark:border-gray-600 text-eco-600 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
            <span class="ml-2 text-gray-700 dark:text-gray-300">${value.value}</span>
        `;
        container.appendChild(label);
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

    updateVariants() {
        const attributesWithValues = this.selectedAttributes.filter(attr => attr.values.length > 0);
        const variantsContainer = document.getElementById('variants-container');
        const variantsTableBody = document.getElementById('variants-table-body');
        
        if (attributesWithValues.length === 0) {
            variantsContainer.classList.add('hidden');
            return;
        }
        
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