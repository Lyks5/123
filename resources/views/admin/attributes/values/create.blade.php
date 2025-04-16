@extends('admin.layouts.app')

@section('title', 'Добавить значение для атрибута ' . $attribute->name)

@section('content')
<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Добавить значение для "{{ $attribute->name }}"</h2>
        <a href="{{ route('admin.attributes.values.index', $attribute->id) }}" class="btn-secondary-admin">
            Назад к значениям
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Информация о значении
            </h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.attributes.values.store', $attribute->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-6 max-w-md">
                    <div>
                        <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Значение</label>
                        @if($attribute->type == 'color')
                            <div class="flex items-center mb-2">
                                <div class="flex-shrink-0">
                                    <input type="color" name="color_picker" id="color_picker" class="h-10 w-10 rounded border border-gray-300 dark:border-gray-700 cursor-pointer" value="#3498db">
                                </div>
                                <div class="ml-3 flex-grow">
                                    <input type="text" name="value" id="value" class="form-input-admin w-full @error('value') border-red-500 @enderror" value="{{ old('value', '#3498db') }}" placeholder="#HEX код цвета" required>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Выберите цвет с помощью селектора или введите HEX-код вручную</p>
                        @else
                            <input type="text" name="value" id="value" class="form-input-admin w-full @error('value') border-red-500 @enderror" value="{{ old('value') }}" required>
                        @endif
                        @error('value')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                            Сохранить значение
                        </button>
                        <a href="{{ route('admin.attributes.values.index', $attribute->id) }}" class="ml-2 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-medium rounded-lg transition-colors">
                            Отмена
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@if($attribute->type == 'color')
@push('scripts')
<script>
    const colorPicker = document.getElementById('color_picker');
    const valueInput = document.getElementById('value');
    
    // Синхронизация цвета и текстового поля
    colorPicker.addEventListener('input', function() {
        valueInput.value = this.value;
    });
    
    valueInput.addEventListener('input', function() {
        // Проверяем, что введено корректное значение HEX цвета
        const validHex = /^#([0-9A-F]{3}){1,2}$/i;
        if (validHex.test(this.value)) {
            colorPicker.value = this.value;
        }
    });
</script>
@endpush
@endif