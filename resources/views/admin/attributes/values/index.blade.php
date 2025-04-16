@extends('admin.layouts.app')

@section('title', 'Значения атрибута ' . $attribute->name)

@section('content')
<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Значения атрибута "{{ $attribute->name }}"</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.attributes.edit', $attribute->id) }}" class="btn-secondary-admin">
                Редактировать атрибут
            </a>
            <a href="{{ route('admin.attributes.index') }}" class="btn-secondary-admin">
                Назад к атрибутам
            </a>
        </div>
    </div>

    <!-- Add New Value Card -->
    <div class="card-admin">
        <div class="card-admin-header">
            <h3 class="card-admin-title">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Добавить новое значение
            </h3>
        </div>
        <div class="card-admin-body">
            <form action="{{ route('admin.attributes.values.store', $attribute->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="value" class="form-label-admin">Значение</label>
                        @if($attribute->type == 'color')
                            <div class="flex space-x-3">
                                <input type="color" name="color_picker" id="color_picker" class="h-10 w-10 rounded border border-gray-300 dark:border-gray-700 cursor-pointer" value="#3498db">
                                <input type="text" name="value" id="value" class="form-input-admin flex-1 @error('value') border-red-500 @enderror" value="{{ old('value', '#3498db') }}" placeholder="#HEX код цвета" required>
                            </div>
                        @else
                            <input type="text" name="value" id="value" class="form-input-admin @error('value') border-red-500 @enderror" value="{{ old('value') }}" required>
                        @endif
                        @error('value')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="btn-primary-admin">
                            Добавить значение
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Values Table Card -->
    <div class="card-admin">
        <div class="card-admin-header">
            <h3 class="card-admin-title">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                    <line x1="4" y1="22" x2="4" y2="15"></line>
                </svg>
                Список значений
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead class="admin-table-header">
                    <tr>
                        <th class="admin-table-header-cell">ID</th>
                        <th class="admin-table-header-cell">Значение</th>
                        @if($attribute->type == 'color')
                        <th class="admin-table-header-cell">Образец цвета</th>
                        @endif
                        <th class="admin-table-header-cell">Используется в вариантах</th>
                        <th class="admin-table-header-cell">Действия</th>
                    </tr>
                </thead>
                <tbody class="admin-table-body">
                    @foreach ($values as $value)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        <td class="admin-table-body-cell">{{ $value->id }}</td>
                        <td class="admin-table-body-cell font-medium">{{ $value->value }}</td>
                        @if($attribute->type == 'color')
                        <td class="admin-table-body-cell">
                            <div class="h-8 w-8 rounded-full border border-gray-200 dark:border-gray-700 shadow-sm" style="background-color: {{ $value->value }};"></div>
                        </td>
                        @endif
                        <td class="admin-table-body-cell">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $value->variants_count > 0 ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                {{ $value->variants_count ?? 0 }}
                            </span>
                        </td>
                        <td class="admin-table-body-cell">
                            <div class="flex space-x-4">
                                <a href="{{ route('admin.attributes.values.update', [$attribute->id, $value->id]) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
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
                    @endforeach

                    @if(count($values) == 0)
                    <tr>
                        <td colspan="{{ $attribute->type == 'color' ? '5' : '4' }}" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 dark:text-gray-600 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 8h14M5 12h14M5 16h14"></path>
                                </svg>
                                <p class="font-medium">Нет значений для этого атрибута</p>
                                <p class="text-sm mt-1">Добавьте первое значение с помощью формы выше</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        @if($values->hasPages())
        <div class="card-admin-footer">
            {{ $values->links() }}
        </div>
        @endif
    </div>
</div>

@if($attribute->type == 'color')
@push('scripts')
<script>
    const colorPicker = document.getElementById('color_picker');
    const valueInput = document.getElementById('value');
    
    colorPicker.value = '#3498db';
    
    colorPicker.addEventListener('input', function() {
        valueInput.value = this.value;
    });
    
    valueInput.addEventListener('input', function() {
        const validHex = /^#([0-9A-F]{3}){1,2}$/i;
        if (validHex.test(this.value)) {
            colorPicker.value = this.value;
        }
    });
</script>
@endpush
@endif
@endsection