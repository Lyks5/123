export const initNotifications = (options = {}) => {
    const {
        duration = 3000,
        containerClass = 'notifications-container'
    } = options;

    // Удаляем старый контейнер, если он существует
    const oldContainer = document.querySelector(`.${containerClass}`);
    if (oldContainer) {
        oldContainer.remove();
    }

    // Создаем новый контейнер
    const container = document.createElement('div');
    container.className = containerClass;
    container.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 10;
    `;
    document.body.appendChild(container);

    const createNotification = (message, type = 'info') => {
        // Удаляем предыдущее уведомление
        const oldNotification = container.querySelector('.notification');
        if (oldNotification) {
            oldNotification.remove();
        }

        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.setAttribute('role', 'alert');
        notification.style.cssText = `
            background-color: ${type === 'success' ? '#10B981' : '#EF4444'};
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-width: 300px;
            
            transform: translateY(20px);
            transition: all 0.3s ease;
        `;

        const content = document.createElement('div');
        content.textContent = message;

        const closeButton = document.createElement('button');
        closeButton.innerHTML = '×';
        closeButton.style.cssText = `
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            padding: 0 0 0 12px;
        `;
        closeButton.setAttribute('aria-label', 'Закрыть уведомление');

        notification.appendChild(content);
        notification.appendChild(closeButton);

        container.appendChild(notification);

        // Анимация появления
        requestAnimationFrame(() => {
            notification.classList.add('show');
        });

        // Обработчик закрытия
        const close = () => {
            notification.classList.remove('show');
            notification.addEventListener('transitionend', () => {
                notification.remove();
            });
        };

        // Автоматическое закрытие
        const timeout = setTimeout(close, duration);

        // Обработчики событий
        closeButton.addEventListener('click', () => {
            clearTimeout(timeout);
            close();
        });

        notification.addEventListener('mouseenter', () => {
            clearTimeout(timeout);
        });

        notification.addEventListener('mouseleave', () => {
            const timeout = setTimeout(close, duration);
        });

        return {
            close,
            element: notification
        };
    };

    // Слушаем глобальное событие для показа уведомлений
    document.addEventListener('show-notification', (e) => {
        const { message, type } = e.detail;
        createNotification(message, type);
    });

    // Публичные методы
    return {
        show: (message, type) => createNotification(message, type),
        success: (message) => createNotification(message, 'success'),
        error: (message) => createNotification(message, 'error'),
        warning: (message) => createNotification(message, 'warning'),
        info: (message) => createNotification(message, 'info'),
        clear: () => {
            const notifications = container.querySelectorAll('.notification');
            notifications.forEach(notification => notification.remove());
        }
    };
};

// Вспомогательные функции для стилизации
export const getNotificationStyles = () => `
    .notifications-container {
        position: fixed;
        z-index: 9999;
        pointer-events: none;
    }
    .notifications-container.top-right {
        top: 20px;
        right: 20px;
    }
    .notifications-container.top-left {
        top: 20px;
        left: 20px;
    }
    .notifications-container.bottom-right {
        bottom: 20px;
        right: 20px;
    }
    .notifications-container.bottom-left {
        bottom: 20px;
        left: 20px;
    }
    .notification {
        pointer-events: auto;
        padding: 12px 24px;
        margin: 0 0 10px;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        min-width: 280px;
        max-width: 520px;
        transform: translateX(120%);
        transition: transform 0.3s ease-in-out;
    }
    .notification.show {
        transform: translateX(0);
    }
`;
