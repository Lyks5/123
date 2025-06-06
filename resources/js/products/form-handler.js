class ProductForm {
    constructor() {
        console.log('ProductForm: Создание экземпляра');
        this.selectedAttributes = [];
        this.changeHistory = [];
        this.draftTimer = null;
        this.selectedVariants = new Set();
        this.initialized = false;
    }

    async initialize() {
        if (this.initialized) return;

        try {
            console.log('ProductForm: Начало инициализации');

            // Создаем контейнер для истории
            this.historyContainer = document.createElement('div');
            this.historyContainer.className = 'fixed right-4 top-4 max-w-sm';
            document.body.appendChild(this.historyContainer);

            // Инициализируем базовые компоненты
            await this.init();

            // Инициализируем остальные менеджеры
            if (window.imageHandler) {
                console.log('Инициализация ImageHandler...');
                await window.imageHandler.initialize();
            }

            // Инициализируем вкладки
            this.initializeTabs();
            if (window.variantsManager) {
                console.log('Инициализация VariantsManager...');
                await window.variantsManager.initialize();
            }
            
            if (window.bulkEditManager) {
                console.log('Инициализация BulkEditManager...');
                await window.bulkEditManager.initialize();
            }

            this.initialized = true;
            console.log('ProductForm: Все компоненты успешно загружены');
            this.removePreloader();
            console.log('ProductForm: Прелоадер скрыт');

        } catch (error) {
            console.error('Ошибка при инициализации формы:', error);
            const preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.innerHTML = `
                    <div class="text-center">
                        <p class="text-xl font-bold mb-2 text-red-600">Ошибка инициализации</p>
                        <p class="text-sm text-red-500">${error.message}</p>
                        <button onclick="location.reload()" class="mt-4 bg-eco-600 text-white px-4 py-2 rounded hover:bg-eco-700">
                            Обновить страницу
                        </button>
                    </div>
                `;
            }
            throw error;
        }
    }

    async init() {
        console.log('ProductForm: Начало инициализации компонентов');

        try {
            // Сначала инициализируем компоненты интерфейса
            this.initializeTabs();
            this.initializeHints();
            this.initializePreview();

            // Затем настраиваем обработчики событий и валидацию
            this.setupEventListeners();
            this.setupValidation();
            this.setupFormButtons();

            // В конце настраиваем автосохранение
            this.initializeAutosave();

            console.log('ProductForm: Компоненты инициализированы успешно');
        } catch (error) {
            console.error('Ошибка при инициализации компонентов:', error);
            throw error;
        }
    }

    setupValidation() {
        const form = document.querySelector('form');
        if (!form) return;

        window.validateForm = (event) => {
            event.preventDefault();
            const form = event.target;
            let isValid = true;
            const errors = [];

            // Проверка обязательных полей
            const requiredFields = {
                'name': 'Название товара',
                'sku': 'SKU',
                'price': 'Цена',
                'description': 'Описание'
            };

            // Удаляем предыдущие сообщения об ошибках
            const prevErrors = form.querySelectorAll('.error-message');
            prevErrors.forEach(error => error.remove());

            for (const [field, label] of Object.entries(requiredFields)) {
                const input = form.querySelector(`[name="${field}"]`);
                if (!input.value.trim()) {
                    isValid = false;
                    errors.push(`Поле "${label}" обязательно для заполнения`);
                    input.classList.add('border-red-500');
                } else {
                    input.classList.remove('border-red-500');
                }
            }

            // Проверка цен
            const price = parseFloat(form.querySelector('[name="price"]').value);
            const salePrice = parseFloat(form.querySelector('[name="sale_price"]').value);
            
            if (salePrice && salePrice >= price) {
                isValid = false;
                errors.push('Цена со скидкой должна быть меньше основной цены');
                form.querySelector('[name="sale_price"]').classList.add('border-red-500');
            }

            // Если есть ошибки, показываем сообщения
            if (!isValid) {
                const errorContainer = document.createElement('div');
                errorContainer.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 error-message';
                errorContainer.innerHTML = `
                    <p class="font-bold">Пожалуйста, исправьте следующие ошибки:</p>
                    <ul class="list-disc list-inside">
                        ${errors.map(error => `<li>${error}</li>`).join('')}
                    </ul>
                `;
                
                const submitButton = form.querySelector('button[type="submit"]');
                form.insertBefore(errorContainer, submitButton.parentElement);

                // Удаляем сообщение об ошибках через 5 секунд
                setTimeout(() => errorContainer.remove(), 5000);
                return false;
            }

            // Добавляем индикатор загрузки
            const submitButton = form.querySelector('button[type="submit"]');
            const originalContent = submitButton.innerHTML;
            submitButton.innerHTML = `
                <div class="spinner mr-2"></div>
                Сохранение...
            `;
            submitButton.disabled = true;

            // Отправляем форму
            form.submit();
            return true;
        };
    }

    initializeTabs() {
        if (window.imageHandler) {
            window.imageHandler.initialize();
        }
    }

    handleTabClick(clickedTab) {
        if (!clickedTab || !clickedTab.dataset.tab) {
            return;
        }
        
        const targetTab = clickedTab.dataset.tab;

        // Обновляем URL
        const url = new URL(window.location);
        url.searchParams.set('tab', targetTab);
        window.history.pushState({}, '', url);

        // Диспатчим событие для Alpine.js
        window.dispatchEvent(new CustomEvent('tab-change', {
            detail: { tab: targetTab }
        }));

        // Инициализируем imageHandler при необходимости
        if (targetTab === 'images' && window.imageHandler) {
            setTimeout(() => window.imageHandler.initialize(), 100);
        }

        // Получаем все секции
        const sections = document.querySelectorAll('[data-section]');
        sections.forEach(section => {
            const shouldShow = section.dataset.section === targetTab;
            
            if (targetTab === 'attributes') {
                console.log('Обработка секции атрибутов');
                console.log('Состояние до изменения:', {
                    shouldShow,
                    classList: section.className,
                    isHidden: section.classList.contains('hidden'),
                    computedDisplay: window.getComputedStyle(section).display,
                    alpineStore: Alpine.store('variants')
                });

                // Принудительно обновляем видимость через CSS
                section.style.display = shouldShow ? 'block' : 'none';
                
                // Обновляем классы
                section.classList.toggle('hidden', !shouldShow);
                section.setAttribute('aria-hidden', !shouldShow);
                
                // Отправляем событие изменения видимости
                section.dispatchEvent(new CustomEvent('section-visibility-changed', {
                    detail: {
                        section: 'attributes',
                        visible: shouldShow,
                        className: section.className,
                        computedStyle: window.getComputedStyle(section).display
                    },
                    bubbles: true
                }));

                console.log('Состояние после изменения:', {
                    classList: section.className,
                    isHidden: section.classList.contains('hidden'),
                    computedDisplay: window.getComputedStyle(section).display,
                    style: section.style.display
                });
            } else {
                // Для остальных секций стандартное поведение
                section.classList.toggle('hidden', !shouldShow);
                section.setAttribute('aria-hidden', !shouldShow);
            }
            
            if (shouldShow) {
                console.log('Отображаем секцию:', targetTab);
                section.dispatchEvent(new CustomEvent('sectionShown'));
            }
        });
    }

    // Инициализация подсказок
    initializeHints() {
        const fields = document.querySelectorAll('[data-hint]');
        fields.forEach(field => {
            const hint = document.createElement('div');
            hint.className = 'tooltip ml-1 inline-block';
            hint.innerHTML = `
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="tooltip-text">${field.dataset.hint}</span>
            `;
            field.parentElement.appendChild(hint);
        });
    }

    // Инициализация предпросмотра
    initializePreview() {
        document.getElementById('previewButton').addEventListener('click', () => {
            this.showPreview();
        });
    }

    showPreview() {
        const modal = document.getElementById('previewModal');
        const gallery = new Splide('#preview-gallery', {
            type: 'fade',
            rewind: true,
            arrows: true,
            pagination: true
        });

        // Заполняем данные предпросмотра
        document.getElementById('preview-name').textContent = document.getElementById('name').value;
        document.getElementById('preview-price').textContent =
            new Intl.NumberFormat('ru-RU', { style: 'currency', currency: 'RUB' })
                .format(document.getElementById('price').value);
        document.getElementById('preview-description').textContent =
            document.getElementById('description').value;

        // Показываем модальное окно
        modal.classList.remove('hidden');

        // Инициализируем галерею
        gallery.mount();
    }

    // Инициализация автосохранения
    initializeAutosave() {
        const formInputs = document.querySelectorAll('input, textarea, select');
        formInputs.forEach(input => {
            input.addEventListener('change', () => this.saveDraft());
            input.addEventListener('keyup', () => {
                clearTimeout(this.draftTimer);
                this.draftTimer = setTimeout(() => this.saveDraft(), 1000);
            });
        });

        // Загрузка черновика при старте
        this.loadDraft();
    }

    saveDraft() {
        const autoSaveStatus = document.getElementById('autoSaveStatus');
        autoSaveStatus.textContent = 'Автосохранение...';
        autoSaveStatus.classList.remove('hidden');
        const formData = new FormData(document.querySelector('form'));
        const draftData = {};
        formData.forEach((value, key) => {
            draftData[key] = value;
        });
        
        localStorage.setItem('productDraft', JSON.stringify({
            timestamp: new Date().toISOString(),
            data: draftData
        }));

        this.addHistoryEntry('Автосохранение черновика');
        
        setTimeout(() => {
            autoSaveStatus.textContent = 'Сохранено';
            setTimeout(() => {
                autoSaveStatus.classList.add('hidden');
            }, 2000);
        }, 500);
    }

    loadDraft() {
        const draft = localStorage.getItem('productDraft');
        if (draft) {
            const { data } = JSON.parse(draft);
            Object.entries(data).forEach(([key, value]) => {
                const input = document.querySelector(`[name="${key}"]`);
                if (input) {
                    if (input.type === 'checkbox') {
                        input.checked = value === 'on';
                    } else {
                        input.value = value;
                    }
                }
            });
        }
    }

    setupEventListeners() {
        const addHistoryAndSave = (message) => {
            this.addHistoryEntry(message);
            this.saveDraft();
        };

        // События изображений
        document.addEventListener('main-image-removed',
            () => addHistoryAndSave('Удалено основное изображение')
        );

        document.addEventListener('images-reordered',
            () => addHistoryAndSave('Изменен порядок дополнительных изображений')
        );

        // События атрибутов
        document.addEventListener('attribute-value-added',
            (event) => addHistoryAndSave(event.detail.message)
        );

        // События вариантов
        document.addEventListener('variants-bulk-updated',
            (event) => addHistoryAndSave(event.detail.message)
        );

        document.addEventListener('variants-copied',
            (event) => addHistoryAndSave(event.detail.message)
        );

        // Слушатели для основных полей формы
        ['sku', 'price', 'sale_price', 'stock_quantity'].forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', () => {
                    const variantsTableBody = document.getElementById('variants-table-body');
                    if (variantsTableBody && variantsTableBody.innerHTML !== '') {
                        window.variantsManager.updateVariants();
                    }
                });
            }
        });
    }

    setupFormButtons() {
        const saveAndContinueButton = document.getElementById('saveAndContinueButton');
        if (saveAndContinueButton) {
            saveAndContinueButton.addEventListener('click', (e) => {
                e.preventDefault();
                const form = document.querySelector('form');
                const formData = new FormData(form);
                formData.append('continue_editing', '1');
                
                if (window.validateForm(e)) {
                    form.submit();
                }
            });
        }
    }

    removePreloader() {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            preloader.classList.add('hiding');
            setTimeout(() => {
                preloader.classList.add('hidden');
                document.body.classList.remove('loading');
            }, 300);
        }
    }

    addHistoryEntry(message) {
        const entry = {
            message,
            timestamp: new Date().toLocaleTimeString()
        };
        
        this.changeHistory.push(entry);
        
        // Показываем уведомление
        const notification = document.createElement('div');
        notification.className = 'bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg mb-2 flex items-center justify-between';
        notification.innerHTML = `
            <div>
                <p class="text-sm">${message}</p>
                <p class="text-xs text-gray-400">${entry.timestamp}</p>
            </div>
            <button type="button" class="ml-4 text-gray-400 hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        
        // Добавляем обработчик для кнопки закрытия
        notification.querySelector('button').addEventListener('click', () => {
            notification.remove();
        });
        
        this.historyContainer.appendChild(notification);
        
        // Автоматически скрываем уведомление через 5 секунд
        setTimeout(() => {
            notification.classList.add('opacity-0', 'transition-opacity');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
}

// Экспортируем класс
export default ProductForm;