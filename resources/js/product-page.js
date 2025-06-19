import { initGallery } from './components/product/gallery';
import { initQuantityControl } from './components/product/quantity';
import { initTabs } from './components/product/tabs';
import { initRating } from './components/product/rating';
import { initWishlist } from './components/product/wishlist';
import { initShare } from './components/product/share';
import { initNotifications } from './components/product/notifications';

// Состояние корзины
export const cartState = {
    items: [],
    total: 0
};

// Обработчик обновления корзины
export const handleCartUpdated = async (productId, quantity) => {
    try {
        const response = await fetch('/api/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ productId, quantity })
        });

        if (!response.ok) throw new Error('Ошибка обновления корзины');
        
        const data = await response.json();
        cartState.items = data.items;
        cartState.total = data.total;

        // Вызываем событие обновления корзины
        document.dispatchEvent(new CustomEvent('cart-updated', { 
            detail: { items: cartState.items, total: cartState.total }
        }));

        return data;
    } catch (error) {
        console.error('Cart update error:', error);
        throw error;
    }
};

// Инициализация всех компонентов страницы
export const init = (productName, productId) => {
    window.productId = productId;
    const gallery = initGallery();
    
    const quantity = initQuantityControl({
        onChange: (value) => {
            handleCartUpdated(window.productId, value)
                .catch(error => {
                    console.error('Failed to update cart:', error);
                    notifications.error('Не удалось обновить корзину');
                });
        }
    });

    const notifications = initNotifications({
        position: 'top-right',
        duration: 3000
    });

    const tabs = initTabs();
    
    const rating = initRating({
        readOnly: !window.isAuthenticated,
        initialRating: window.productRating || 0
    });

    const wishlist = initWishlist(window.productId);
    
    const share = initShare({
        title: document.querySelector('h1').textContent,
        description: document.querySelector('meta[name="description"]')?.content,
        image: document.querySelector('.product-gallery-image')?.src
    });

    return {
        gallery,
        quantity,
        tabs,
        rating,
        wishlist,
        share,
        notifications
    };
};

// Инициализация при загрузке DOM
document.addEventListener('DOMContentLoaded', init);