<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold dark:text-white">Экологический эффект</h2>
        <button
            x-data="{}"
            @click="$dispatch('open-modal', 'eco-impact-info')"
            class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400"
            data-tooltip="Подробная информация о экологическом эффекте">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg transform hover:scale-105 transition-transform duration-300">
            <p class="text-gray-500 dark:text-gray-400 text-sm">Снижение CO₂</p>
            <p class="text-2xl font-bold text-eco-700 dark:text-eco-400 animate-fade-in">
                {{ number_format($environmentalData['impact']['co2_reduced'] ?? 0, 0, ',', ' ') }} кг
            </p>
        </div>
        
        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg transform hover:scale-105 transition-transform duration-300">
            <p class="text-gray-500 dark:text-gray-400 text-sm">Сэкономлено воды</p>
            <p class="text-2xl font-bold text-eco-700 dark:text-eco-400 animate-fade-in">
                {{ number_format($environmentalData['impact']['water_saved'] ?? 0, 0, ',', ' ') }} л
            </p>
        </div>
        
        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg transform hover:scale-105 transition-transform duration-300">
            <p class="text-gray-500 dark:text-gray-400 text-sm">Снижение пластика</p>
            <p class="text-2xl font-bold text-eco-700 dark:text-eco-400 animate-fade-in">
                {{ number_format($environmentalData['impact']['plastic_reduced'] ?? 0, 0, ',', ' ') }} кг
            </p>
        </div>
    
        <!-- Модальное окно с информацией -->
        <div
            x-data="{ shown: false }"
            x-show="shown"
            @open-modal.window="if ($event.detail === 'eco-impact-info') shown = true"
            @close-modal.window="shown = false"
            @keydown.escape.window="shown = false"
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
        >
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div
                    x-show="shown"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    @click="shown = false"
                ></div>
    
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                    Экологический эффект
                                </h3>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Наши эко-товары помогают сократить:
                                    </p>
                                    <ul class="mt-2 list-disc list-inside text-sm text-gray-500 dark:text-gray-400">
                                        <li>Выбросы CO₂ при производстве</li>
                                        <li>Потребление воды</li>
                                        <li>Использование пластика</li>
                                    </ul>
                                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                                        Эти показатели рассчитываются на основе данных о продажах эко-товаров и их индивидуальных характеристик.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                            @click="shown = false"
                        >
                            Закрыть
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative">
        <div id="eco-sales-chart-skeleton" class="absolute inset-0 bg-gray-100 dark:bg-gray-700 animate-pulse rounded-lg hidden"></div>
        <div id="eco-sales-chart" class="h-52"></div>
    </div>

    <div class="mt-4">
        <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
            <span>Процент эко-товаров: {{ number_format($environmentalData['eco_percentage'] ?? 0, 1) }}%</span>
            <span>Активных инициатив: {{ $environmentalData['initiatives']['active'] ?? 0 }}/{{ $environmentalData['initiatives']['total'] ?? 0 }}</span>
        </div>
    </div>
</div>