

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

// Хранилище для управления вариантами
Alpine.store('variants', {
    loading: false,
    hasSelectedAttributes: false,
    hasVariants: false,
    loadingAttributes: false,
    selectedCount: 0,

    setLoading(value) {
        this.loading = value;
    },

    setLoadingAttributes(value) {
        this.loadingAttributes = value;
    },

    setHasSelectedAttributes(value) {
        this.hasSelectedAttributes = value;
    },

    setHasVariants(value) {
        this.hasVariants = value;
    },

    setSelectedCount(value) {
        this.selectedCount = value;
    }
});

// Инициализация приложения
document.addEventListener('alpine:init', async () => {
    console.log('Alpine.js инициализирован, настройка store...');

    // Инициализируем store до Alpine.start
    // Инициализируем Alpine store
    Alpine.store('variants', {
        hasSelectedAttributes: false,
        hasVariants: false,
        loadingAttributes: false,
        selectedCount: 0,

        setLoadingAttributes(value) {
            this.loadingAttributes = value;
            console.log('LoadingAttributes установлен в:', value);
        },

        setHasSelectedAttributes(value) {
            this.hasSelectedAttributes = value;
            console.log('HasSelectedAttributes установлен в:', value);
        },

        setHasVariants(value) {
            this.hasVariants = value;
            console.log('HasVariants установлен в:', value);
        }
    });

    console.log('Alpine store инициализирован:', Alpine.store('variants'));

    // Инициализируем уведомления
    initNotifications();
    console.log('Уведомления инициализированы');

    // Alpine компоненты
    Alpine.data('productForm', () => ({
        loading: true,
        init() {
            this.loading = false;
            console.log('Alpine компонент productForm инициализирован');
        }
    }));

    // Инициализация формы атрибутов
    if (document.querySelector('#attribute-form')) {
        await initAttributeForm();
        console.log('Форма атрибутов инициализирована');
    }

    // Инициализация страницы продукта
});

document.addEventListener('DOMContentLoaded', async () => {
    console.log('DOM загружен, начало инициализации компонентов...');

    if (document.querySelector('form[action*="products"]')) {
        console.log('Найдена форма продукта, начинаем инициализацию...');
        console.log('Текущее состояние Alpine store:', Alpine.store('variants'));
        document.body.classList.add('loading');

        try {
            window.productForm = new ProductForm();
            window.imageHandler = new ImageHandler();
            window.variantsManager = new VariantsManager();
            window.bulkEditManager = new BulkEditManager();

            // Инициализируем компоненты последовательно
            console.log('Инициализация ProductForm...');
            await window.productForm.initialize();

            console.log('Инициализация ImageHandler...');
            await window.imageHandler.initialize();

            console.log('Инициализация VariantsManager...');
            console.log('Состояние Alpine.store variants перед инициализацией VariantsManager:',
                Alpine.store('variants'));
            await window.variantsManager.initialize();
            console.log('Состояние Alpine.store variants после инициализации VariantsManager:',
                Alpine.store('variants'));

            console.log('Инициализация BulkEditManager...');
            await window.bulkEditManager.initialize();

            document.body.classList.remove('loading');
            console.log('Инициализация страницы продукта завершена успешно');
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

// Экспорты для использования в других модулях
export { Alpine,  };


