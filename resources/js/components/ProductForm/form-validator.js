export class FormValidator {
    constructor(form) {
        this.form = form;
        this.isDirtyState = false;
        this.errors = new Map();
        this.debounceTimers = new Map();
        this.pendingValidations = new Map();
        this.rules = {
            name: {
                required: true,
                minLength: 3,
                maxLength: 100
            },
            description: {
                required: true,
                minLength: 10,
                maxLength: 5000
            },
            sku: {
                required: true,
                pattern: /^[A-Za-z0-9-]+$/,
                minLength: 4,
                maxLength: 50,
                async: async (value, formData) => {
                    if (!value) return true;
                    try {
                        const response = await fetch(`/api/products/check-sku?sku=${encodeURIComponent(value)}&id=${formData.id || ''}`);
                        if (!response.ok) throw new Error('Ошибка проверки SKU');
                        const data = await response.json();
                        return !data.exists;
                    } catch (error) {
                        console.error('Ошибка при проверке SKU:', error);
                        return true; // Пропускаем проверку при ошибке сети
                    }
                }
            },
            price: {
                required: true,
                min: 0.01,
                max: 999999.99,
                type: 'number'
            },
            salePrice: {
                min: 0.01,
                max: 999999.99,
                type: 'number',
                custom: (value, formData) => {
                    if (!value) return true;
                    return parseFloat(value) < parseFloat(formData.price);
                }
            },
            stockQuantity: {
                required: true,
                min: 0,
                type: 'integer'
            },
            'categoryIds[]': {
                required: true,
                minLength: 1
            }
        };

        this.messages = {
            required: 'Это поле обязательно для заполнения',
            minLength: (min) => `Минимальная длина ${min} символов`,
            maxLength: (max) => `Максимальная длина ${max} символов`,
            pattern: 'Недопустимый формат',
            min: (min) => `Минимальное значение ${min}`,
            max: (max) => `Максимальное значение ${max}`,
            type: 'Неверный формат числа',
            custom: {
                salePrice: 'Цена со скидкой должна быть меньше основной цены'
            }
        };

        this.bindEvents();
    }

    bindEvents() {
        this.form.addEventListener('input', (e) => {
            this.isDirtyState = true;
            this.validateField(e.target);
        });

        this.form.addEventListener('change', (e) => {
            this.isDirtyState = true;
            this.validateField(e.target);
        });
    }

    async validateAll() {
        const formData = new FormData(this.form);
        const data = Object.fromEntries(formData);
        this.errors.clear();

        // Проверяем все поля с правилами
        for (const [fieldName, rules] of Object.entries(this.rules)) {
            const field = this.form.querySelector(`[name="${fieldName}"]`);
            if (field) {
                await this.validateField(field, data);
            }
        }

        // Проверяем специальные поля (изображения, атрибуты)
        await this.validateImages();
        await this.validateAttributes();

        return this.errors.size === 0;
    }

    debounce(key, fn, delay = 500) {
        if (this.debounceTimers.has(key)) {
            clearTimeout(this.debounceTimers.get(key));
        }
        return new Promise(resolve => {
            const timer = setTimeout(async () => {
                this.debounceTimers.delete(key);
                resolve(await fn());
            }, delay);
            this.debounceTimers.set(key, timer);
        });
    }

    async validateField(field, formData = null) {
        const name = field.name;
        const rules = this.rules[name];
        if (!rules) return true;

        const value = field.value;
        let errors = [];

        if (!formData) {
            formData = Object.fromEntries(new FormData(this.form));
        }

        // Показываем индикатор загрузки при асинхронной валидации
        if (rules.async) {
            this.showValidationPending(field);
        }

        // Проверяем все правила для поля
        if (rules.required && !value) {
            errors.push(this.messages.required);
        }

        if (value) {
            if (rules.minLength && value.length < rules.minLength) {
                errors.push(this.messages.minLength(rules.minLength));
            }

            if (rules.maxLength && value.length > rules.maxLength) {
                errors.push(this.messages.maxLength(rules.maxLength));
            }

            if (rules.pattern && !rules.pattern.test(value)) {
                errors.push(this.messages.pattern);
            }

            if (rules.type === 'number' || rules.type === 'integer') {
                const numValue = parseFloat(value);
                if (isNaN(numValue)) {
                    errors.push(this.messages.type);
                } else {
                    if (rules.min !== undefined && numValue < rules.min) {
                        errors.push(this.messages.min(rules.min));
                    }
                    if (rules.max !== undefined && numValue > rules.max) {
                        errors.push(this.messages.max(rules.max));
                    }
                    if (rules.type === 'integer' && !Number.isInteger(numValue)) {
                        errors.push('Должно быть целым числом');
                    }
                }
            }

            if (rules.custom) {
                const isValid = await rules.custom(value, formData);
                if (!isValid) {
                    errors.push(this.messages.custom[name] || 'Неверное значение');
                }
            }

            // Асинхронная валидация с debounce
            if (rules.async) {
                const isValid = await this.debounce(
                    `async_${name}`,
                    async () => rules.async(value, formData)
                );
                if (!isValid) {
                    errors.push(`${name === 'sku' ? 'SKU уже используется' : 'Значение недоступно'}`);
                }
            }
        }

        // Обновляем ошибки и отображение
        if (errors.length > 0) {
            this.errors.set(name, errors);
            this.showFieldErrors(field, errors);
        } else {
            this.errors.delete(name);
            this.clearFieldErrors(field);
        }

        return errors.length === 0;
    }

    async validateImages() {
        const imageManager = document.querySelector('.image-manager');
        if (!imageManager) return true;

        const images = imageManager.querySelectorAll('.product-image');
        if (images.length === 0) {
            this.errors.set('images', ['Добавьте хотя бы одно изображение']);
            return false;
        }

        return true;
    }

    async validateAttributes() {
        const attributesSection = document.querySelector('.attributes-section');
        if (!attributesSection) return true;

        const requiredAttributes = attributesSection.querySelectorAll('[data-required="true"]');
        let hasErrors = false;

        requiredAttributes.forEach(attr => {
            const value = attr.value;
            if (!value) {
                const name = attr.name;
                this.errors.set(name, ['Это поле обязательно для заполнения']);
                hasErrors = true;
            }
        });

        return !hasErrors;
    }

    showValidationPending(field) {
        const container = field.parentElement;
        let pendingIndicator = container.querySelector('.validation-pending');
        
        if (!pendingIndicator) {
            pendingIndicator = document.createElement('div');
            pendingIndicator.className = 'validation-pending absolute right-2 top-1/2 transform -translate-y-1/2';
            pendingIndicator.innerHTML = `
                <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `;
            container.appendChild(pendingIndicator);
        }
    }

    clearValidationPending(field) {
        const container = field.parentElement;
        const pendingIndicator = container.querySelector('.validation-pending');
        if (pendingIndicator) {
            pendingIndicator.remove();
        }
    }

    showFieldErrors(field, errors) {
        this.clearValidationPending(field);

        // Находим или создаем контейнер для ошибок
        let errorContainer = field.parentElement.querySelector('.field-error');
        if (!errorContainer) {
            errorContainer = document.createElement('div');
            errorContainer.className = 'field-error text-red-500 text-sm mt-1 space-y-1';
            field.parentElement.appendChild(errorContainer);
        }

        // Отображаем ошибки с анимацией
        errorContainer.innerHTML = errors.map(error =>
            `<div class="error-item fade-in flex items-center space-x-1">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>${error}</span>
            </div>`
        ).join('');
        
        field.classList.add('error');
    }

    clearFieldErrors(field) {
        this.clearValidationPending(field);
        
        const errorContainer = field.parentElement.querySelector('.field-error');
        if (errorContainer) {
            // Плавное скрытие ошибок
            errorContainer.style.opacity = '0';
            setTimeout(() => errorContainer.remove(), 300);
        }
        field.classList.remove('error');
    }

    isDirty() {
        return this.isDirtyState;
    }

    resetDirty() {
        this.isDirtyState = false;
    }
}