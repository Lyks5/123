export const initWishlist = (productId) => {
    const wishlistButton = document.querySelector('#wishlist-btn');
    
    if (!wishlistButton) {
        console.error('Wishlist button not found');
        return null;
    }

    const toggleWishlist = async () => {
        try {
            // Добавляем класс загрузки
            wishlistButton.classList.add('animate-pulse');
            wishlistButton.disabled = true;

            const response = await fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ product_id: productId })
            });

            const data = await response.json();

            // Показываем уведомление
            showNotification(data.inWishlist ? 'Товар добавлен в избранное' : 'Товар удален из избранного', 'success');

            // Если мы на странице избранного и товар удален - перезагружаем страницу
            if (!data.inWishlist && window.location.pathname.includes('/account/wishlists')) {
                setTimeout(() => {
                    window.location.reload();
                }, 1000); // Перезагружаем через 1 секунду, чтобы успело показаться уведомление
                return;
            }

            // Обновляем внешний вид кнопки
            const icon = wishlistButton.querySelector('svg');
            
            if (data.inWishlist) {
                wishlistButton.classList.add('text-yellow-500', 'bg-eco-50/80');
                icon.setAttribute('fill', 'currentColor');
            } else {
                wishlistButton.classList.remove('text-yellow-500', 'bg-eco-50/80');
                icon.setAttribute('fill', 'none');
            }

        } catch (error) {
            showNotification('Не удалось обновить избранное', 'error');
        } finally {
            // Убираем класс загрузки
            wishlistButton.classList.remove('animate-pulse');
            wishlistButton.disabled = false;
        }
    };

    const showNotification = (message, type) => {
        const notification = document.createElement('div');
        notification.className = `fixed bottom-4 right-4 p-4 rounded-lg shadow-lg ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white z-50`;
        notification.textContent = message;
        document.body.appendChild(notification);

        // Удаляем уведомление через 3 секунды
        setTimeout(() => {
            notification.remove();
        }, 3000);
    };

    // Добавляем обработчик клика
    wishlistButton.addEventListener('click', toggleWishlist);
};
