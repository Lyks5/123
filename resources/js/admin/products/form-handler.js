// Управление предпросмотром изображений
class ImagePreviewHandler {
    constructor() {
        this.imageInput = document.getElementById('images');
        this.previewContainer = document.getElementById('preview-container');
        this.primaryImageInput = document.getElementById('primary_image');
        
        this.initImagePreview();
    }
    
    initImagePreview() {
        this.imageInput.addEventListener('change', (event) => this.handleImageChange(event));
    }
    
    handleImageChange(event) {
        this.previewContainer.innerHTML = '';
        
        Array.from(event.target.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = (e) => this.createPreviewElement(e.target.result, index);
            reader.readAsDataURL(file);
        });
    }
    
    createPreviewElement(src, index) {
        const div = document.createElement('div');
        div.className = 'relative';
        
        const img = document.createElement('img');
        img.src = src;
        img.className = 'preview-image';
        div.appendChild(img);
        
        const radioDiv = document.createElement('div');
        radioDiv.className = 'absolute bottom-2 right-2 bg-white dark:bg-gray-700 p-1 rounded-full shadow';
        
        const radioInput = document.createElement('input');
        radioInput.type = 'radio';
        radioInput.name = 'primary_image_selector';
        radioInput.value = index;
        radioInput.className = 'h-4 w-4 text-eco-600 focus:ring-eco-500';
        radioInput.addEventListener('change', () => {
            this.primaryImageInput.value = radioInput.value;
        });
        
        if (index === 0) {
            radioInput.checked = true;
            this.primaryImageInput.value = 0;
        }
        
        radioDiv.appendChild(radioInput);
        div.appendChild(radioDiv);
        
        this.previewContainer.appendChild(div);
    }
}

// Управление атрибутами и вариантами
class ProductVariantsHandler {
    constructor() {
        this.selectedAttributes = [];
        this.initElements();
        this.initEventListeners();
    }
    
    initElements() {
        this.attributeCheckboxes = document.querySelectorAll('.attribute-checkbox');
        this.selectedAttributesContainer = document.getElementById('selected-attributes');
        this.variantsContainer = document.getElementById('variants-container');
        this.variantsTableBody = document.getElementById('variants-table-body');
    }
    
    initEventListeners() {
        this.attributeCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', (e) => this.handleAttributeChange(e));
        });
    }
    
    handleAttributeChange(event) {
        const checkbox = event.target;
        const attributeId = checkbox.value;
        const attributeName = checkbox.dataset.attributeName;
        
        if (checkbox.checked) {
            this.addAttribute(attributeId, attributeName);
        } else {
            this.removeAttribute(attributeId);
        }
    }
    
    addAttribute(attributeId, attributeName) {
        this.selectedAttributes.push({
            id: attributeId,
            name: attributeName,
            values: []
        });
        
        this.fetchAttributeValues(attributeId, attributeName);
    }
    
    removeAttribute(attributeId) {
        this.selectedAttributes = this.selectedAttributes.filter(attr => attr.id !== attributeId);
        
        const attributeElement = document.getElementById(`attribute-values-${attributeId}`);
        if (attributeElement) {
            attributeElement.remove();
        }
        
        this.updateVariants();
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
        
        const header = document.createElement('h4');
        header.className = 'font-medium text-gray-700 dark:text-gray-300 mb-2';
        header.textContent = attributeName;
        
        const valuesContainer = document.createElement('div');
        valuesContainer.className = 'attributes-grid';
        
        values.forEach(value => {
            const label = this.createValueCheckbox(attributeId, value);
            valuesContainer.appendChild(label);
        });
        
        attributeDiv.appendChild(header);
        attributeDiv.appendChild(valuesContainer);
        this.selectedAttributesContainer.appendChild(attributeDiv);
    }
    
    createValueCheckbox(attributeId, value) {
        const label = document.createElement('label');
        label.className = 'inline-flex items-center';
        
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.name = `attribute_values[${attributeId}][]`;
        checkbox.value = value.id;
        checkbox.className = 'attribute-checkbox';
        
        checkbox.addEventListener('change', () => {
            const attribute = this.selectedAttributes.find(attr => attr.id === attributeId);
            
            if (checkbox.checked) {
                attribute.values.push({
                    id: value.id,
                    value: value.value
                });
            } else {
                attribute.values = attribute.values.filter(val => val.id !== value.id);
            }
            
            this.updateVariants();
        });
        
        const span = document.createElement('span');
        span.className = 'ml-2 text-gray-700 dark:text-gray-300';
        span.textContent = value.value;
        
        label.appendChild(checkbox);
        label.appendChild(span);
        
        return label;
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
        
        if (attributesWithValues.length === 0) {
            this.variantsContainer.classList.add('hidden');
            return;
        }
        
        this.variantsContainer.classList.remove('hidden');
        this.variantsTableBody.innerHTML = '';
        
        const combinations = this.generateVariantCombinations(attributesWithValues);
        combinations.forEach((combination, index) => this.createVariantRow(combination, index));
    }
    
    createVariantRow(combination, index) {
        const row = document.createElement('tr');
        row.className = index % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700';
        
        // Комбинация атрибутов
        const combinationCell = this.createCombinationCell(combination, index);
        row.appendChild(combinationCell);
        
        // SKU
        const skuCell = this.createSkuCell(combination, index);
        row.appendChild(skuCell);
        
        // Цена
        const priceCell = this.createPriceCell(index);
        row.appendChild(priceCell);
        
        // Цена со скидкой
        const salePriceCell = this.createSalePriceCell(index);
        row.appendChild(salePriceCell);
        
        // Количество
        const stockCell = this.createStockCell(index);
        row.appendChild(stockCell);
        
        this.variantsTableBody.appendChild(row);
    }
    
    createCombinationCell(combination, index) {
        const cell = document.createElement('td');
        cell.className = 'px-4 py-2 text-sm text-gray-700 dark:text-gray-300';
        
        let combinationText = [];
        this.selectedAttributes.forEach(attr => {
            if (combination[attr.name]) {
                combinationText.push(`${attr.name}: ${combination[attr.name]}`);
                
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `variants[${index}][attribute_values][]`;
                hiddenInput.value = combination[`attribute_${attr.id}`];
                cell.appendChild(hiddenInput);
            }
        });
        
        cell.appendChild(document.createTextNode(combinationText.join(', ')));
        return cell;
    }
    
    createSkuCell(combination, index) {
        const cell = document.createElement('td');
        cell.className = 'px-4 py-2';
        
        const input = document.createElement('input');
        input.type = 'text';
        input.name = `variants[${index}][sku]`;
        input.required = true;
        input.className = 'form-input';
        
        // Генерация SKU
        const baseSku = document.getElementById('sku').value || 'SKU';
        let variantSku = baseSku + '-';
        this.selectedAttributes.forEach(attr => {
            if (combination[attr.name]) {
                variantSku += combination[attr.name].substring(0, 3);
            }
        });
        input.value = variantSku;
        
        cell.appendChild(input);
        return cell;
    }
    
    createPriceCell(index) {
        const cell = document.createElement('td');
        cell.className = 'px-4 py-2';
        
        const input = document.createElement('input');
        input.type = 'number';
        input.name = `variants[${index}][price]`;
        input.min = '0';
        input.step = '0.01';
        input.value = document.getElementById('price').value || '';
        input.className = 'form-input';
        
        cell.appendChild(input);
        return cell;
    }
    
    createSalePriceCell(index) {
        const cell = document.createElement('td');
        cell.className = 'px-4 py-2';
        
        const input = document.createElement('input');
        input.type = 'number';
        input.name = `variants[${index}][sale_price]`;
        input.min = '0';
        input.step = '0.01';
        input.value = document.getElementById('sale_price').value || '';
        input.className = 'form-input';
        
        cell.appendChild(input);
        return cell;
    }
    
    createStockCell(index) {
        const cell = document.createElement('td');
        cell.className = 'px-4 py-2';
        
        const input = document.createElement('input');
        input.type = 'number';
        input.name = `variants[${index}][stock_quantity]`;
        input.min = '0';
        input.value = document.getElementById('stock_quantity').value || '0';
        input.className = 'form-input';
        
        cell.appendChild(input);
        return cell;
    }
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    new ImagePreviewHandler();
    new ProductVariantsHandler();
});