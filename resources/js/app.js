import './bootstrap';

// Стили
import '../css/app.css';
import '../css/components/spinner.css';
import '../css/components/bulk-edit.css';
import '../css/components/history-panel.css';
import '../css/admin/attributes/form.css';

// Внешние зависимости
import Alpine from 'alpinejs';

// Модули приложения
import ProductForm from './products/form-handler';
import ImageHandler from './products/image-upload';
import VariantsManager from './products/variants-manager';
import BulkEditManager from './products/bulk-edit';
import initAttributeForm from './admin/attributes/form-handler';
import { initNotifications } from './components/notification';

// Глобальные зависимости
window.Alpine = Alpine;

// Глобальные настройки Alpine
Alpine.store('navigation', {
    catalogOpen: false
});

// Инициализация приложения
document.addEventListener('DOMContentLoaded', async () => {
    // Инициализация формы атрибутов
    if (document.querySelector('#attribute-form')) {
        initAttributeForm();
    }
    
    // Инициализация страницы продукта
    if (document.querySelector('form[action*="products"]')) {
        console.log('Инициализация страницы продукта');
        document.body.classList.add('loading');

        try {
            window.productForm = new ProductForm();
            window.imageHandler = new ImageHandler();
            window.variantsManager = new VariantsManager();
            window.bulkEditManager = new BulkEditManager();

            await window.productForm.initialize();
            
            console.log('Инициализация завершена успешно');
        } catch (error) {
            console.error('Ошибка при инициализации:', error);
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
        }
    }
});

// Глобальные компоненты
Alpine.data('productForm', () => ({
    loading: true,
    init() {
        this.loading = false;
        console.log('Alpine компонент productForm инициализирован');
    }
}));

// Инициализация при загрузке DOM
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM загружен, инициализация приложения...');
    Alpine.start();
    initNotifications();
    console.log('Alpine.js и уведомления инициализированы');
});

// Экспорты для использования в других модулях
export { Alpine,  };


