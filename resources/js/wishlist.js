document.addEventListener('DOMContentLoaded', function() {
    const wishlistBtn = document.getElementById('wishlist-btn');
    if (!wishlistBtn) return;

    wishlistBtn.addEventListener('click', function() {
        const productId = this.dataset.productId;
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
        .then(response => response.json())
        .then(data => {
            // Обновляем состояние кнопки
            if (data.status) {
                wishlistBtn.classList.add('bg-eco-50/80', 'text-eco-600');
                wishlistBtn.title = 'Товар в избранном';
            } else {
                wishlistBtn.classList.remove('bg-eco-50/80', 'text-eco-600');
                wishlistBtn.title = 'Добавить в избранное';
            }

            // Показываем уведомление
            if (window.EcoStoreNotification) {
                window.EcoStoreNotification.show({
                    message: data.message,
                    type: 'success'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (window.EcoStoreNotification) {
                window.EcoStoreNotification.show({
                    message: 'Произошла ошибка при обновлении избранного',
                    type: 'error'
                });
            }
        });
    });
});