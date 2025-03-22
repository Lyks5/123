@extends('admin.layouts.app')

@section('title', 'Атрибуты товаров')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Атрибуты товаров</h2>
        <a href="{{ route('admin.attributes.create') }}" class="btn-primary-admin">
            Добавить атрибут
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
                        <th class="admin-table-header-cell">Тип</th>
                        <th class="admin-table-header-cell">Кол-во значений</th>
                        <th class="admin-table-header-cell">Действия</th>
                    </tr>
                </thead>
                <tbody class="admin-table-body">
                    @forelse ($attributes as $attribute)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="admin-table-body-cell">{{ $attribute->id }}</td>
                            <td class="admin-table-body-cell font-medium">{{ $attribute->name }}</td>
                            <td class="admin-table-body-cell">
                                @switch($attribute->type)
                                    @case('select')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300">
                                            Выбор из списка
                                        </span>
                                        @break
                                    @case('radio')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-300">
                                            Радиокнопки
                                        </span>
                                        @break
                                    @case('checkbox')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300">
                                            Флажки
                                        </span>
                                        @break
                                    @case('color')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300">
                                            Цвет
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300">
                                            {{ $attribute->type }}
                                        </span>
                                @endswitch
                            </td>
                            <td class="admin-table-body-cell">{{ $attribute->values->count() }}</td>
                            <td class="admin-table-body-cell">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.attributes.edit', $attribute->id) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                        Изменить
                                    </a>
                                    <a href="{{ route('admin.attributes.values.index', $attribute->id) }}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">
                                        Значения
                                    </a>
                                    <form action="{{ route('admin.attributes.destroy', $attribute->id) }}" method="POST" class="inline" onsubmit="return confirm('Вы уверены?');">
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
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Атрибуты не найдены</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $attributes->links() }}
</div>
@endsection