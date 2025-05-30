<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6 transform hover:scale-105 transition-transform duration-300">
    <div class="flex items-center" data-tooltip="Общая выручка за весь период">
        <div class="p-3 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 mr-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400 uppercase">Выручка</p>
            <p class="text-2xl font-bold dark:text-white animate-fade-in">{{ number_format($salesData['total_revenue'] ?? 0, 0, ',', ' ') }} ₽</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">За все время</p>
        </div>
    </div>
    <div class="mt-4 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
        <div class="h-2 bg-blue-600 dark:bg-blue-500 rounded-full transition-all duration-500 ease-out"
             style="width: {{ ($salesData['revenue_goal_progress'] ?? 0) }}%"></div>
    </div>
</div>