import noUiSlider from 'nouislider';
import { initNotifications } from './components/product/notifications';

document.addEventListener('DOMContentLoaded', function() {
    // Инициализация уведомлений
    const notifications = initNotifications({
        position: 'top-right',
        duration: 3000
    });
    // Отладочная информация
    console.log('Shop.js loaded');

    // Инициализация слайдера цен
    const priceSlider = document.getElementById('price-range');
    const minPriceInput = document.getElementById('min-price');
    const maxPriceInput = document.getElementById('max-price');

    if (priceSlider && minPriceInput && maxPriceInput) {
        console.log('Price slider elements found');
        try {
            noUiSlider.create(priceSlider, {
                start: [
                    parseInt(minPriceInput.value || minPriceInput.getAttribute('min')), 
                    parseInt(maxPriceInput.value || maxPriceInput.getAttribute('max'))
                ],
                connect: true,
                range: {
                    'min': parseInt(minPriceInput.getAttribute('min')),
                    'max': parseInt(maxPriceInput.getAttribute('max'))
                }
            });

            priceSlider.noUiSlider.on('update', function (values, handle) {
                const value = Math.round(values[handle]);
                if (handle === 0) {
                    minPriceInput.value = value;
                } else {
                    maxPriceInput.value = value;
                }
            });
        } catch (error) {
            console.error('Error initializing price slider:', error);
        }
    } else {
        console.warn('Price slider elements not found');
    }

    // Сбор всех активных фильтров
    function getActiveFilters() {
        const filters = {
            category: [],
            eco_features: [],
            min_price: minPriceInput?.value || '',
            max_price: maxPriceInput?.value || '',
            eco_score: document.getElementById('eco-score')?.value || '',
            sort: document.getElementById('sort')?.value || '',
            search: document.getElementById('product-search')?.value || '',
        };
        document.querySelectorAll('input[name="category[]"]:checked').forEach(el => {
            filters.category.push(el.value);
        });
        document.querySelectorAll('input[name="features[]"]:checked').forEach(el => {
            filters.eco_features.push(el.value);
        });
        return filters;
    }

    // Обновление товаров через AJAX
    function updateProducts() {
        const filters = getActiveFilters();
        const params = new URLSearchParams();
        if (filters.category.length) params.append('category', filters.category.join(','));
        if (filters.eco_features.length) params.append('eco_features', filters.eco_features.join(','));
        if (filters.min_price) params.append('min_price', filters.min_price);
        if (filters.max_price) params.append('max_price', filters.max_price);
        if (filters.eco_score) params.append('eco_score', filters.eco_score);
        if (filters.sort) params.append('sort', filters.sort);

        // Обновить URL без перезагрузки
        window.history.replaceState(null, '', '?' + params.toString());

        // AJAX-запрос
        fetch(window.location.pathname + '?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(res => res.json())
            .then(data => {
                const productsGrid = document.getElementById('products-grid');
                if (productsGrid && data.html) {
                    productsGrid.innerHTML = data.html;
                }
                
                // Отображаем уведомление, если оно есть в ответе
                if (data.message) {
                    document.dispatchEvent(new CustomEvent('show-notification', {
                        detail: {
                            message: data.message,
                            type: data.success ? 'success' : 'error'
                        }
                    }));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.dispatchEvent(new CustomEvent('show-notification', {
                    detail: {
                        message: 'Произошла ошибка при загрузке данных',
                        type: 'error'
                    }
                }));
            });
    }

    // Навесить обработчики на все фильтры
    document.querySelectorAll('input[name="category[]"], input[name="features[]"], #min-price, #max-price, #eco-score, #sort').forEach(el => {
        el.addEventListener('change', updateProducts);
    });

    // Обработчик поиска
    const searchInput = document.getElementById('product-search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(updateProducts, 400);
        });
    }

    // Кнопка очистки фильтров
    const clearBtn = document.getElementById('clear-filters');
    if (clearBtn) {
        clearBtn.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('input[type="checkbox"], input[type="number"]').forEach(el => {
                if (el.type === 'checkbox') el.checked = false;
                if (el.type === 'number') el.value = '';
            });
            document.getElementById('eco-score').value = '';
            document.getElementById('sort').value = 'default';
            updateProducts();
        });
    }
});