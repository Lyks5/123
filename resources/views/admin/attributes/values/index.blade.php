@extends('admin.layouts.app')

@section('title', 'Значения атрибута')

@section('content')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Значения атрибута "{{ $attribute->name }}"</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Тип: {{ $attribute->type }}</p>
            </div>
            <a href="{{ route('admin.attributes.values.create', $attribute) }}" class="bg-eco-600 hover:bg-eco-700 text-white font-bold py-2 px-4 rounded">
                Добавить значение
            </a>
        </div>

        @if($values->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr class="text-gray-700 dark:text-gray-300">
                            <th class="py-3 px-4 text-left">ID</th>
                            <th class="py-3 px-4 text-left">Значение</th>
                            @if($attribute->type === 'color')
                                <th class="py-3 px-4 text-left">Цвет</th>
                            @endif
                            <th class="py-3 px-4 text-left">Порядок</th>
                            <th class="py-3 px-4 text-left">Использований в товарах</th>
                            <th class="py-3 px-4 text-left">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($values as $value)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="py-3 px-4 dark:text-gray-300">{{ $value->id }}</td>
                                <td class="py-3 px-4 dark:text-gray-300">{{ $value->value }}</td>
                                @if($attribute->type === 'color')
                                    <td class="py-3 px-4">
                                        <div class="w-6 h-6 rounded border border-gray-200" style="background-color: {{ $value->hex_color }}"></div>
                                    </td>
                                @endif
                                <td class="py-3 px-4 dark:text-gray-300">{{ $value->display_order }}</td>
                                <td class="py-3 px-4 dark:text-gray-300">
                                    {{ $value->products_count }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.attributes.values.edit', ['attribute' => $attribute, 'valueId' => $value->id]) }}" class="text-blue-500 hover:text-blue-700" title="Редактировать">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        @if($value->products_count == 0)
                                            <form action="{{ route('admin.attributes.values.delete', ['attribute' => $attribute, 'valueId' => $value->id]) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Вы уверены, что хотите удалить это значение?');" 
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700" title="Удалить">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 cursor-not-allowed" title="Нельзя удалить значение, используемое в товарах">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $values->links() }}
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
                            Нет значений. Добавьте первое значение, нажав кнопку "Добавить значение".
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection