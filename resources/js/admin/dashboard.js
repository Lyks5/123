// Инициализация всех подсказок
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация всех charts.js графиков
    initCharts();

    // Инициализация обновления в реальном времени
    initLiveUpdates();

    // Инициализация анимаций чисел
    initNumberAnimations();
});

function initCharts() {
    // График продаж
    const salesChart = document.querySelector('#sales-chart');
    if (salesChart) {
        const options = {
            chart: {
                type: 'area',
                height: 300,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            series: [{
                name: 'Продажи',
                data: salesData // передается из blade шаблона
            }],
            xaxis: {
                categories: salesDates, // передается из blade шаблона
                labels: {
                    style: {
                        colors: '#64748b'
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function(value) {
                        return new Intl.NumberFormat('ru-RU').format(value) + ' ₽';
                    },
                    style: {
                        colors: '#64748b'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return new Intl.NumberFormat('ru-RU').format(value) + ' ₽';
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'vertical',
                    opacityFrom: 0.4,
                    opacityTo: 0.1,
                }
            },
            colors: ['#059669']
        };

        const chart = new ApexCharts(salesChart, options);
        chart.render();
    }
}

function initLiveUpdates() {
    // Обновление данных каждую минуту
    setInterval(() => {
        fetch('/admin/dashboard/stats')
            .then(response => response.json())
            .then(data => {
                updateStats(data);
            })
            .catch(error => console.error('Error:', error));
    }, 60000);
}

function updateStats(data) {
    // Обновление статистических данных
    Object.keys(data).forEach(key => {
        const element = document.querySelector(`[data-stat="${key}"]`);
        if (element) {
            // Если это денежное значение
            if (key.includes('revenue') || key.includes('sales')) {
                element.textContent = new Intl.NumberFormat('ru-RU').format(data[key]) + ' ₽';
            } else {
                element.textContent = data[key];
            }

            // Добавляем анимацию обновления
            element.classList.add('stat-updated');
            setTimeout(() => {
                element.classList.remove('stat-updated');
            }, 1000);
        }
    });
}

function initNumberAnimations() {
    // Анимация чисел при первой загрузке
    const stats = document.querySelectorAll('.animate-number');
    stats.forEach(stat => {
        const finalValue = parseFloat(stat.dataset.value);
        animateNumber(stat, finalValue);
    });
}

function animateNumber(element, final) {
    const start = 0;
    const duration = 1000;
    const startTime = performance.now();
    
    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        const current = Math.floor(progress * (final - start) + start);
        
        if (element.dataset.format === 'currency') {
            element.textContent = new Intl.NumberFormat('ru-RU').format(current) + ' ₽';
        } else {
            element.textContent = new Intl.NumberFormat('ru-RU').format(current);
        }

        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }

    requestAnimationFrame(update);
}

// Экспорт функций для использования в других модулях
export {
    initCharts,
    initLiveUpdates,
    initNumberAnimations,
    updateStats
};