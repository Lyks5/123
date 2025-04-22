@extends('admin.layouts.app')

@section('title', 'Категории блога')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Категории блога</h2>
            <span class="bg-eco-50 text-eco-800 text-sm font-medium px-2.5 py-0.5 rounded-full">
                {{ $categories->total() }}
            </span>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.blog.posts.index') }}" class="btn-secondary-admin">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12H3M3 12L10 5M3 12L10 19"/></svg>
                К списку статей
            </a>
            <a href="{{ route('admin.blog.categories.create') }}" class="btn-primary-admin">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                Добавить категорию
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="card-admin overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th scope="col" class="px-6 py-4">ID</th>
                        <th scope="col" class="px-6 py-4">Название</th>
                        <th scope="col" class="px-6 py-4">Slug</th>
                        <th scope="col" class="px-6 py-4">Статьи</th>
                        <th scope="col" class="px-6 py-4">Дата создания</th>
                        <th scope="col" class="px-6 py-4 text-right">Действия</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($categories as $category)
                        <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-6 py-4">{{ $category->id }}</td>
                            <td class="px-6 py-4 font-medium">
                                <div class="flex items-center">
                                    
                                    {{ $category->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $category->slug }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-eco-50 text-eco-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-eco-900/30 dark:text-eco-300">
                                    {{ $category->posts->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $category->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="{{ route('admin.blog.categories.edit', $category->id) }}" 
                                       class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                        Изменить
                                    </a>
                                    <form action="{{ route('admin.blog.categories.delete', $category->id) }}" 
                                          method="POST" 
                                          class="inline-block" 
                                          onsubmit="return confirm('Вы уверены? Это действие нельзя отменить.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                            Удалить
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 16s-1.5-2-4-2-4 2-4 2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                                    <span class="text-gray-500 dark:text-gray-400">Категории не найдены</span>
                                    <a href="{{ route('admin.blog.categories.create') }}" class="mt-2 text-eco-600 hover:text-eco-700 dark:text-eco-400 dark:hover:text-eco-300">
                                        Создать первую категорию
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection