@extends('admin.layouts.app')

@section('title', 'Значения атрибута ' . $attribute->name)

@section('content')
<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Значения атрибута "{{ $attribute->name }}"</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Тип: 
                @switch($attribute->type)
                    @case('select')
                        Выбор из списка
                        @break
                    @case('radio')
                        Радиокнопки
                        @break
                    @case('checkbox')
                        Флажки
                        @break
                    @case('color')
                        Цвет
                        @break
                    @default
                        {{ $attribute->type }}
                @endswitch
            </p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.attributes.index') }}" class="btn-secondary-admin">
                Назад к атрибутам
            </a>
            <a href="{{ route('admin.attributes.values.store', $attribute->id) }}" class="btn-primary-admin">
                Добавить значение
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
            <table class="admin-table">
                <thead class="admin-table-header">
                    <tr>
                        <th class="admin-table-header-cell">ID</th>
                        <th class="admin-table-header-cell">Значение</th>
                        @if($attribute->type == 'color')
                            <th class="admin-table-header-cell">Цвет</th>
                        @endif
                        <th class="admin-table-header-cell">Действия</th>
                    </tr>
                </thead>
                <tbody class="admin-table-body">
                    @forelse ($values as $value)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="admin-table-body-cell">{{ $value->id }}</td>
                            <td class="admin-table-body-cell font-medium">{{ $value->value }}</td>
                            @if($attribute->type == 'color')
                                <td class="admin-table-body-cell">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 rounded border border-gray-200 dark:border-gray-700 mr-2" style="background-color: {{ $value->value }}"></div>
                                        <span>{{ $value->value }}</span>
                                    </div>
                                </td>
                            @endif
                            <td class="admin-table-body-cell">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.attributes.values.edit', [$attribute->id, $value->id]) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                        Изменить
                                    </a>
                                    <form action="{{ route('admin.attributes.values.delete', [$attribute->id, $value->id]) }}" method="POST" class="inline" onsubmit="return confirm('Вы уверены?');">
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
                            <td colspan="{{ $attribute->type == 'color' ? '4' : '3' }}" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Значения не найдены</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $values->links() }}
</div>
@endsection