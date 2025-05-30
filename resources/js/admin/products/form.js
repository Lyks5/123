class ProductForm {
    constructor() {
        this.form = document.querySelector('#product-form');
        this.imageInput = document.querySelector('#product-images');
        this.imagePreviewContainer = document.querySelector('#image-preview');
        this.attributesContainer = document.querySelector('#attributes-container');
        this.addAttributeBtn = document.querySelector('#add-attribute');
        
        this.init();
    }

    init() {
        if (this.form) {
            this.setupImagePreview();
            this.setupAttributesHandling();
            this.setupFormValidation();
            this.setupFormSubmission();
        }
    }

    // Предпросмотр изображений
    setupImagePreview() {
        if (this.imageInput) {
            this.imageInput.addEventListener('change', (e) => {
                this.imagePreviewContainer.innerHTML = '';
                const files = Array.from(e.target.files);

                files.forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const preview = document.createElement('div');
                            preview.className = 'image-preview-item';
                            preview.innerHTML = `
                                <img src="${e.target.result}" alt="Preview">
                                <button type="button" class="remove-image">&times;</button>
                            `;
                            this.imagePreviewContainer.appendChild(preview);

                            preview.querySelector('.remove-image').addEventListener('click', () => {
                                preview.remove();
                                // Создаем новый FileList без удаленного файла
                                const dt = new DataTransfer();
                                const input = this.imageInput;
                                const { files } = input;
                                
                                for (let i = 0; i < files.length; i++) {
                                    const file = files[i];
                                    if (file !== files[Array.from(files).indexOf(file)]) {
                                        dt.items.add(file);
                                    }
                                }
                                
                                input.files = dt.files;
                            });
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });
        }
    }

    // Управление атрибутами
    setupAttributesHandling() {
        if (this.addAttributeBtn) {
            this.addAttributeBtn.addEventListener('click', () => {
                const attributeRow = document.createElement('div');
                attributeRow.className = 'attribute-row';
                attributeRow.innerHTML = `
                    <select name="attributes[]" class="form-select">
                        <option value="">Выберите атрибут</option>
                        ${this.getAttributeOptions()}
                    </select>
                    <input type="text" name="values[]" class="form-input" placeholder="Значение">
                    <button type="button" class="remove-attribute">&times;</button>
                `;
                
                this.attributesContainer.appendChild(attributeRow);

                attributeRow.querySelector('.remove-attribute').addEventListener('click', () => {
                    attributeRow.remove();
                });
            });
        }
    }

    getAttributeOptions() {
        // Здесь должен быть код для получения списка доступных атрибутов
        // Можно загрузить их при инициализации формы или получать через AJAX
        return `
            <option value="color">Цвет</option>
            <option value="size">Размер</option>
            <option value="material">Материал</option>
        `;
    }

    // Валидация формы
    setupFormValidation() {
        this.form.addEventListener('submit', (e) => {
            if (!this.validateForm()) {
                e.preventDefault();
            }
        });

        // Живая валидация при вводе
        this.form.querySelectorAll('input, textarea, select').forEach(field => {
            field.addEventListener('input', () => {
                this.validateField(field);
            });
        });
    }

    validateForm() {
        let isValid = true;
        const requiredFields = this.form.querySelectorAll('[required]');

        requiredFields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    validateField(field) {
        const errorElement = field.nextElementSibling;
        let isValid = true;
        let errorMessage = '';

        // Очистка предыдущей ошибки
        if (errorElement && errorElement.classList.contains('error-message')) {
            errorElement.remove();
        }

        // Проверка обязательных полей
        if (field.hasAttribute('required') && !field.value.trim()) {
            isValid = false;
            errorMessage = 'Это поле обязательно для заполнения';
        }

        // Проверка минимальной длины
        if (field.minLength && field.value.length < field.minLength) {
            isValid = false;
            errorMessage = `Минимальная длина: ${field.minLength} символов`;
        }

        // Проверка формата цены
        if (field.name === 'price' && !/^\d+(\.\d{1,2})?$/.test(field.value)) {
            isValid = false;
            errorMessage = 'Введите корректную цену';
        }

        // Отображение ошибки
        if (!isValid) {
            const error = document.createElement('div');
            error.className = 'error-message';
            error.textContent = errorMessage;
            field.parentNode.insertBefore(error, field.nextSibling);
        }

        return isValid;
    }

    // Отправка формы через AJAX
    setupFormSubmission() {
        this.form.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (!this.validateForm()) {
                return;
            }

            const formData = new FormData(this.form);
            const submitButton = this.form.querySelector('[type="submit"]');
            
            try {
                submitButton.disabled = true;
                submitButton.textContent = 'Сохранение...';

                const response = await fetch(this.form.action, {
                    method: this.form.method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // Успешное сохранение
                    window.location.href = data.redirect || '/admin/products';
                } else {
                    // Ошибка валидации или сохранения
                    this.showErrors(data.errors || {'error': ['Произошла ошибка при сохранении']});
                }
            } catch (error) {
                console.error('Ошибка:', error);
                this.showErrors({'error': ['Произошла ошибка при отправке формы']});
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Сохранить';
            }
        });
    }

    showErrors(errors) {
        // Очистка предыдущих ошибок
        this.form.querySelectorAll('.error-message').forEach(el => el.remove());

        // Отображение новых ошибок
        Object.entries(errors).forEach(([field, messages]) => {
            const input = this.form.querySelector(`[name="${field}"]`);
            if (input) {
                const error = document.createElement('div');
                error.className = 'error-message';
                error.textContent = messages[0]; // Берем первое сообщение об ошибке
                input.parentNode.insertBefore(error, input.nextSibling);
            }
        });
    }
}

// Инициализация формы при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    new ProductForm();
});

export default ProductForm;