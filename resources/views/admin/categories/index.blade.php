@extends('admin.layouts.app')

@section('title', 'Управление категориями')

@section('content')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Категории</h1>
                <form action="{{ route('admin.categories.index') }}" method="GET" class="mt-4 flex gap-2">
                    <div class="flex-1">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Поиск по названию..."
                               class="w-80 rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-eco-600 hover:bg-eco-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Найти
                    </button>
                    @if(request()->has('search'))
                        <a href="{{ route('admin.categories.index') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
                            Сбросить
                        </a>
                    @endif
                </form>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="bg-eco-600 hover:bg-eco-700 text-white font-bold py-2 px-4 rounded h-10">
                Добавить категорию
            </a>
        </div>

        @if(request()->has('search'))
            <div class="mb-4">
                @if($categories->total() > 0)
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Найдено категорий: {{ $categories->total() }} по запросу "{{ request('search') }}"
                    </p>
                @else
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        По запросу "{{ request('search') }}" ничего не найдено
                    </p>
                @endif
            </div>
        @endif

        @if($categories->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr class="text-gray-700 dark:text-gray-300">
                            <th class="py-3 px-4 text-left">ID</th>
                            <th class="py-3 px-4 text-left">Название</th>
                            <th class="py-3 px-4 text-left">Родительская категория</th>
                            <th class="py-3 px-4 text-left">Статус</th>
                            <th class="py-3 px-4 text-left">Товаров</th>
                            <th class="py-3 px-4 text-left">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($categories as $category)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="py-3 px-4">{{ $category->id }}</td>
                                <td class="py-3 px-4 dark:text-gray-300">{{ $category->name }}</td>
                                <td class="py-3 px-4 dark:text-gray-300">{{ $category->parent ? $category->parent->name : '—' }}</td>
                                <td class="py-3 px-4">
                                    @if($category->is_active)
                                        <span class="px-2 py-1 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 rounded-full text-xs">Активна</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 rounded-full text-xs">Неактивна</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 dark:text-gray-300">{{ $category->products->count() }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.categories.edit', $category->slug) }}" class="text-blue-500 hover:text-blue-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category->slug) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту категорию?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $categories->links() }}
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
                            Нет категорий. Создайте первую категорию, нажав кнопку "Добавить категорию".
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection