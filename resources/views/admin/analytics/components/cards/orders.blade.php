<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6 transform hover:scale-105 transition-transform duration-300">
    <div class="flex items-center" data-tooltip="Общее количество оформленных заказов">
        <div class="p-3 rounded-full bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 mr-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400 uppercase">Заказы</p>
            <p class="text-2xl font-bold dark:text-white animate-fade-in">{{ $salesData['order_count'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">За все время</p>
        </div>
    </div>
    <div class="mt-4 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
        <div class="h-2 bg-green-600 dark:bg-green-500 rounded-full transition-all duration-500 ease-out"
             style="width: {{ ($salesData['orders_goal_progress'] ?? 0) }}%"></div>
    </div>
</div>