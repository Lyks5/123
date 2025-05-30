import noUiSlider from 'nouislider';

document.addEventListener('DOMContentLoaded', function() {
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

    // Функция для сбора всех активных фильтров
    function getActiveFilters() {
        console.log('Getting active filters');
        const filters = {
            category: [],
            eco_features: [],
            min_price: minPriceInput?.value || '',
            max_price: maxPriceInput?.value || '',
            eco_score: document.getElementById('eco-score')?.value || '',
            sort: document.getElementById('sort')?.value || 'default'
        };

        // Сбор выбранных категорий
        document.querySelectorAll('input[name="category[]"]:checked').forEach(input => {
            filters.category.push(input.value);
        });

        // Сбор выбранных эко-особенностей
        document.querySelectorAll('input[name="features[]"]:checked').forEach(input => {
            filters.eco_features.push(input.value);
        });

        console.log('Active filters:', filters);
        return filters;
    }

    // Функция для обновления списка товаров
    async function updateProducts() {
        console.log('Updating products');
        const filters = getActiveFilters();
        
        try {
            const params = new URLSearchParams();
            Object.entries(filters).forEach(([key, value]) => {
                if (Array.isArray(value)) {
                    if (value.length > 0) {
                        params.set(key, value.join(','));
                    }
                } else if (value) {
                    params.set(key, value);
                }
            });

            console.log('Sending request with params:', params.toString());
            const response = await fetch(`/api/products/filter?${params.toString()}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Received data:', data);
            
            // Обновление списка товаров
            const productsGrid = document.getElementById('products-grid');
            if (productsGrid) {
                productsGrid.innerHTML = data.html;
            }
            
            // Обновление счетчика товаров
            const productsCount = document.getElementById('products-count');
            if (productsCount) {
                productsCount.textContent = `Показано ${data.from} - ${data.to} из ${data.total} товаров`;
            }
            
            // Обновление URL
            const url = new URL(window.location);
            params.forEach((value, key) => {
                url.searchParams.set(key, value);
            });
            window.history.pushState({}, '', url);
            
            // Обновление активных фильтров
            updateActiveFilters(filters);
        } catch (error) {
            console.error('Error updating products:', error);
        }
    }

    // Функция для отображения активных фильтров
    function updateActiveFilters(filters) {
        console.log('Updating active filters display');
        const activeFiltersContainer = document.getElementById('active-filters');
        if (!activeFiltersContainer) return;

        activeFiltersContainer.innerHTML = '';

        // Добавление категорий
        filters.category.forEach(categoryId => {
            const categoryLabel = document.querySelector(`input[value="${categoryId}"]`)?.nextElementSibling?.textContent;
            if (categoryLabel) {
                addActiveFilterBadge(categoryLabel.trim(), () => {
                    const input = document.querySelector(`input[value="${categoryId}"]`);
                    if (input) {
                        input.checked = false;
                        updateProducts();
                    }
                });
            }
        });

        // Добавление эко-особенностей
        filters.eco_features.forEach(featureId => {
            const featureLabel = document.querySelector(`input[value="${featureId}"]`)?.nextElementSibling?.textContent;
            if (featureLabel) {
                addActiveFilterBadge(featureLabel.trim().split('(')[0], () => {
                    const input = document.querySelector(`input[value="${featureId}"]`);
                    if (input) {
                        input.checked = false;
                        updateProducts();
                    }
                });
            }
        });

        // Добавление диапазона цен
        if (filters.min_price || filters.max_price) {
            addActiveFilterBadge(
                `Цена: ${filters.min_price}₽ - ${filters.max_price}₽`,
                () => {
                    if (priceSlider && priceSlider.noUiSlider) {
                        priceSlider.noUiSlider.reset();
                        updateProducts();
                    }
                }
            );
        }

        // Добавление эко-рейтинга
        if (filters.eco_score) {
            const ecoScoreSelect = document.getElementById('eco-score');
            const selectedOption = ecoScoreSelect?.querySelector(`option[value="${filters.eco_score}"]`);
            if (selectedOption) {
                addActiveFilterBadge(
                    selectedOption.textContent,
                    () => {
                        if (ecoScoreSelect) {
                            ecoScoreSelect.value = '';
                            updateProducts();
                        }
                    }
                );
            }
        }
    }

    // Функция для добавления бейджа активного фильтра
    function addActiveFilterBadge(text, removeCallback) {
        const activeFiltersContainer = document.getElementById('active-filters');
        if (!activeFiltersContainer) return;

        const badge = document.createElement('span');
        badge.className = 'inline-flex items-center bg-eco-100 text-eco-800 px-3 py-1 rounded-full text-sm mr-2 mb-2';
        badge.innerHTML = `
            ${text}
            <button class="ml-2 text-eco-600 hover:text-eco-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        
        const removeButton = badge.querySelector('button');
        if (removeButton) {
            removeButton.addEventListener('click', (e) => {
                e.preventDefault();
                removeCallback();
            });
        }
        
        activeFiltersContainer.appendChild(badge);
    }

    // Добавление обработчиков событий с debounce
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    const debouncedUpdate = debounce(updateProducts, 500);

    // Добавление обработчиков событий для всех элементов фильтрации
    document.querySelectorAll('input[name="category[]"], input[name="features[]"], #eco-score, #sort').forEach(input => {
        console.log('Adding event listener to:', input);
        input.addEventListener('change', () => {
            console.log('Filter changed:', input.name || input.id);
            debouncedUpdate();
        });
    });

    // Обработчик для слайдера цен
    if (priceSlider && priceSlider.noUiSlider) {
        priceSlider.noUiSlider.on('change', debouncedUpdate);
    }

    // Кнопка очистки всех фильтров
    const clearFiltersButton = document.getElementById('clear-filters');
    if (clearFiltersButton) {
        clearFiltersButton.addEventListener('click', () => {
            console.log('Clearing all filters');
            // Сброс чекбоксов
            document.querySelectorAll('input[type="checkbox"]').forEach(input => {
                input.checked = false;
            });
            
            // Сброс слайдера цен
            if (priceSlider && priceSlider.noUiSlider) {
                priceSlider.noUiSlider.reset();
            }
            
            // Сброс эко-рейтинга
            const ecoScoreSelect = document.getElementById('eco-score');
            if (ecoScoreSelect) {
                ecoScoreSelect.value = '';
            }
            
            // Сброс сортировки
            const sortSelect = document.getElementById('sort');
            if (sortSelect) {
                sortSelect.value = 'default';
            }
            
            // Обновление товаров
            updateProducts();
        });
    }

    // Начальное обновление активных фильтров
    updateActiveFilters(getActiveFilters());
});