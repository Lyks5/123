<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 md:p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold dark:text-white">Популярные товары</h2>
        <button @click="expanded = !expanded" class="text-gray-500 hover:text-gray-700 dark:text-gray-400">
            <svg :class="{'rotate-180': !expanded}" class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
    </div>

    <div x-show="expanded" x-transition class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Товар</th>
                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">SKU</th>
                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Продажи</th>
                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Выручка</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productData['top_products'] ?? [] as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">
                            {{ $product->name ?? '-' }}
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">
                            {{ $product->sku ?? '-' }}
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">
                            {{ $product->total_quantity ?? 0 }}
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">
                            {{ number_format($product->total_revenue ?? 0, 0, ',', ' ') }} ₽
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500 dark:text-gray-400">
                            Нет данных для отображения
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>