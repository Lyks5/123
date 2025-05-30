<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6 transform hover:scale-105 transition-transform duration-300">
    <div class="flex items-center" data-tooltip="Общее количество зарегистрированных пользователей">
        <div class="p-3 rounded-full bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300 mr-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400 uppercase">Пользователи</p>
            <p class="text-2xl font-bold dark:text-white animate-fade-in">{{ $userData['total_users'] ?? 0 }}</p>
            <p class="text-xs text-green-500 dark:text-green-400">+{{ $userData['new_users_30d'] ?? 0 }} за 30 дней</p>
        </div>
    </div>
    <div class="mt-4 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
        <div class="h-2 bg-purple-600 dark:bg-purple-500 rounded-full transition-all duration-500 ease-out"
             style="width: {{ ($userData['users_goal_progress'] ?? 0) }}%"></div>
    </div>
</div>