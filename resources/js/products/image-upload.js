// Sortable доступен глобально через CDN

export class ImageUploadHandler {
    constructor() {
        // Инициализация будет вызвана отдельно
        this.initialized = false;
    }

    async initialize() {
        if (this.initialized) return;
        
        console.log('Инициализация ImageUploadHandler...');
        await this.initializeMainImageHandlers();
        await this.initializeAdditionalImagesHandlers();
        await this.initializeSortable();
        this.initialized = true;
        console.log('ImageUploadHandler инициализирован');
    }

    initializeMainImageHandlers() {
        const mainImageDropzone = document.getElementById('main-image-dropzone');
        const mainImageInput = document.getElementById('main_image');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            mainImageDropzone.addEventListener(eventName, this.preventDefaults);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            mainImageDropzone.addEventListener(eventName, () => {
                mainImageDropzone.classList.add('border-eco-500');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            mainImageDropzone.addEventListener(eventName, () => {
                mainImageDropzone.classList.remove('border-eco-500');
            });
        });

        mainImageDropzone.addEventListener('drop', (e) => this.handleMainImageDrop(e));
        mainImageInput.addEventListener('change', (e) => this.handleMainImageSelect(e));
    }

    initializeAdditionalImagesHandlers() {
        const additionalImagesDropzone = document.getElementById('additional-images-dropzone');
        const additionalImagesInput = document.getElementById('additional_images');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            additionalImagesDropzone.addEventListener(eventName, this.preventDefaults);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            additionalImagesDropzone.addEventListener(eventName, () => {
                additionalImagesDropzone.classList.add('border-eco-500');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            additionalImagesDropzone.addEventListener(eventName, () => {
                additionalImagesDropzone.classList.remove('border-eco-500');
            });
        });

        additionalImagesDropzone.addEventListener('drop', (e) => this.handleAdditionalImagesDrop(e));
        additionalImagesInput.addEventListener('change', (e) => this.handleAdditionalImagesSelect(e));
    }

    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    handleMainImageSelect(e) {
        const file = e.target.files[0];
        if (file) {
            this.handleMainImageUpload(file);
        }
    }

    handleMainImageDrop(e) {
        const file = e.dataTransfer.files[0];
        if (file) {
            this.handleMainImageUpload(file);
        }
    }

    handleMainImageUpload(file) {
        if (!file.type.startsWith('image/')) {
            alert('Пожалуйста, загрузите изображение');
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            document.querySelector('#main-image-preview img').src = e.target.result;
            document.getElementById('main-image-preview').classList.remove('hidden');
            this.simulateUploadProgress('main-image-progress', 'main-image-progress-bar');
        };
        reader.readAsDataURL(file);
    }

    handleAdditionalImagesSelect(e) {
        this.handleAdditionalImagesUpload(Array.from(e.target.files));
    }

    handleAdditionalImagesDrop(e) {
        this.handleAdditionalImagesUpload(Array.from(e.dataTransfer.files));
    }

    handleAdditionalImagesUpload(files) {
        const imageFiles = files.filter(file => file.type.startsWith('image/'));
        if (imageFiles.length === 0) {
            alert('Пожалуйста, загрузите изображения');
            return;
        }

        const currentImages = document.getElementById('additional-images-preview').children.length;
        if (currentImages + imageFiles.length > 5) {
            alert('Максимальное количество дополнительных изображений - 5');
            return;
        }

        imageFiles.forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.createImagePreview(e.target.result);
            };
            reader.readAsDataURL(file);
        });

        this.simulateUploadProgress('additional-images-progress', 'additional-images-progress-bar');
    }

    createImagePreview(src) {
        const preview = document.getElementById('additional-images-preview');
        const imageContainer = document.createElement('div');
        imageContainer.className = 'relative';
        imageContainer.innerHTML = `
            <img src="${src}" alt="Preview" class="w-full h-32 object-cover rounded-lg">
            <button type="button" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;

        imageContainer.querySelector('button').addEventListener('click', () => {
            imageContainer.remove();
        });

        preview.appendChild(imageContainer);
    }

    simulateUploadProgress(progressContainerId, progressBarId) {
        const progressContainer = document.getElementById(progressContainerId);
        const progressBar = document.getElementById(progressBarId);
        let progress = 0;

        progressContainer.classList.remove('hidden');
        progressBar.style.width = '0%';

        const interval = setInterval(() => {
            progress += Math.random() * 30;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
                setTimeout(() => {
                    progressContainer.classList.add('hidden');
                }, 500);
            }
            progressBar.style.width = `${progress}%`;
        }, 200);
    }

    async initializeSortable() {
        const container = document.getElementById('additional-images-preview');
        if (!container) {
            console.warn('Контейнер для сортировки не найден');
            return;
        }

        try {
            new Sortable(container, {
                animation: 150,
                ghostClass: 'bg-gray-100',
                onSort: () => {
                    // Вызываем событие изменения порядка
                    container.dispatchEvent(new CustomEvent('images-reordered'));
                }
            });
            console.log('Sortable инициализирован');
        } catch (error) {
            console.error('Ошибка инициализации Sortable:', error);
        }
    }

    removeMainImage() {
        const mainImageInput = document.getElementById('main_image');
        const mainImagePreview = document.getElementById('main-image-preview');
        const previewImage = mainImagePreview.querySelector('img');

        // Очищаем значение input файла
        mainImageInput.value = '';
        
        // Удаляем предпросмотр
        if (previewImage) {
            previewImage.src = '';
        }
        mainImagePreview.classList.add('hidden');

        // Отправляем событие удаления изображения
        document.dispatchEvent(new CustomEvent('main-image-removed'));
    }
}

export default ImageUploadHandler;