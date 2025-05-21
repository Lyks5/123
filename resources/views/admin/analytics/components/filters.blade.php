<div class="flex flex-col lg:flex-row gap-4">
    <!-- Мобильные фильтры -->
    <div class="lg:hidden">
        <button @click="mobileFiltersOpen = !mobileFiltersOpen" class="flex items-center justify-between w-full px-4 py-2 bg-white dark:bg-gray-800 rounded-lg shadow">
            <span class="text-sm font-medium">Фильтры и экспорт</span>
            <svg :class="{'rotate-180': mobileFiltersOpen}" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        
        <div x-show="mobileFiltersOpen" x-transition class="mt-2 p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="space-y-4">
                @include('admin.analytics.components.filters.period-select')
                @include('admin.analytics.components.filters.export-buttons')
            </div>
        </div>
    </div>

    <!-- Десктопные фильтры -->
    <div class="hidden lg:flex items-center space-x-4">
        @include('admin.analytics.components.filters.period-select')
        @include('admin.analytics.components.filters.export-buttons')
    </div>
</div>