export class BulkEditManager {
    constructor() {
        this.selectedVariants = new Set();
        this.initialized = false;
    }

    async initialize() {
        if (this.initialized) return;

        console.log('BulkEditManager: Начало инициализации');
        try {
            await this.initializeBulkEditPanel();
            await this.initializeSelectAllHandler();
            this.initialized = true;
            console.log('BulkEditManager: Инициализация завершена');
        } catch (error) {
            console.error('BulkEditManager: Ошибка инициализации', error);
            throw error;
        }
    }

    initializeBulkEditPanel() {
        // Создаем панель массового редактирования
        const bulkEditPanel = document.createElement('div');
        bulkEditPanel.className = 'bulk-edit-panel';
        bulkEditPanel.innerHTML = `
            <div class="flex items-center space-x-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium mb-1">Цена</label>
                    <input type="number" class="w-full rounded-md" id="bulk-price" min="0" step="0.01">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium mb-1">Скидка</label>
                    <input type="number" class="w-full rounded-md" id="bulk-discount" min="0" step="0.01">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium mb-1">Количество</label>
                    <input type="number" class="w-full rounded-md" id="bulk-quantity" min="0">
                </div>
                <button type="button" id="apply-bulk-edit" class="bg-eco-600 text-white px-4 py-2 rounded-md">
                    Применить
                </button>
                <button type="button" id="copy-from-selected" class="bg-gray-600 text-white px-4 py-2 rounded-md">
                    Копировать из выбранного
                </button>
            </div>
        `;
        document.body.appendChild(bulkEditPanel);

        // Добавляем обработчики событий для кнопок
        document.getElementById('apply-bulk-edit').addEventListener('click', () => this.applyBulkEdit());
        document.getElementById('copy-from-selected').addEventListener('click', () => this.copyFromSelected());
    }

    initializeSelectAllHandler() {
        // Добавляем чекбокс "Выбрать все" в шапку таблицы
        const headerRow = document.querySelector('#variants-container thead tr');
        const selectHeader = document.createElement('th');
        selectHeader.className = 'px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider';
        selectHeader.innerHTML = `
            <input type="checkbox" id="select-all-variants" 
                class="rounded border-gray-300 dark:border-gray-600 text-eco-600">
        `;
        headerRow.insertBefore(selectHeader, headerRow.firstChild);

        // Обработчик для выбора всех вариантов
        document.getElementById('select-all-variants').addEventListener('change', (e) => {
            const checkboxes = document.querySelectorAll('.variant-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = e.target.checked;
                this.handleVariantSelection(cb);
            });
        });
    }

    handleVariantSelection(checkbox) {
        if (checkbox.checked) {
            this.selectedVariants.add(checkbox.closest('tr'));
        } else {
            this.selectedVariants.delete(checkbox.closest('tr'));
        }

        // Обновляем состояние кнопки "Выбрать все"
        const allCheckbox = document.getElementById('select-all-variants');
        const totalVariants = document.querySelectorAll('.variant-checkbox').length;
        allCheckbox.checked = this.selectedVariants.size === totalVariants;

        // Показываем/скрываем панель массового редактирования
        this.toggleBulkEditPanel(this.selectedVariants.size > 0);
    }

    toggleBulkEditPanel(show) {
        const panel = document.querySelector('.bulk-edit-panel');
        panel.style.display = show ? 'block' : 'none';
    }

    applyBulkEdit() {
        const price = document.getElementById('bulk-price').value;
        const discount = document.getElementById('bulk-discount').value;
        const quantity = document.getElementById('bulk-quantity').value;

        const checkedVariants = document.querySelectorAll('.variant-checkbox:checked');
        checkedVariants.forEach(checkbox => {
            const row = checkbox.closest('tr');
            if (price) row.querySelector('[name$="[price]"]').value = price;
            if (discount) row.querySelector('[name$="[sale_price]"]').value = discount;
            if (quantity) row.querySelector('[name$="[stock_quantity]"]').value = quantity;
        });

        // Отправляем событие о массовом обновлении
        document.dispatchEvent(new CustomEvent('variants-bulk-updated', {
            detail: {
                count: checkedVariants.length,
                message: `Массовое обновление ${checkedVariants.length} вариантов`
            }
        }));

        // Очищаем поля ввода
        document.getElementById('bulk-price').value = '';
        document.getElementById('bulk-discount').value = '';
        document.getElementById('bulk-quantity').value = '';
    }

    copyFromSelected() {
        const checkedVariants = Array.from(document.querySelectorAll('.variant-checkbox:checked'));
        if (checkedVariants.length < 2) {
            alert('Выберите как минимум 2 варианта (источник и цели для копирования)');
            return;
        }

        const sourceRow = checkedVariants[0].closest('tr');
        const price = sourceRow.querySelector('[name$="[price]"]').value;
        const salePrice = sourceRow.querySelector('[name$="[sale_price]"]').value;
        const quantity = sourceRow.querySelector('[name$="[stock_quantity]"]').value;

        checkedVariants.slice(1).forEach(checkbox => {
            const row = checkbox.closest('tr');
            row.querySelector('[name$="[price]"]').value = price;
            row.querySelector('[name$="[sale_price]"]').value = salePrice;
            row.querySelector('[name$="[stock_quantity]"]').value = quantity;
        });

        // Отправляем событие о копировании значений
        document.dispatchEvent(new CustomEvent('variants-copied', {
            detail: {
                sourceIndex: 0,
                targetCount: checkedVariants.length - 1,
                message: `Скопированы значения из варианта в ${checkedVariants.length - 1} других вариантов`
            }
        }));
    }
}

export default BulkEditManager;