<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6">
    <h2 class="text-xl font-semibold mb-4 dark:text-white">Продажи по категориям</h2>
    <div class="relative" x-data="{
        chart: null,
        async initChart() {
            const { default: initCharts } = await import('/js/admin/analytics/charts.js');
            const options = initCharts.categories();
            
            if (this.chart) {
                this.chart.destroy();
            }

            this.chart = new ApexCharts(document.querySelector('#category-chart'), options);
            this.chart.render();

            // Обработчик изменения темы
            document.addEventListener('dark-mode-changed', () => {
                this.initChart();
            });
        }
    }"
    x-init="initChart">
        <div id="category-chart-skeleton" class="absolute inset-0 bg-gray-100 dark:bg-gray-700 animate-pulse rounded-lg hidden"></div>
        <div id="category-chart" class="h-80"></div>
    </div>

    <div class="mt-4">
        <div class="flex flex-col">
            @foreach($salesData['category_revenue'] ?? [] as $category)
                <div class="py-2 border-b dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <span class="text-sm dark:text-gray-300">{{ $category->name }}</span>
                        <span class="text-sm font-medium dark:text-gray-300">
                            {{ number_format($category->total_revenue ?? 0, 0, ',', ' ') }} ₽
                        </span>
                    </div>
                    <div class="mt-1 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-1.5 bg-eco-600 dark:bg-eco-500 rounded-full transition-all duration-500 ease-out"
                             style="width: {{ ($category->total_revenue / ($salesData['total_revenue'] ?? 1)) * 100 }}%">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>