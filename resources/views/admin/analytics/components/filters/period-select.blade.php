<div class="relative">
    <label class="block text-sm font-medium mb-2 dark:text-gray-300">Период</label>
    <select class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
        <option value="30">За последний месяц</option>
        <option value="90">За 3 месяца</option>
        <option value="180">За 6 месяцев</option>
        <option value="365">За год</option>
        <option value="custom">Произвольный период</option>
    </select>
    
    <div class="hidden mt-3" id="custom-period">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1 dark:text-gray-300">От</label>
                <input type="date" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 dark:text-gray-300">До</label>
                <input type="date" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
            </div>
        </div>
    </div>
</div>