@extends('admin.layouts.app')

@section('title', 'Категории блога')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Категории блога</h2>
        <a href="{{ route('admin.blog.categories.create') }}" class="btn-primary-admin">
            Добавить категорию
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="card-admin overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead class="admin-table-header">
                    <tr>
                        <th class="admin-table-header-cell">ID</th>
                        <th class="admin-table-header-cell">Название</th>
                        <th class="admin-table-header-cell">Slug</th>
                        <th class="admin-table-header-cell">Кол-во статей</th>
                        <th class="admin-table-header-cell">Действия</th>
                    </tr>
                </thead>
                <tbody class="admin-table-body">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="admin-table-body-cell">{{ $category->id }}</td>
                            <td class="admin-table-body-cell font-medium">{{ $category->name }}</td>
                            <td class="admin-table-body-cell">{{ $category->slug }}</td>
                            <td class="admin-table-body-cell">{{ $category->posts->count() }}</td>
                            <td class="admin-table-body-cell">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.blog.categories.edit', $category->id) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                        Изменить
                                    </a>
                                    <form action="{{ route('admin.blog.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Вы уверены?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                            Удалить
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Категории не найдены</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $categories->links() }}
</div>
@endsection