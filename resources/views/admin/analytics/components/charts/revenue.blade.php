<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6 lg:col-span-2">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold dark:text-white">Динамика продаж</h2>
        <div class="flex space-x-2">
            <button @click="chartType = 'area'"
                    :class="{'bg-eco-600 dark:bg-eco-500 text-white': chartType === 'area', 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700': chartType !== 'area'}"
                    class="px-3 py-1 rounded-md transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                </svg>
            </button>
            <button @click="chartType = 'bar'"
                    :class="{'bg-eco-600 dark:bg-eco-500 text-white': chartType === 'bar', 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700': chartType !== 'bar'}"
                    class="px-3 py-1 rounded-md transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M8 13v-1m4 1v-3m4 3V8M12 20V4"></path>
                </svg>
            </button>
        </div>
    </div>
    <div class="relative" x-data="{
        chart: null,
        chartType: 'area',
        async initChart() {
            const { default: initCharts } = await import('/js/admin/analytics/charts.js');
            const options = initCharts.revenue(this.chartType);
            
            if (this.chart) {
                this.chart.destroy();
            }

            this.chart = new ApexCharts(document.querySelector('#revenue-chart'), options);
            this.chart.render();

            // Обработчик изменения темы
            document.addEventListener('dark-mode-changed', () => {
                this.initChart();
            });
        }
    }"
    x-init="initChart"
    @chart-type-changed="initChart">
        <div id="revenue-chart-skeleton" class="absolute inset-0 bg-gray-100 dark:bg-gray-700 animate-pulse rounded-lg hidden"></div>
        <div id="revenue-chart" class="h-80"></div>
    </div>
</div>