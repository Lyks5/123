@extends('admin.layouts.app')

@section('title', 'Управление поступлениями')

@section('content')
<div class="container px-6 mx-auto">
    <div class="flex justify-between items-center py-4">
        <h2 class="text-2xl font-semibold text-gray-800">Поступления товаров</h2>
        <a href="{{ route('admin.arrivals.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Создать поступление
        </a>
    </div>

    <!-- Форма фильтрации -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <form action="{{ route('admin.arrivals.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Фильтр по названию -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Название</label>
                <input type="text" name="name" id="name" value="{{ request('name') }}" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Фильтр по статусу -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Статус</label>
                <select name="status" id="status" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Все</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Активные</option>
                    <option value="used" {{ request('status') === 'used' ? 'selected' : '' }}>Использованные</option>
                </select>
            </div>

            <!-- Фильтр по дате -->
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700">Дата от</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700">Дата до</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Фильтр по количеству -->
            <div>
                <label for="quantity_min" class="block text-sm font-medium text-gray-700">Количество от</label>
                <input type="number" name="quantity_min" id="quantity_min" value="{{ request('quantity_min') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="quantity_max" class="block text-sm font-medium text-gray-700">Количество до</label>
                <input type="number" name="quantity_max" id="quantity_max" value="{{ request('quantity_max') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Кнопки формы -->
            <div class="col-span-full flex justify-end space-x-2">
                <a href="{{ route('admin.arrivals.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Сбросить
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Применить фильтры
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Количество</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Цена закупки</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($arrivals as $arrival)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $arrival->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $arrival->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $arrival->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $arrival->status === 'active' ? 'Активно' : 'Использовано' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $arrival->quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $arrival->arrival_date->format('d.m.Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($arrival->purchase_price, 2) }} ₽</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.arrivals.edit', $arrival) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Изменить</a>
                            <form action="{{ route('admin.arrivals.destroy', $arrival) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Вы уверены?')">
                                    Удалить
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            Поступления не найдены
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($arrivals->hasPages())
            <div class="px-6 py-4">
                {{ $arrivals->appends(request()->except('page'))->links() }}
            </div>
        @endif
    </div>
</div>
@endsection