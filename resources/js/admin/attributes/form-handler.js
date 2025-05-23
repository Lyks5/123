const initAttributeForm = () => {
    const form = document.querySelector('#attribute-form');
    const typeSelect = document.querySelector('#type');
    const nameInput = document.querySelector('#name');
    const previewContainer = document.createElement('div');
    
    // Создаем контейнер для предпросмотра
    const setupPreviewContainer = () => {
        previewContainer.className = 'type-preview hidden';
        nameInput.parentElement.appendChild(previewContainer);
    };
    
    // Обновляем предпросмотр в зависимости от типа
    const updatePreview = () => {
        const type = typeSelect.value;
        previewContainer.innerHTML = '';
        previewContainer.className = 'type-preview';
        
        const previewContent = {
            'select': {
                title: 'Выпадающий список',
                html: `
                    <select class="form-input-admin mt-2">
                        <option>Значение 1</option>
                        <option>Значение 2</option>
                        <option>Значение 3</option>
                    </select>
                `
            },
            'radio': {
                title: 'Радиокнопки',
                html: `
                    <div class="space-y-2 mt-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="preview" class="form-radio" checked>
                            <span class="ml-2">Значение 1</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="preview">
                            <span class="ml-2">Значение 2</span>
                        </label>
                    </div>
                `
            },
            'checkbox': {
                title: 'Флажки',
                html: `
                    <div class="space-y-2 mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" class="form-checkbox" checked>
                            <span class="ml-2">Значение 1</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" class="form-checkbox">
                            <span class="ml-2">Значение 2</span>
                        </label>
                    </div>
                `
            },
            'color': {
                title: 'Цветовые значения',
                html: `
                    <div class="flex items-center space-x-3 mt-2">
                        <div class="color-preview" style="background-color: #ff0000" title="Красный"></div>
                        <div class="color-preview" style="background-color: #00ff00" title="Зеленый"></div>
                        <div class="color-preview" style="background-color: #0000ff" title="Синий"></div>
                    </div>
                    <style>
                        .color-preview {
                            width: 2rem;
                            height: 2rem;
                            border-radius: 0.375rem;
                            border: 2px solid #E5E7EB;
                            transition: all 0.3s ease;
                            cursor: pointer;
                        }
                        .color-preview:hover {
                            transform: scale(1.1);
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                        }
                    </style>
                `
            }
        };

        if (previewContent[type]) {
            previewContainer.innerHTML = `
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    ${previewContent[type].title}
                </label>
                <p class="help-text">Пример отображения значений</p>
                ${previewContent[type].html}
            `;
        }
    };

    // Валидация формы
    const validateForm = () => {
        let isValid = true;
        const name = nameInput.value.trim();
        const errors = [];

        // Проверка имени
        if (name.length === 0) {
            errors.push({ field: nameInput, message: 'Название атрибута обязательно' });
            isValid = false;
        } else if (name.length < 3) {
            errors.push({ field: nameInput, message: 'Название атрибута должно содержать минимум 3 символа' });
            isValid = false;
        } else if (name.length > 255) {
            errors.push({ field: nameInput, message: 'Название атрибута не должно превышать 255 символов' });
            isValid = false;
        } else {
            clearError(nameInput);
        }

        // Показываем все ошибки
        errors.forEach(error => {
            showError(error.field, error.message);
        });

        return isValid;
    };

    // Отображение ошибки
    const showError = (element, message) => {
        clearError(element);
        element.classList.add('border-red-500');
        const errorDiv = document.createElement('p');
        errorDiv.className = 'text-red-500 text-xs mt-1';
        errorDiv.textContent = message;
        element.parentElement.appendChild(errorDiv);
    };

    // Очистка ошибки
    const clearError = (element) => {
        element.classList.remove('border-red-500');
        const errorDiv = element.parentElement.querySelector('.text-red-500');
        if (errorDiv) {
            errorDiv.remove();
        }
    };

    // Обработка отправки формы
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (!validateForm()) {
                const event = new CustomEvent('show-notification', {
                    detail: {
                        type: 'error',
                        message: 'Пожалуйста, исправьте ошибки в форме'
                    }
                });
                window.dispatchEvent(event);
                return;
            }

            // Блокируем форму
            form.classList.add('form-disabled');

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    // Показываем уведомление об успехе
                    const event = new CustomEvent('show-notification', {
                        detail: {
                            type: 'success',
                            message: 'Атрибут успешно создан! Переадресация...'
                        }
                    });
                    window.dispatchEvent(event);

                    // Задержка перед переадресацией для показа уведомления
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1000);
                } else {
                    // Показываем ошибки валидации
                    if (result.errors) {
                        Object.keys(result.errors).forEach(key => {
                            const element = document.querySelector(`[name="${key}"]`);
                            if (element) {
                                showError(element, result.errors[key][0]);
                            }
                        });
                        
                        const event = new CustomEvent('show-notification', {
                            detail: {
                                type: 'error',
                                message: 'Пожалуйста, исправьте ошибки в форме'
                            }
                        });
                        window.dispatchEvent(event);
                    }
                }
            } catch (error) {
                console.error('Ошибка:', error);
                const event = new CustomEvent('show-notification', {
                    detail: {
                        type: 'error',
                        message: 'Произошла ошибка при создании атрибута. Пожалуйста, попробуйте еще раз.'
                    }
                });
                window.dispatchEvent(event);
            } finally {
                // Разблокируем форму
                form.classList.remove('form-disabled');
            }
        });
    }

    // Обработчик изменения типа атрибута
    if (typeSelect) {
        setupPreviewContainer();
        typeSelect.addEventListener('change', updatePreview);
        updatePreview(); // Инициализация при загрузке

        // Добавляем подсказки при наведении
        typeSelect.addEventListener('mouseover', (e) => {
            const option = e.target.options[e.target.selectedIndex];
            if (option) {
                const tooltips = {
                    'select': 'Позволяет выбрать одно значение из выпадающего списка',
                    'radio': 'Позволяет выбрать одно значение из нескольких вариантов',
                    'checkbox': 'Позволяет выбрать несколько значений',
                    'color': 'Специальный тип для выбора цветовых значений'
                };
                option.title = tooltips[option.value] || '';
            }
        });
    }

    // Добавляем интерактивность для предпросмотра
    previewContainer.addEventListener('click', (e) => {
        const colorPreview = e.target.closest('.color-preview');
        if (colorPreview) {
            const currentColor = colorPreview.style.backgroundColor;
            const event = new CustomEvent('show-notification', {
                detail: {
                    type: 'info',
                    message: `Выбран цвет: ${currentColor}`
                }
            });
            window.dispatchEvent(event);
        }
    });
};

export default initAttributeForm;