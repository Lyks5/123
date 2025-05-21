<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6 transform hover:scale-105 transition-transform duration-300">
    <div class="flex items-center" data-tooltip="Процент посетителей, совершивших покупку">
        <div class="p-3 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300 mr-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400 uppercase">Конверсия</p>
            <p class="text-2xl font-bold dark:text-white animate-fade-in">{{ number_format($salesData['conversion_rates']['current'] ?? 0, 1) }}%</p>
            <p class="text-xs text-green-500">+{{ number_format($salesData['conversion_rates']['change'] ?? 0, 1) }}% к пред. периоду</p>
        </div>
    </div>
    <div class="mt-4 h-2 bg-gray-200 rounded-full">
        <div class="h-2 bg-amber-600 rounded-full" style="width: {{ ($salesData['conversion_rates']['current'] ?? 0) }}%"></div>
    </div>
</div>