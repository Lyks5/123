document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('productForm');
    const dropZone = document.getElementById('dropZone');
    const imageInput = document.getElementById('images');
    const previewContainer = document.getElementById('imagePreviewContainer');
    
    // Валидация формы
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        const errors = {};
        
        // Проверка названия
        if (!form.name.value.trim()) {
            errors.name = 'Название продукта обязательно';
            isValid = false;
        }
        
        // Проверка описания
        if (!form.description.value.trim()) {
            errors.description = 'Описание продукта обязательно';
            isValid = false;
        }
        
        // Проверка артикула
        if (!form.sku.value.trim()) {
            errors.sku = 'Артикул обязателен';
            isValid = false;
        }
        
        // Проверка цены
        if (!form.price.value || parseFloat(form.price.value) <= 0) {
            errors.price = 'Введите корректную цену';
            isValid = false;
        }
        
        // Проверка категории
        if (!form.category_id.value) {
            errors.category_id = 'Выберите категорию';
            isValid = false;
        }
        
        // Проверка статуса
        if (!form.status.value) {
            errors.status = 'Выберите статус продукта';
            isValid = false;
        }
        
        // Проверка количества товара
        if (!form.stock_quantity.value || parseInt(form.stock_quantity.value) < 0) {
            errors.stock_quantity = 'Введите корректное количество товара';
            isValid = false;
        }
        
        // Очистка предыдущих ошибок
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
        document.querySelectorAll('.form-group').forEach(el => el.classList.remove('has-error'));
        
        // Отображение ошибок
        if (!isValid) {
            Object.keys(errors).forEach(key => {
                const errorEl = document.querySelector(`[data-error="${key}"]`);
                if (errorEl) {
                    errorEl.textContent = errors[key];
                    errorEl.closest('.form-group').classList.add('has-error');
                }
            });
            return;
        }
        
        // Если валидация прошла успешно, отправляем форму
        form.submit();
    });
    
    // Обработка загрузки изображений
    function handleFiles(files) {
        Array.from(files).forEach(file => {
            if (!file.type.startsWith('image/')) return;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'image-preview-item';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="remove-image">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                `;
                
                div.querySelector('.remove-image').addEventListener('click', function() {
                    div.remove();
                });
                
                previewContainer.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
    
    // Обработка выбора файлов через input
    imageInput.addEventListener('change', function(e) {
        const files = Array.from(this.files);
        previewContainer.innerHTML = ''; // Очищаем контейнер перед добавлением новых превью
        handleFiles(files);
        
        // Создаем новый FileList для формы
        const dataTransfer = new DataTransfer();
        files.forEach(file => dataTransfer.items.add(file));
        this.files = dataTransfer.files;
    });
    
    // Обработка drag & drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
    });
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, function() {
            dropZone.classList.add('drop-zone-active');
        });
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, function() {
            dropZone.classList.remove('drop-zone-active');
        });
    });
    
    dropZone.addEventListener('drop', function(e) {
        const files = Array.from(e.dataTransfer.files);
        previewContainer.innerHTML = ''; // Очищаем контейнер перед добавлением новых превью
        handleFiles(files);
        
        // Обновляем файлы в input
        const dataTransfer = new DataTransfer();
        files.forEach(file => dataTransfer.items.add(file));
        imageInput.files = dataTransfer.files;
    });
});