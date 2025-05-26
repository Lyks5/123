export class ImageManager {
    constructor(container, options = {}) {
        this.container = container;
        this.options = {
            maxImages: options.maxImages || 8,
            maxSize: options.maxSize || 10 * 1024 * 1024, // 10MB
            allowedTypes: options.allowedTypes || ['image/jpeg', 'image/png', 'image/gif'],
            maxUploadRetries: options.maxUploadRetries || 3,
            retryDelay: options.retryDelay || 2000,
            maxPreviewSize: options.maxPreviewSize || 800 // максимальный размер превью в пикселях
        };

        this.images = new Map();
        this.dropZone = this.container.querySelector('.drop-zone');
        this.imageGrid = this.container.querySelector('.image-grid');
        this.fileInput = this.container.querySelector('input[type="file"]');

        this.initializeDropZone();
        this.bindEvents();
    }

    initializeDropZone() {
        const dropZone = document.createElement('div');
        dropZone.className = 'drop-zone border-2 border-dashed rounded-lg p-8 text-center';
        dropZone.innerHTML = `
            <div class="space-y-4">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <div class="text-gray-600">
                    Перетащите изображения сюда или
                    <button type="button" class="text-blue-500 hover:text-blue-600">выберите файл</button>
                </div>
                <input type="file" class="hidden" multiple accept="image/*">
            </div>
        `;

        this.container.insertBefore(dropZone, this.container.firstChild);
        this.dropZone = dropZone;
        this.fileInput = dropZone.querySelector('input[type="file"]');
    }

    bindEvents() {
        // Обработка клика по кнопке выбора файла
        this.dropZone.querySelector('button').addEventListener('click', () => {
            this.fileInput.click();
        });

        // Обработка выбора файлов
        this.fileInput.addEventListener('change', (e) => {
            this.handleFiles(Array.from(e.target.files));
        });

        // Обработка drag & drop
        this.dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            this.dropZone.classList.add('border-blue-500');
        });

        this.dropZone.addEventListener('dragleave', () => {
            this.dropZone.classList.remove('border-blue-500');
        });

        this.dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            this.dropZone.classList.remove('border-blue-500');
            
            const files = Array.from(e.dataTransfer.files).filter(
                file => this.options.allowedTypes.includes(file.type)
            );
            
            this.handleFiles(files);
        });

        // Обработка сортировки в сетке изображений
        if (this.imageGrid) {
            new Sortable(this.imageGrid, {
                animation: 150,
                onEnd: () => this.updateImageOrder()
            });
        }
    }

    async handleFiles(files) {
        if (this.images.size + files.length > this.options.maxImages) {
            this.showError(`Максимальное количество изображений: ${this.options.maxImages}`);
            return;
        }

        for (const file of files) {
            if (!this.validateFile(file)) continue;

            const id = crypto.randomUUID();
            const preview = await this.createPreview(file);
            
            this.images.set(id, {
                id,
                file,
                preview,
                status: 'pending',
                progress: 0
            });

            this.renderImage(id);
            await this.uploadImage(id);
        }
    }

    validateFile(file) {
        if (!this.options.allowedTypes.includes(file.type)) {
            this.showError('Неподдерживаемый формат файла');
            return false;
        }

        if (file.size > this.options.maxSize) {
            this.showError('Размер файла превышает допустимый');
            return false;
        }

        return true;
    }

    async createPreview(file) {
        return new Promise((resolve) => {
            const img = new Image();
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const reader = new FileReader();

            reader.onload = (e) => {
                img.onload = () => {
                    // Вычисляем размеры с сохранением пропорций
                    let width = img.width;
                    let height = img.height;
                    
                    if (width > height) {
                        if (width > this.options.maxPreviewSize) {
                            height = height * (this.options.maxPreviewSize / width);
                            width = this.options.maxPreviewSize;
                        }
                    } else {
                        if (height > this.options.maxPreviewSize) {
                            width = width * (this.options.maxPreviewSize / height);
                            height = this.options.maxPreviewSize;
                        }
                    }

                    // Устанавливаем размеры canvas
                    canvas.width = width;
                    canvas.height = height;

                    // Рисуем изображение с новыми размерами
                    ctx.drawImage(img, 0, 0, width, height);

                    // Конвертируем в WebP для лучшего сжатия
                    resolve(canvas.toDataURL('image/webp', 0.8));
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    renderImage(id) {
        const image = this.images.get(id);
        if (!image) return;

        const element = document.createElement('div');
        element.className = 'relative group';
        element.dataset.imageId = id;
        element.innerHTML = `
            <div class="relative aspect-square overflow-hidden rounded-lg">
                <img src="${image.preview}" 
                     alt="" 
                     class="object-cover w-full h-full">
                ${this.renderProgress(image.progress)}
            </div>
            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 
                        transition-opacity flex items-center justify-center space-x-2">
                <button type="button" class="p-2 text-white hover:text-blue-500"
                        data-action="primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M5 13l4 4L19 7"/>
                    </svg>
                </button>
                <button type="button" class="p-2 text-white hover:text-red-500"
                        data-action="delete">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        `;

        element.querySelector('[data-action="primary"]').addEventListener('click', () => {
            this.setPrimaryImage(id);
        });

        element.querySelector('[data-action="delete"]').addEventListener('click', () => {
            this.deleteImage(id);
        });

        this.imageGrid.appendChild(element);
    }

    renderProgress(progress) {
        if (progress === 100) return '';
        
        return `
            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="w-16 h-16 rounded-full border-4 border-white border-t-transparent animate-spin"></div>
            </div>
        `;
    }

    async uploadImage(id, retryCount = 0) {
        const image = this.images.get(id);
        if (!image || image.status === 'uploaded') return;

        try {
            const formData = new FormData();
            formData.append('image', image.file);

            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 секунд таймаут

            const response = await fetch('/api/products/images', {
                method: 'POST',
                body: formData,
                signal: controller.signal
            });

            clearTimeout(timeoutId);

            if (!response.ok) throw new Error('Ошибка загрузки');

            const result = await response.json();
            this.images.set(id, {
                ...image,
                status: 'uploaded',
                progress: 100,
                url: result.url
            });

            this.updateImageElement(id);
            this.showSuccess('Изображение успешно загружено');
        } catch (error) {
            console.error('Ошибка загрузки:', error);

            if (retryCount < this.options.maxUploadRetries) {
                this.images.set(id, {
                    ...image,
                    status: 'retrying',
                    retryCount: retryCount + 1
                });
                this.updateImageElement(id);
                this.showInfo(`Повторная попытка ${retryCount + 1}/${this.options.maxUploadRetries}`);

                // Повторная попытка с задержкой
                await new Promise(resolve => setTimeout(resolve, this.options.retryDelay));
                return this.uploadImage(id, retryCount + 1);
            }

            this.images.set(id, {
                ...image,
                status: 'error',
                error: error.message
            });
            
            const errorMessage = error.name === 'AbortError'
                ? 'Превышено время ожидания загрузки'
                : 'Ошибка загрузки изображения';
            
            this.showError(errorMessage);
            this.updateImageElement(id);
        }
    }

    updateImageElement(id) {
        const element = this.imageGrid.querySelector(`[data-image-id="${id}"]`);
        if (!element) return;

        const image = this.images.get(id);
        if (!image) return;

        const progress = element.querySelector('.absolute.inset-0');
        if (progress && image.status === 'uploaded') {
            progress.remove();
        }
    }

    setPrimaryImage(id) {
        this.images.forEach((image, imageId) => {
            image.isPrimary = imageId === id;
        });

        this.imageGrid.querySelectorAll('[data-image-id]').forEach(element => {
            element.classList.toggle('ring-2 ring-blue-500', 
                element.dataset.imageId === id);
        });
    }

    deleteImage(id) {
        const element = this.imageGrid.querySelector(`[data-image-id="${id}"]`);
        if (element) {
            element.remove();
        }

        this.images.delete(id);
    }

    updateImageOrder() {
        const newOrder = Array.from(this.imageGrid.children).map(
            element => element.dataset.imageId
        );
        // Здесь можно добавить обработку обновления порядка
    }

    getImages() {
        return Array.from(this.images.values())
            .filter(image => image.status === 'uploaded')
            .map(image => ({
                id: image.id,
                url: image.url,
                isPrimary: image.isPrimary
            }));
    }

    showNotification(message, type = 'info') {
        const icons = {
            success: '✓',
            error: '⚠',
            info: 'ℹ',
            warning: '⚠'
        };

        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-blue-500',
            warning: 'bg-yellow-500'
        };

        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg
                          transform transition-all duration-300 opacity-0 translate-y-2`;
        toast.innerHTML = `
            <div class="flex items-center space-x-2">
                <span class="text-lg">${icons[type]}</span>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(toast);
        requestAnimationFrame(() => {
            toast.classList.remove('opacity-0', 'translate-y-2');
        });

        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    showError(message) {
        this.showNotification(message, 'error');
    }

    showSuccess(message) {
        this.showNotification(message, 'success');
    }

    showInfo(message) {
        this.showNotification(message, 'info');
    }

    showWarning(message) {
        this.showNotification(message, 'warning');
    }
}