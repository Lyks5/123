import { ImagePreviewHandler, ProductVariantsHandler } from './form-handler.js';

class ProductEditHandler {
    constructor() {
        this.initDeleteImageHandlers();
        new ImagePreviewHandler();
        new ProductVariantsHandler();
    }

    initDeleteImageHandlers() {
        document.querySelectorAll('[data-delete-image]').forEach(button => {
            button.addEventListener('click', async (e) => {
                e.preventDefault();
                const imageId = button.dataset.deleteImage;
                if (await this.confirmImageDelete()) {
                    await this.deleteImage(imageId);
                }
            });
        });
    }

    async confirmImageDelete() {
        return confirm('Вы уверены, что хотите удалить это изображение?');
    }

    async deleteImage(imageId) {
        try {
            const response = await fetch(`/admin/products/images/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.status === 'success') {
                // Удаляем элемент изображения из DOM
                const imageElement = document.querySelector(`[data-image-id="${imageId}"]`);
                if (imageElement) {
                    imageElement.remove();
                }
                // Показываем уведомление об успехе
                this.showNotification('success', 'Изображение успешно удалено');
            } else {
                throw new Error(data.message || 'Ошибка при удалении изображения');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('error', 'Ошибка при удалении изображения');
        }
    }

    showNotification(type, message) {
        // Создаем элемент уведомления
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-md ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white shadow-lg z-50 transition-opacity duration-500`;
        notification.textContent = message;

        // Добавляем уведомление на страницу
        document.body.appendChild(notification);

        // Удаляем уведомление через 3 секунды
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
    new ProductEditHandler();
});