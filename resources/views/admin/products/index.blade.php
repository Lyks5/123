@extends('admin.layouts.app')

@section('title', 'Управление товарами')

@section('content')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Товары</h1>
                <a href="{{ route('admin.products.create') }}" class="bg-eco-600 hover:bg-eco-700 text-white font-bold py-2 px-4 rounded">
                    Добавить товар
                </a>
            </div>

            <form action="{{ route('admin.products.index') }}" method="GET" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <!-- Поиск по названию и SKU -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Поиск по названию</label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ request('name') }}"
                               placeholder="Введите название товара"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                    </div>
                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Поиск по SKU</label>
                        <input type="text"
                               name="sku"
                               id="sku"
                               value="{{ request('sku') }}"
                               placeholder="Введите SKU товара"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Фильтр по категории -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Категория</label>
                        <select name="category" id="category" class="w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                            <option value="">Все категории</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Фильтр по статусу -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Статус</label>
                        <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                            <option value="">Все статусы</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Активные</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Черновики</option>
                        </select>
                    </div>

                    <!-- Фильтр по цене -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Цена</label>
                        <div class="flex space-x-2">
                            <input type="number" name="price_min" value="{{ request('price_min') }}"
                                   placeholder="От" min="0"
                                   class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                            <input type="number" name="price_max" value="{{ request('price_max') }}"
                                   placeholder="До" min="0"
                                   class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                    </div>

                    <!-- Фильтр по количеству -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Количество на складе</label>
                        <div class="flex space-x-2">
                            <input type="number" name="stock_min" value="{{ request('stock_min') }}"
                                   placeholder="От" min="0"
                                   class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                            <input type="number" name="stock_max" value="{{ request('stock_max') }}"
                                   placeholder="До" min="0"
                                   class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex justify-end space-x-2">
                    <button type="submit"
                            class="bg-eco-600 hover:bg-eco-700 text-white font-medium py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500">
                        Применить фильтры
                    </button>
                    @if(request()->hasAny(['category', 'status', 'price_min', 'price_max', 'stock_min', 'stock_max', 'name', 'sku']))
                        <a href="{{ route('admin.products.index') }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Сбросить
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if($products->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr class="text-gray-700 dark:text-gray-300">
                            <th class="py-3 px-4 text-left">ID</th>
                            <th class="py-3 px-4 text-left">Изображение</th>
                            <th class="py-3 px-4 text-left">Название</th>
                            <th class="py-3 px-4 text-left">SKU</th>
                            <th class="py-3 px-4 text-left">Цена</th>
                            <th class="py-3 px-4 text-left">Кол-во</th>
                            <th class="py-3 px-4 text-left">Статус</th>
                            <th class="py-3 px-4 text-left">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($products as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="py-3 px-4">{{ $product->id }}</td>
                                <td class="py-3 px-4 relative">
                                    @if($product->image_url)
                                        @php
                                            $imageUrl = $product->image_url;
                                            if (!str_starts_with($imageUrl, '/storage/')) {
                                                $imageUrl = '/storage/' . $imageUrl;
                                            }
                                        @endphp
                                        <img src="{{ asset($imageUrl) }}"
                                            alt="{{ $product->name }}"
                                            class="w-16 h-16 object-cover rounded"
                                            onerror="console.log('Image load error for product {{ $product->id }}:', this.src); this.onerror=null; this.classList.add('bg-gray-200'); this.src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';"
                                        >
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                                            <span class="text-gray-500 dark:text-gray-400 text-xs">Нет фото</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 dark:text-gray-300">{{ $product->name }}</td>
                                <td class="py-3 px-4 dark:text-gray-300">{{ $product->sku }}</td>
                                <td class="py-3 px-4">
                                    @if($product->sale_price)
                                        <span class="line-through text-gray-500 dark:text-gray-400">{{ number_format($product->price, 0, '.', ' ') }} ₽</span>
                                        <span class="text-eco-600 dark:text-eco-400 font-bold">{{ number_format($product->sale_price, 0, '.', ' ') }} ₽</span>
                                    @else
                                        <span class="dark:text-gray-300">{{ number_format($product->price, 0, '.', ' ') }} ₽</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 dark:text-gray-300">{{ $product->stock_quantity  }}</td>
                                <td class="py-3 px-4">
                                    @if($product->status === 'published')
                                        <span class="px-2 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 rounded-full text-xs">Активен</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 rounded-full text-xs">Неактивен</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-500 hover:text-blue-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.products.delete', $product) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот товар?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                        <a href="{{ route('product.show', ['product' => $product->sku]) }}" target="_blank" class="text-gray-500 hover:text-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @else
            <div class="bg-yellow-50 dark:bg-yellow-900/50 border-l-4 border-yellow-400 dark:border-yellow-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 dark:text-yellow-300">
                            Нет товаров. Создайте первый товар, нажав кнопку "Добавить товар".
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection