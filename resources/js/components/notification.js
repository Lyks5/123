export class NotificationManager {
    constructor() {
        this.container = document.getElementById('notifications');
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.id = 'notifications';
            this.container.className = 'fixed bottom-4 right-4 z-50 space-y-2';
            document.body.appendChild(this.container);
        }

        this.initializeEventListeners();
    }

    initializeEventListeners() {
        window.addEventListener('show-notification', (event) => {
            this.show(event.detail.type, event.detail.message);
        });
    }

    show(type, message) {
        const notification = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        
        notification.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center justify-between transform transition-all duration-300 ease-in-out opacity-0 translate-x-full`;
        
        notification.innerHTML = `
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${type === 'success' 
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                    }
                </svg>
                <span>${message}</span>
            </div>
            <button class="ml-4 hover:text-gray-200" onclick="this.parentElement.remove()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;

        this.container.appendChild(notification);

        // Анимация появления
        requestAnimationFrame(() => {
            notification.classList.remove('opacity-0', 'translate-x-full');
        });

        // Автоматическое скрытие через 5 секунд
        setTimeout(() => {
            notification.classList.add('opacity-0', 'translate-x-full');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 5000);
    }
}

// Создаем глобальный экземпляр менеджера уведомлений
export const initNotifications = () => {
    window.notificationManager = new NotificationManager();
};