document.addEventListener('DOMContentLoaded', function() {
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
            location.reload();
        } else {
            // Обновляем состояние кнопки на странице товара
            const wishlistBtn = document.getElementById('wishlist-btn');
            if (wishlistBtn) {
                if (data.status) {
                    wishlistBtn.classList.add('bg-eco-50/80', 'text-eco-600');
                    wishlistBtn.title = 'Товар в избранном';
                } else {
                    wishlistBtn.classList.remove('bg-eco-50/80', 'text-eco-600');
                    wishlistBtn.title = 'Добавить в избранное';
                }
            }
            // Показываем уведомление
            if (window.notificationManager) {
                window.notificationManager.show('success', data.message);
            }
        }
    })
    .catch(error => {
        
    });
}