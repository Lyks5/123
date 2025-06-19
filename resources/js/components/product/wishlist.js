const WISHLIST_STORAGE_KEY = 'product_wishlist';

export const initWishlist = (productId) => {
    const wishlistButton = document.querySelector('.wishlist-button');
    
    if (!wishlistButton) {
        console.error('Wishlist button not found');
        return null;
    }

    const getWishlist = () => {
        const stored = localStorage.getItem(WISHLIST_STORAGE_KEY);
        return stored ? JSON.parse(stored) : [];
    };

    const saveWishlist = (wishlist) => {
        localStorage.setItem(WISHLIST_STORAGE_KEY, JSON.stringify(wishlist));
    };

    const isInWishlist = () => {
        return getWishlist().includes(productId);
    };

    const updateButtonState = () => {
        if (isInWishlist()) {
            wishlistButton.classList.add('active');
            wishlistButton.setAttribute('aria-label', 'Удалить из избранного');
        } else {
            wishlistButton.classList.remove('active');
            wishlistButton.setAttribute('aria-label', 'Добавить в избранное');
        }
    };

    const toggleWishlist = () => {
        const wishlist = getWishlist();
        const index = wishlist.indexOf(productId);

        if (index === -1) {
            wishlist.push(productId);
            dispatchWishlistEvent('added');
        } else {
            wishlist.splice(index, 1);
            dispatchWishlistEvent('removed');
        }

        saveWishlist(wishlist);
        updateButtonState();
    };

    const dispatchWishlistEvent = (action) => {
        const event = new CustomEvent('wishlist-change', {
            detail: {
                productId,
                action,
                wishlistCount: getWishlist().length
            }
        });
        document.dispatchEvent(event);
    };

    // Добавляем обработчик клика
    wishlistButton.addEventListener('click', toggleWishlist);

    // Устанавливаем начальное состояние
    updateButtonState();

    // Публичные методы
    return {
        isInWishlist,
        toggle: toggleWishlist,
        getWishlistItems: getWishlist
    };
};
