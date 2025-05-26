export class AutoSave {
    constructor(options = {}) {
        this.options = {
            interval: options.interval || 300000, // 5 минут по умолчанию
            onSave: options.onSave || (() => {}),
            isDirty: options.isDirty || (() => false),
            maxRetries: options.maxRetries || 3,
            retryDelay: options.retryDelay || 5000,
            lockTimeout: options.lockTimeout || 300000 // 5 минут
        };

        this.timer = null;
        this.isActive = false;
        this.retryCount = 0;
        this.lockId = null;
        this.lastSaveAttempt = null;

        this.initialize();
    }

    initialize() {
        // Запускаем таймер автосохранения
        this.start();

        // Добавляем обработчик перед закрытием страницы
        window.addEventListener('beforeunload', this.handleBeforeUnload.bind(this));
    }

    start() {
        if (this.timer) {
            this.stop();
        }

        this.isActive = true;
        this.timer = setInterval(async () => {
            await this.checkAndSave();
        }, this.options.interval);
    }

    stop() {
        if (this.timer) {
            clearInterval(this.timer);
            this.timer = null;
        }
        this.isActive = false;
    }

    async acquireLock() {
        const lockData = {
            id: crypto.randomUUID(),
            timestamp: Date.now()
        };
        
        try {
            const existingLock = localStorage.getItem('product_edit_lock');
            if (existingLock) {
                const lock = JSON.parse(existingLock);
                if (Date.now() - lock.timestamp < this.options.lockTimeout) {
                    return false;
                }
            }
            
            localStorage.setItem('product_edit_lock', JSON.stringify(lockData));
            this.lockId = lockData.id;
            return true;
        } catch (error) {
            console.error('Ошибка при получении блокировки:', error);
            return false;
        }
    }

    releaseLock() {
        if (this.lockId) {
            const existingLock = JSON.parse(localStorage.getItem('product_edit_lock') || '{}');
            if (existingLock.id === this.lockId) {
                localStorage.removeItem('product_edit_lock');
            }
            this.lockId = null;
        }
    }

    async checkAndSave() {
        if (!this.isActive) return;

        // Проверяем наличие блокировки
        if (!this.lockId && !(await this.acquireLock())) {
            console.warn('Документ редактируется другим пользователем');
            this.options.onLockError?.();
            return;
        }

        // Проверяем, есть ли несохраненные изменения
        if (this.options.isDirty()) {
            try {
                this.lastSaveAttempt = Date.now();
                await this.options.onSave();
                this.retryCount = 0; // Сбрасываем счетчик после успешного сохранения
            } catch (error) {
                console.error('Ошибка автосохранения:', error);
                
                if (this.retryCount < this.options.maxRetries) {
                    this.retryCount++;
                    console.log(`Повторная попытка ${this.retryCount}/${this.options.maxRetries}`);
                    
                    // Запускаем повторную попытку с задержкой
                    setTimeout(() => {
                        this.checkAndSave();
                    }, this.options.retryDelay);
                } else {
                    this.options.onSaveError?.(error);
                    this.retryCount = 0; // Сбрасываем счетчик после исчерпания попыток
                }
            }
        }
    }

    handleBeforeUnload(event) {
        // Если есть несохраненные изменения, показываем предупреждение
        if (this.options.isDirty()) {
            event.preventDefault();
            event.returnValue = '';
            return '';
        }
    }

    destroy() {
        this.stop();
        this.releaseLock();
        window.removeEventListener('beforeunload', this.handleBeforeUnload);
    }
}