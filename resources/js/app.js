import './bootstrap';
// Уведомления для wishlist и других действий
import { NotificationManager } from './components/notification';
window.notificationManager = new NotificationManager();

// Импортировать React компоненты
import './app.tsx';
