export class StatusBar {
    constructor(container, options = {}) {
        this.container = container;
        this.options = {
            onSave: options.onSave || (() => {}),
            onPublish: options.onPublish || (() => {}),
            onArchive: options.onArchive || (() => {})
        };

        this.state = {
            status: 'draft',
            lastSavedAt: null,
            isDirty: false,
            isSaving: false,
            saveError: null,
            retryCount: 0,
            isLocked: false,
            lockedBy: null,
            saveStatus: 'idle' // idle, saving, retrying, error, success
        };

        this.render();
        this.bindEvents();
    }

    render() {
        this.container.innerHTML = `
            <div class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg">
                <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            ${this.renderStatusIndicator()}
                            ${this.renderLastSaved()}
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            ${this.renderSaveStatus()}
                            ${this.renderDirtyIndicator()}
                            ${this.renderLockStatus()}
                            <div class="flex items-center space-x-2">
                                <button type="button"
                                        class="btn-save px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg relative"
                                        ${this.state.isSaving || !this.state.isDirty || this.state.isLocked ? 'disabled' : ''}>
                                    ${this.getSaveButtonText()}
                                </button>
                                
                                <button type="button"
                                        class="btn-publish px-4 py-2 bg-blue-500 text-white hover:bg-blue-600 rounded-lg"
                                        ${this.state.isDirty || this.state.status === 'published' ? 'disabled' : ''}>
                                    ${this.state.status === 'published' ? 'Опубликован' : 'Опубликовать'}
                                </button>

                                ${this.state.status !== 'draft' ? `
                                    <button type="button"
                                            class="btn-archive px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                                        Архивировать
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    renderStatusIndicator() {
        const statusConfig = {
            draft: {
                icon: `
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                `,
                text: 'Черновик'
            },
            published: {
                icon: `
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M5 13l4 4L19 7"/>
                    </svg>
                `,
                text: 'Опубликован'
            },
            archived: {
                icon: `
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                `,
                text: 'В архиве'
            }
        };

        const config = statusConfig[this.state.status];
        return `
            <div class="flex items-center space-x-2">
                ${config.icon}
                <span class="text-sm font-medium">${config.text}</span>
            </div>
        `;
    }

    renderLastSaved() {
        if (!this.state.lastSavedAt) return '';

        return `
            <div class="text-sm text-gray-500">
                Последнее сохранение: ${this.formatDate(this.state.lastSavedAt)}
            </div>
        `;
    }

    renderSaveStatus() {
        const statusConfig = {
            idle: '',
            saving: '<div class="text-sm text-blue-500">Сохранение...</div>',
            retrying: `
                <div class="text-sm text-orange-500">
                    Повторная попытка ${this.state.retryCount}...
                    <span class="animate-pulse">⌛</span>
                </div>
            `,
            error: `
                <div class="text-sm text-red-500">
                    Ошибка сохранения: ${this.state.saveError}
                </div>
            `,
            success: `
                <div class="text-sm text-green-500">
                    Успешно сохранено
                    <span class="ml-1">✓</span>
                </div>
            `
        };

        return statusConfig[this.state.saveStatus] || '';
    }

    renderDirtyIndicator() {
        if (!this.state.isDirty) return '';

        return `
            <div class="text-sm text-orange-500">
                Есть несохраненные изменения
            </div>
        `;
    }

    renderLockStatus() {
        if (!this.state.isLocked) return '';

        return `
            <div class="text-sm text-red-500">
                <div class="flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <span>Документ редактируется другим пользователем</span>
                </div>
            </div>
        `;
    }

    getSaveButtonText() {
        if (this.state.isSaving) {
            return `
                <span class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Сохранение...
                </span>
            `;
        }
        return 'Сохранить черновик';
    }

    bindEvents() {
        // Кнопка сохранения черновика
        this.container.querySelector('.btn-save')?.addEventListener('click', async () => {
            if (this.state.isSaving || !this.state.isDirty) return;
            await this.options.onSave();
        });

        // Кнопка публикации
        this.container.querySelector('.btn-publish')?.addEventListener('click', async () => {
            if (this.state.isDirty || this.state.status === 'published') return;
            await this.options.onPublish();
        });

        // Кнопка архивации
        this.container.querySelector('.btn-archive')?.addEventListener('click', async () => {
            await this.options.onArchive();
        });
    }

    setStatus(status) {
        this.state.status = status;
        this.render();
    }

    setLastSaved(date) {
        this.state.lastSavedAt = date;
        this.render();
    }

    setDirty(isDirty) {
        this.state.isDirty = isDirty;
        this.render();
    }

    setSaving(isSaving, error = null) {
        this.state.isSaving = isSaving;
        
        if (isSaving) {
            this.state.saveStatus = 'saving';
        } else if (error) {
            this.state.saveStatus = 'error';
            this.state.saveError = error.message || 'Неизвестная ошибка';
        } else {
            this.state.saveStatus = 'success';
            setTimeout(() => {
                if (this.state.saveStatus === 'success') {
                    this.state.saveStatus = 'idle';
                    this.render();
                }
            }, 3000);
        }
        
        this.render();
    }

    setRetrying(count) {
        this.state.retryCount = count;
        this.state.saveStatus = 'retrying';
        this.render();
    }

    setLocked(isLocked, lockedBy = null) {
        this.state.isLocked = isLocked;
        this.state.lockedBy = lockedBy;
        this.render();
    }

    formatDate(date) {
        return new Intl.DateTimeFormat('ru', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }).format(date);
    }
}