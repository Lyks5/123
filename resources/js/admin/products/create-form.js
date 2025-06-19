class ProductCreateHandler {
    constructor() {
        this.form = document.getElementById('productForm');
        this.imageInput = document.getElementById('images');
        this.previewContainer = document.getElementById('preview-container');
        
        this.initHandlers();
    }

    initHandlers() {
        // Инициализация загрузки изображений
        this.imageInput.addEventListener('change', (e) => this.handleImageChange(e));
        
        // Обработка Drag and Drop
        const dropZone = document.querySelector('.border-dashed');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.add('border-primary');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.remove('border-primary');
            });
        });

        dropZone.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            this.handleFiles(files);
        });

        // Обработка отправки формы
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
    }

    handleImageChange(event) {
        const files = event.target.files;
        this.handleFiles(files);
    }

    handleFiles(files) {
        Array.from(files).forEach(file => {
            // Проверка типа файла
            if (!file.type.startsWith('image/')) {
                this.showNotification('error', 'Пожалуйста, загружайте только изображения');
                return;
            }

            // Создаем превью
            const reader = new FileReader();
            reader.onload = (e) => {
                this.createPreviewElement(e.target.result, file);
            };
            reader.readAsDataURL(file);
        });
    }

    createPreviewElement(src, file) {
        const wrapper = document.createElement('div');
        wrapper.className = 'relative group';

        // Изображение
        const img = document.createElement('img');
        img.src = src;
        img.className = 'w-full aspect-square object-cover rounded-lg';
        wrapper.appendChild(img);

        // Кнопка удаления
        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'absolute top-2 right-2 hidden group-hover:flex bg-red-500 text-white p-1 rounded-full hover:bg-red-600';
        removeButton.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
        removeButton.onclick = () => wrapper.remove();
        wrapper.appendChild(removeButton);

        // Скрытый input для файла
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.name = 'images[]';
        fileInput.className = 'hidden';
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;
        wrapper.appendChild(fileInput);

        this.previewContainer.appendChild(wrapper);
    }

    async handleSubmit(e) {
        e.preventDefault();

        const submitButton = this.form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.textContent = 'Создание...';

        try {
            const formData = new FormData(this.form);
            const response = await fetch(this.form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (response.ok) {
                this.showNotification('success', 'Продукт успешно создан');
                setTimeout(() => {
                    window.location.href = '/admin/products';
                }, 1000);
            } else {
                throw new Error(result.message || 'Произошла ошибка при создании продукта');
            }
        } catch (error) {
            this.showNotification('error', error.message);
            submitButton.disabled = false;
            submitButton.textContent = 'Создать продукт';
        }
    }

    showNotification(type, message) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-md ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white shadow-lg z-50 transition-opacity duration-500`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 500);
        }, 3000);
    }
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    new ProductCreateHandler();
});