/**
 * Эко-магазин: функциональные JS для страницы продукта
 * Поддерживает табы, галерею с зумом, рейтинги, избранное, анимации.
 */
import { initNotifications } from './components/notification';

const ProductPage = (() => {
    // Инициализируем менеджер уведомлений
    initNotifications();

    // Состояние корзины
    const cartState = {
        isLoading: false
    };

    // Обработчик успешного добавления в корзину
    function handleCartUpdated(data) {
        window.dispatchEvent(new CustomEvent('cart-updated', {
            detail: {
                count: data.cartCount || 0
            }
        }));
        showNotification(data.message || 'Товар успешно добавлен в корзину', true);
    }

    // Улучшенный кеш для предзагрузки изображений с обработкой ошибок
    const imageCache = {
        cache: new Map(),
        maxSize: 50, // Максимальное количество кешированных изображений
        
        async preload(src) {
            if (this.cache.has(src)) {
                return this.cache.get(src);
            }

            try {
                const img = new Image();
                const loadPromise = new Promise((resolve, reject) => {
                    img.onload = () => resolve(img);
                    img.onerror = () => reject(new Error(`Failed to load image: ${src}`));
                });

                img.src = src;
                await loadPromise;

                // Очистка кеша если превышен лимит
                if (this.cache.size >= this.maxSize) {
                    const firstKey = this.cache.keys().next().value;
                    this.cache.delete(firstKey);
                }

                this.cache.set(src, img);
                return img;
            } catch (error) {
                console.error('Image preload error:', error);
                throw error;
            }
        },

        clear() {
            this.cache.clear();
        }
    };

    function openTab(tabName) {
        const tabContents = document.querySelectorAll('.tab-content');
        const newTab = document.getElementById('content-' + tabName);
        
        // Плавное скрытие старого контента
        tabContents.forEach(content => {
            if (content.classList.contains('active')) {
                content.style.opacity = '0';
                content.addEventListener('transitionend', () => {
                    content.classList.remove('active');
                    content.classList.add('hidden');
                }, { once: true });
            }
        });

        // Обновляем состояние табов
        document.querySelectorAll('[role="tab"]').forEach(tab => {
            const isSelected = tab.getAttribute('data-tab') === tabName;
            tab.classList.toggle('border-eco-600', isSelected);
            tab.classList.toggle('text-eco-900', isSelected);
            tab.classList.toggle('border-transparent', !isSelected);
            tab.classList.toggle('text-eco-600', !isSelected);
            tab.setAttribute('aria-selected', isSelected.toString());
        });

        // Показываем новый контент с анимацией
        requestAnimationFrame(() => {
            newTab.classList.remove('hidden');
            newTab.style.opacity = '0';
            requestAnimationFrame(() => {
                newTab.classList.add('active');
                newTab.style.opacity = '1';
            });
        });
    }
  
    function setRating(rating) {
        document.getElementById('rating').value = rating;
        document.querySelectorAll('.rating-star').forEach((star, index) => {
            star.classList.toggle('text-yellow-400', index < rating);
            star.classList.toggle('text-gray-300', index >= rating);
            // Добавляем анимацию при изменении
            star.style.transform = 'scale(1.2)';
            setTimeout(() => star.style.transform = 'scale(1)', 200);
        });
    }
  
    function updateQuantity(increment) {
        console.log('updateQuantity вызван:', increment);
        
        const quantityInput = document.getElementById('quantity');
        if (!quantityInput) {
            console.error('Элемент quantity не найден');
            return;
        }

        const currentValue = parseInt(quantityInput.value) || 1;
        const maxQuantity = parseInt(quantityInput.getAttribute('data-max-quantity')) || 99;
        
        console.log('Текущее значение:', currentValue, 'Максимум:', maxQuantity);
        
        let newValue;
        if (increment) {
            if (currentValue >= maxQuantity) {
                showQuantityError(`Максимальное доступное количество: ${maxQuantity}`);
                return;
            }
            newValue = currentValue + 1;
        } else {
            if (currentValue <= 1) {
                showQuantityError('Минимальное количество: 1');
                return;
            }
            newValue = currentValue - 1;
        }
        
        if (newValue !== currentValue) {
            // Анимация при изменении
            quantityInput.style.transform = 'scale(0.95)';
            quantityInput.value = newValue;
            
            // Анимация кнопок
            const incButton = document.getElementById('inc-qty');
            const decButton = document.getElementById('dec-qty');
            
            incButton.disabled = newValue >= maxQuantity;
            decButton.disabled = newValue <= 1;
            
            incButton.classList.toggle('opacity-50', newValue >= maxQuantity);
            decButton.classList.toggle('opacity-50', newValue <= 1);
            
            setTimeout(() => quantityInput.style.transform = 'scale(1)', 150);
            
            // Вызываем событие изменения для обновления UI
            quantityInput.dispatchEvent(new Event('change'));
        }
    }

    function showQuantityError(message) {
        const container = document.querySelector('.quantity-container');
        
        // Удаляем предыдущее сообщение об ошибке, если оно есть
        const existingError = container.querySelector('.quantity-error');
        if (existingError) {
            existingError.remove();
        }
        
        // Создаем новое сообщение об ошибке
        const error = document.createElement('div');
        error.className = 'quantity-error text-red-500 text-sm mt-1 transform scale-0';
        error.textContent = message;
        
        // Добавляем сообщение после инпута
        container.appendChild(error);
        
        // Анимируем появление
        requestAnimationFrame(() => {
            error.style.transform = 'scale(1)';
            error.style.transition = 'transform 0.2s ease-out';
        });
        
        // Добавляем визуальную индикацию на input
        const quantityInput = document.getElementById('quantity');
        quantityInput.classList.add('border-red-500');
        
        // Удаляем сообщение и индикацию через 3 секунды
        setTimeout(() => {
            error.style.transform = 'scale(0)';
            error.addEventListener('transitionend', () => error.remove());
            quantityInput.classList.remove('border-red-500');
        }, 3000);
    }
  
    function initializeZoom(mainImage) {
        let isZoomed = false;
        let touch = null;

        mainImage.addEventListener('click', (e) => {
            if (!isZoomed) {
                mainImage.style.transform = 'scale(2)';
                isZoomed = true;
            } else {
                mainImage.style.transform = 'scale(1)';
                isZoomed = false;
            }
        });

        // Поддержка перемещения при зуме на мобильных
        mainImage.addEventListener('touchstart', (e) => {
            if (isZoomed) {
                touch = e.touches[0];
            }
        }, { passive: true });

        mainImage.addEventListener('touchmove', (e) => {
            if (isZoomed && touch) {
                e.preventDefault();
                const deltaX = e.touches[0].pageX - touch.pageX;
                const deltaY = e.touches[0].pageY - touch.pageY;
                mainImage.style.transform = `scale(2) translate(${deltaX}px, ${deltaY}px)`;
            }
        });

        mainImage.addEventListener('touchend', () => {
            touch = null;
        });
    }

    async function updateMainImage(thumbnailImg) {
        const mainImage = document.querySelector('.product-gallery img[x-bind\\:src]');
        if (!mainImage) {
            console.error('Main image element not found');
            return;
        }

        const newSrc = thumbnailImg.getAttribute('src');
        if (!newSrc) {
            console.error('New image source not found');
            return;
        }

        try {
            // Показываем индикатор загрузки
            const loadingIndicator = mainImage.closest('.relative').querySelector('[x-show="isLoading"]');
            if (loadingIndicator) {
                loadingIndicator.style.display = 'flex';
            }

            // Предзагружаем изображение через кеш
            await imageCache.preload(newSrc);

            // Анимируем смену изображения
            mainImage.style.opacity = '0';
            mainImage.style.transform = 'scale(0.95)';

            // Используем Alpine.js для обновления источника
            mainImage._x_dataStack[0].activeImage = newSrc;

            requestAnimationFrame(() => {
                mainImage.style.opacity = '1';
                mainImage.style.transform = 'scale(1)';
            });

            // Обновляем активное состояние миниатюр
            document.querySelectorAll('.product-gallery-thumbs button').forEach(thumb => {
                const isActive = thumb.querySelector('img').src === newSrc;
                thumb.classList.toggle('border-eco-400', isActive);
                thumb.classList.toggle('border-transparent', !isActive);
                if (isActive) {
                    thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                }
            });

        } catch (error) {
            console.error('Failed to update main image:', error);
            // Показываем уведомление об ошибке
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg transform transition-transform duration-300';
            notification.textContent = 'Не удалось загрузить изображение';
            document.body.appendChild(notification);
            
            requestAnimationFrame(() => notification.style.transform = 'translateY(20px)');
            setTimeout(() => {
                notification.style.transform = 'translateY(-100%)';
                notification.addEventListener('transitionend', () => notification.remove());
            }, 3000);
        } finally {
            // Скрываем индикатор загрузки
            const loadingIndicator = mainImage.closest('.relative').querySelector('[x-show="isLoading"]');
            if (loadingIndicator) {
                loadingIndicator.style.display = 'none';
            }
        }
    }
  
    async function addToWishlist(productId) {
        const wishlistBtn = document.getElementById('wishlist-btn');
        if (!wishlistBtn) return;

        const initialClasses = [...wishlistBtn.classList];
        const initialInnerHTML = wishlistBtn.innerHTML;

        try {
            wishlistBtn.disabled = true;
            wishlistBtn.classList.add('opacity-50');
            
            // Добавляем индикатор загрузки
            wishlistBtn.innerHTML = '<svg class="animate-spin h-5 w-5 text-eco-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';

            const response = await fetch('/wishlist/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_id: productId })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            // Восстанавливаем исходное содержимое кнопки
            wishlistBtn.innerHTML = initialInnerHTML;
            
            // Обновляем состояние и стили кнопки
            if (data.success) {
                wishlistBtn.classList.remove(...initialClasses);
                wishlistBtn.classList.add('bg-eco-50/80', 'text-eco-600');
                const icon = wishlistBtn.querySelector('svg');
                if (icon) {
                    icon.classList.add('fill-eco-500');
                }
                wishlistBtn.setAttribute('title', 'Товар в избранном');
            } else {
                // Возвращаем исходные классы если товар уже в избранном
                wishlistBtn.classList.remove(...wishlistBtn.classList);
                initialClasses.forEach(cls => wishlistBtn.classList.add(cls));
            }
            
            // Показываем уведомление
            showNotification(data.message, data.success);

        } catch (error) {
            console.error('Ошибка при добавлении в избранное:', error);
            
            // Восстанавливаем исходное состояние кнопки
            wishlistBtn.innerHTML = initialInnerHTML;
            wishlistBtn.classList.remove(...wishlistBtn.classList);
            initialClasses.forEach(cls => wishlistBtn.classList.add(cls));
            
            showNotification('Произошла ошибка при добавлении в избранное', false);
        } finally {
            wishlistBtn.disabled = false;
            wishlistBtn.classList.remove('opacity-50');
        }
    }

    function showNotification(message, isSuccess = true) {
        if (window.notificationManager) {
            window.notificationManager.show(isSuccess ? 'success' : 'error', message);
        } else {
            console.error('NotificationManager not initialized');
        }
    }
  
    async function shareProduct(productName) {
        try {
            if (navigator.share) {
                await navigator.share({
                    title: productName,
                    text: "Посмотрите этот товар: " + productName,
                    url: window.location.href,
                });
            } else {
                // Копируем ссылку в буфер обмена
                await navigator.clipboard.writeText(window.location.href);
                
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-eco-600 text-white px-6 py-3 rounded-lg transform transition-transform duration-300';
                notification.textContent = 'Ссылка скопирована в буфер обмена';
                
                document.body.appendChild(notification);
                requestAnimationFrame(() => notification.style.transform = 'translateY(20px)');
                
                setTimeout(() => {
                    notification.style.transform = 'translateY(-100%)';
                    notification.addEventListener('transitionend', () => notification.remove());
                }, 3000);
            }
        } catch (error) {
            console.error('Ошибка при попытке поделиться:', error);
        }
    }
  
    function init(productName) {
        console.log('Инициализация страницы продукта');
        
        // Инициализация базового функционала
        openTab('description');

        // Слушаем событие успешного добавления в корзину
        document.addEventListener('cart-success', (event) => handleCartUpdated(event.detail));
        
        // Lazy loading для изображений
        const imageObserver = new IntersectionObserver(async (entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        try {
                            await imageCache.preload(img.dataset.src);
                            img.src = img.dataset.src;
                        } catch (error) {
                            console.error('Failed to lazy load image:', error);
                            img.src = '/images/placeholder-product.jpg';
                        } finally {
                            imageObserver.unobserve(img);
                        }
                    }
                }
            }
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });

        // Инициализация зума для главного изображения
        const mainImage = document.getElementById('main-product-image');
        if (mainImage) {
            initializeZoom(mainImage);
        }

        // Обработчики для табов
        document.querySelectorAll('[role="tab"]').forEach(tab => {
            tab.addEventListener('click', () => openTab(tab.getAttribute('data-tab')));
            tab.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openTab(tab.getAttribute('data-tab'));
                }
            });
        });

        // Улучшенная обработка галереи для мобильных
        const gallery = document.querySelector('.product-gallery-thumbs');
        if (gallery) {
            let startX, currentX, isDragging = false;

            const handleStart = (e) => {
                startX = e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
                currentX = gallery.scrollLeft;
                isDragging = true;
                gallery.style.cursor = 'grabbing';
            };

            const handleMove = (e) => {
                if (!isDragging) return;
                e.preventDefault();
                const x = e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
                const walk = (startX - x) * 2;
                gallery.scrollLeft = currentX + walk;
            };

            const handleEnd = () => {
                isDragging = false;
                gallery.style.cursor = 'grab';
            };

            gallery.addEventListener('mousedown', handleStart);
            gallery.addEventListener('touchstart', handleStart, { passive: true });
            gallery.addEventListener('mousemove', handleMove);
            gallery.addEventListener('touchmove', handleMove);
            gallery.addEventListener('mouseup', handleEnd);
            gallery.addEventListener('touchend', handleEnd);
            gallery.addEventListener('mouseleave', handleEnd);
        }

        // Обработчики для миниатюр
        document.querySelectorAll('.product-thumbnail').forEach(img => {
            img.addEventListener('click', function() { updateMainImage(this); });
            img.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    updateMainImage(this);
                }
            });
        });

        // Кнопки действий
        const shareBtn = document.getElementById('share-btn');
        if (shareBtn) {
            shareBtn.addEventListener('click', () => shareProduct(productName));
        }

        const wishlistBtn = document.getElementById('wishlist-btn');
        if (wishlistBtn) {
            wishlistBtn.addEventListener('click', () => {
                const productId = wishlistBtn.getAttribute('data-product-id');
                if (productId) {
                    addToWishlist(productId);
                } else {
                    console.error('Product ID not found in wishlist button');
                    showNotification('Ошибка: ID товара не найден', false);
                }
            });
        }

        // Инициализация поля количества товара
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Инициализация поля количества');
            
            const quantityInput = document.getElementById('quantity');
            if (!quantityInput) {
                console.error('Поле quantity не найдено');
                return;
            }

            // Устанавливаем значение по умолчанию
            quantityInput.value = '1';

            // Обработка ручного ввода
            quantityInput.addEventListener('input', () => {
                let value = parseInt(quantityInput.value) || 0;
                const maxQuantity = parseInt(quantityInput.getAttribute('data-max-quantity')) || 99;
                
                // Ограничиваем значение
                if (value < 1) value = 1;
                if (value > maxQuantity) {
                    value = maxQuantity;
                    showQuantityError(`Максимальное доступное количество: ${maxQuantity}`);
                }
                
                // Обновляем значение
                quantityInput.value = value;
                
                // Обновляем состояние кнопок
                const incButton = document.getElementById('inc-qty');
                const decButton = document.getElementById('dec-qty');
                
                if (incButton && decButton) {
                    decButton.disabled = value <= 1;
                    decButton.classList.toggle('opacity-50', value <= 1);
                    
                    incButton.disabled = value >= maxQuantity;
                    incButton.classList.toggle('opacity-50', value >= maxQuantity);
                }
            });

            // Предотвращаем отправку формы при нажатии Enter
            quantityInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    quantityInput.blur();
                }
            });
            
            // Устанавливаем начальные состояния кнопок
            const maxQuantity = parseInt(quantityInput.getAttribute('data-max-quantity')) || 99;
            console.log('Максимальное количество:', maxQuantity);
            
            const incButton = document.getElementById('inc-qty');
            const decButton = document.getElementById('dec-qty');
            
            if (!incButton || !decButton) {
                console.error('Кнопки управления количеством не найдены');
                return;
            }
            
            // Устанавливаем начальные состояния
            const currentValue = parseInt(quantityInput.value) || 1;
            console.log('Текущее значение:', currentValue);
            
            decButton.disabled = currentValue <= 1;
            decButton.classList.toggle('opacity-50', currentValue <= 1);
            
            incButton.disabled = currentValue >= maxQuantity;
            incButton.classList.toggle('opacity-50', currentValue >= maxQuantity);
            
            console.log('Инициализация количества завершена');
        });

        // Настройка кнопок +/-
        const setupQuantityButton = (id, increment) => {
            const button = document.getElementById(id);
            if (!button) {
                console.error(`Кнопка с id "${id}" не найдена`);
                return;
            }

            console.log(`Настройка кнопки ${id}, increment: ${increment}`);
            
            // Удаляем старые обработчики, если они есть
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);
            
            const action = () => {
                console.log(`Нажата кнопка ${id}`);
                updateQuantity(increment);
            };

            newButton.addEventListener('click', action);
            newButton.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    action();
                }
            });
            
            // Проверяем, что обработчики установлены
            const events = $._data(newButton, "events");
            if (!events || !events.click) {
                console.error(`Не удалось установить обработчики для кнопки ${id}`);
            }
        };

        // Устанавливаем обработчики после загрузки DOM
        document.addEventListener('DOMContentLoaded', () => {
            setupQuantityButton('inc-qty', true);
            setupQuantityButton('dec-qty', false);
        });

        // Рейтинг
        document.querySelectorAll('.rating-star').forEach((star, idx) => {
            star.addEventListener('click', () => setRating(idx + 1));
            star.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    setRating(idx + 1);
                }
            });
        });
    }
  

    return { openTab, setRating, updateQuantity, updateMainImage, addToWishlist, shareProduct, init };
})();

// Экспортируем для Vite
export default ProductPage;

// Для обратной совместимости с глобальным объектом
window.EcoStoreProductPage = ProductPage;