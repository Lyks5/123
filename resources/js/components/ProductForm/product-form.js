import { FormValidator } from './form-validator.js';
import { ImageManager } from './image-manager.js';
import { AttributesManager } from './attributes-manager.js';
import { StatusBar } from './status-bar.js';
import { AutoSave } from './auto-save.js';

export class ProductForm {
    constructor(formElement, options = {}) {
        this.form = formElement;
        this.options = {
            onSave: options.onSave || (() => {}),
            onPublish: options.onPublish || (() => {}),
            initialData: options.initialData || {},
            autosaveInterval: options.autosaveInterval || 300000, // 5 минут
            maxRetries: options.maxRetries || 3,
            retryDelay: options.retryDelay || 5000
        };

        this.tabs = {
            basic: document.querySelector('[data-tab="basic"]'),
            images: document.querySelector('[data-tab="images"]'),
            pricing: document.querySelector('[data-tab="pricing"]'),
            attributes: document.querySelector('[data-tab="attributes"]')
        };

        // Инициализация компонентов
        this.validator = new FormValidator(this.form);
        this.imageManager = new ImageManager(
            document.querySelector('.image-manager'), 
            { maxImages: 8 }
        );
        this.attributesManager = new AttributesManager(
            document.querySelector('.attributes-section')
        );
        this.statusBar = new StatusBar(
            document.querySelector('.status-bar'),
            { onSave: () => this.save(), onPublish: () => this.publish() }
        );

        // Автосохранение
        this.autoSave = new AutoSave({
            interval: this.options.autosaveInterval,
            onSave: () => this.save(),
            isDirty: () => this.validator.isDirty(),
            maxRetries: this.options.maxRetries,
            retryDelay: this.options.retryDelay,
            onSaveError: (error) => {
                this.statusBar.setSaving(false, error);
                this.showToast('Ошибка автосохранения: ' + error.message, 'error');
            },
            onLockError: () => {
                this.statusBar.setLocked(true);
                this.showToast('Документ редактируется другим пользователем', 'warning');
            }
        });

        this.initializeForm();
        this.bindEvents();
    }

    initializeForm() {
        // Заполняем форму начальными данными
        if (this.options.initialData) {
            Object.entries(this.options.initialData).forEach(([key, value]) => {
                const input = this.form.querySelector(`[name="${key}"]`);
                if (input) {
                    input.value = value;
                }
            });
        }

        // Инициализируем табы
        this.initTabs();
    }

    initTabs() {
        const tabButtons = document.querySelectorAll('[data-tab-trigger]');
        const tabContents = document.querySelectorAll('[data-tab-content]');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tabName = button.dataset.tabTrigger;
                
                // Деактивируем все табы
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                
                // Активируем выбранный таб
                button.classList.add('active');
                document.querySelector(`[data-tab-content="${tabName}"]`)
                    .classList.add('active');
            });
        });

        // Активируем первый таб по умолчанию
        tabButtons[0]?.click();
    }

    bindEvents() {
        // Обработка отправки формы
        this.form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (await this.validate()) {
                this.save();
            }
        });

        // Отслеживание изменений формы
        this.form.addEventListener('input', () => {
            this.statusBar.setDirty(true);
            this.validator.validateField(e.target);
        });

        // Предупреждение при закрытии страницы с несохраненными изменениями
        window.addEventListener('beforeunload', (e) => {
            if (this.validator.isDirty()) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    }

    async validate() {
        return this.validator.validateAll();
    }

    async save() {
        if (this.statusBar.isSaving || this.statusBar.isLocked) return;

        try {
            this.statusBar.setSaving(true);
            const formData = this.getFormData();
            
            // Проверяем блокировку перед сохранением
            if (this.autoSave.lockId || await this.autoSave.acquireLock()) {
                await this.options.onSave(formData);
                
                this.statusBar.setLastSaved(new Date());
                this.statusBar.setDirty(false);
                this.statusBar.setSaving(false);
                this.showToast('Черновик сохранен', 'success');
            } else {
                throw new Error('Документ заблокирован другим пользователем');
            }
        } catch (error) {
            const isNetworkError = error instanceof TypeError && !navigator.onLine;
            if (isNetworkError) {
                this.statusBar.setSaving(false, new Error('Ошибка сети'));
            } else {
                this.statusBar.setSaving(false, error);
            }
            
            console.error('Ошибка сохранения:', error);
            this.showToast(error.message || 'Ошибка при сохранении', 'error');
        }
    }

    async publish() {
        if (!await this.validate()) return;

        try {
            const formData = this.getFormData();
            await this.options.onPublish(formData);
            this.showToast('Товар опубликован');
        } catch (error) {
            this.showToast('Ошибка при публикации', 'error');
            console.error('Ошибка публикации:', error);
        }
    }

    getFormData() {
        const formData = new FormData(this.form);
        const data = Object.fromEntries(formData);

        // Добавляем данные из компонентов
        return {
            ...data,
            images: this.imageManager.getImages(),
            attributes: this.attributesManager.getAttributes(),
            variants: this.attributesManager.getVariants()
        };
    }

    showToast(message, type = 'success') {
        const icons = {
            success: '✓',
            error: '⚠',
            warning: '⚠'
        };

        // Создаем и показываем уведомление
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="flex items-center space-x-2">
                <span class="toast-icon">${icons[type] || ''}</span>
                <span class="toast-message">${message}</span>
            </div>
        `;
        document.body.appendChild(toast);

        // Добавляем класс для анимации
        requestAnimationFrame(() => {
            toast.classList.add('toast-visible');
        });

        // Удаляем через 3 секунды
        setTimeout(() => {
            toast.classList.remove('toast-visible');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
}