document.addEventListener('DOMContentLoaded', function() {
    let revenueChart, categoryChart, ecoSalesChart, reviewsChart;

    // Базовые настройки для всех графиков
    const baseChartOptions = {
        chart: {
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 150
                },
                dynamicAnimation: {
                    enabled: true,
                    speed: 350
                }
            },
            toolbar: {
                show: false
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        theme: {
            mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
        }
    };

    // Инициализация тултипов
    initTooltips();

    // Показываем скелетон при загрузке
    showSkeletons();

    // Инициализация графиков
    initCharts();

    // Обработчики событий
    initEventListeners();
});

// Инициализация тултипов
function initTooltips() {
    tippy('[data-tooltip]', {
        content: (reference) => reference.getAttribute('data-tooltip'),
        arrow: true,
        placement: 'top',
        theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
    });
}

// Показ скелетон-лоадеров
function showSkeletons() {
    document.querySelectorAll('[id$="-skeleton"]').forEach(skeleton => {
        skeleton.classList.remove('hidden');
    });
}

// Создание графика с ленивой загрузкой
function createLazyChart(elementId, options, delay = 500) {
    return new Promise((resolve) => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        const chart = new ApexCharts(document.querySelector(elementId), options);
                        chart.render();
                        document.querySelector(`${elementId}-skeleton`).classList.add('hidden');
                        resolve(chart);
                    }, delay);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        observer.observe(document.querySelector(elementId));
    });
}

// Обновление типа графика
function updateChartType(chartInstance, newType) {
    if (chartInstance) {
        chartInstance.updateOptions({
            chart: {
                type: newType
            }
        });
    }
}

// Обработчик изменения темы
function updateChartsTheme() {
    const isDark = document.documentElement.classList.contains('dark');
    const themeOptions = {
        theme: {
            mode: isDark ? 'dark' : 'light'
        },
        tooltip: {
            theme: isDark ? 'dark' : 'light'
        }
    };

    [revenueChart, categoryChart, ecoSalesChart, reviewsChart].forEach(chart => {
        if (chart) {
            chart.updateOptions(themeOptions);
        }
    });
}

// Инициализация обработчиков событий
function initEventListeners() {
    // Обработчики переключения типа графика
    document.querySelectorAll('[data-chart-type]').forEach(button => {
        button.addEventListener('click', function() {
            const chartType = this.dataset.chartType;
            updateChartType(revenueChart, chartType);
        });
    });

    // Обработчики изменения темы
    document.getElementById('theme-toggle')?.addEventListener('click', updateChartsTheme);
    document.getElementById('mobile-theme-toggle')?.addEventListener('click', updateChartsTheme);
}