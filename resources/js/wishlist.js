import { initWishlist } from './components/product/wishlist';

document.addEventListener('DOMContentLoaded', () => {
    // Инициализация для кнопки на странице товара
    const wishlistBtn = document.getElementById('wishlist-btn');
    if (wishlistBtn) {
        const productId = wishlistBtn.dataset.productId;
        initWishlist(productId);
    }

    // Инициализация для кнопок на странице избранного
    const removeButtons = document.querySelectorAll('.wishlist-remove-btn');
    removeButtons.forEach(button => {
        const productId = button.dataset.productId;
        initWishlist(productId);
    });
});