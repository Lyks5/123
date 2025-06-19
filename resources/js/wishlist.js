document.addEventListener('DOMContentLoaded', function() {
    // Показываем уведомление после удаления из избранного (после reload)
    if (sessionStorage.getItem('wishlist-removed')) {
        if (window.notificationManager) {
            window.notificationManager.show('success', 'Товар удалён из избранного');
        }
        sessionStorage.removeItem('wishlist-removed');
    }
    // Обработчик для кнопки добавления/удаления на странице товара
    const wishlistBtn = document.getElementById('wishlist-btn');
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', handleWishlistToggle);
    }

    // Обработчик для кнопок удаления на странице избранного
    const removeButtons = document.querySelectorAll('.wishlist-remove-btn');
    removeButtons.forEach(button => {
        button.addEventListener('click', handleWishlistRemove);
    });
});

function handleWishlistToggle(event) {
    const productId = this.dataset.productId;
    toggleWishlistItem(productId);
}

function handleWishlistRemove(event) {
    event.stopPropagation();
    event.preventDefault();
    const productId = this.dataset.productId;
    toggleWishlistItem(productId, true);
}

function toggleWishlistItem(productId, isRemove = false) {
    const token = document.querySelector('meta[name="csrf-token"]').content;

    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        
        if (isRemove) {
            // При удалении показываем уведомление и обновляем страницу
            // После успешного удаления сразу обновляем страницу
            // Сохраняем флаг для показа уведомления после перезагрузки
            sessionStorage.setItem('wishlist-removed', '1');
            location.reload();
        } else {
            // Обновляем состояние кнопки на странице товара
            const wishlistBtn = document.getElementById('wishlist-btn');
            if (wishlistBtn) {
                if (data.status) {
                    wishlistBtn.classList.add('bg-eco-50/80', 'text-yellow-500');
                    wishlistBtn.classList.remove('text-eco-600');
                    wishlistBtn.title = 'Товар в избранном';
                    // Меняем иконку на закрашенную
                    const svg = wishlistBtn.querySelector('svg');
                    if (svg) {
                        svg.setAttribute('fill', 'currentColor');
                        svg.style.color = '#facc15';
                    }
                } else {
                    wishlistBtn.classList.remove('bg-eco-50/80', 'text-yellow-500');
                    wishlistBtn.classList.add('text-eco-600');
                    wishlistBtn.title = 'Добавить в избранное';
                    // Меняем иконку на пустую
                    const svg = wishlistBtn.querySelector('svg');
                    if (svg) {
                        svg.setAttribute('fill', 'none');
                        svg.style.color = '';
                    }
                }
            }
            // Показываем уведомление
            if (window.notificationManager) {
                if (data.status) {
                    window.notificationManager.show('success', 'Товар добавлен в избранное');
                } else {
                    window.notificationManager.show('success', 'Товар удалён из избранного');
                }
            }
        }
    })
    .catch(error => {
        
    });
}