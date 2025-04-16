@extends('admin.layouts.app')

@section('title', 'Редактировать значение для атрибута ' . $attribute->name)

@section('content')
<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Редактировать значение для "{{ $attribute->name }}"</h2>
        <a href="{{ route('admin.attributes.values.index', $attribute->id) }}" class="btn-secondary-admin">
            Назад к значениям
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 20h9"></path>
                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                </svg>
                Редактирование значения
            </h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.attributes.values.update', [$attribute->id, $value->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6 max-w-md">
                    <div>
                        <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Значение</label>
                        @if($attribute->type == 'color')
                            <div class="flex items-center gap-4 mb-2">
                                <div class="flex-shrink-0 relative">
                                    <input type="color" name="color_picker" id="color_picker" class="h-16 w-16 rounded-lg border border-gray-300 dark:border-gray-700 cursor-pointer" value="{{ old('value', $value->value) }}">
                                    <div class="absolute inset-0 rounded-lg shadow-sm pointer-events-none"></div>
                                </div>
                                <div class="flex-grow">
                                    <input type="text" name="value" id="value" class="form-input-admin w-full @error('value') border-red-500 @enderror" value="{{ old('value', $value->value) }}" placeholder="#HEX код цвета" required>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">HEX-код цвета (например, #3498db)</p>
                                </div>
                            </div>
                        @else
                            <input type="text" name="value" id="value" class="form-input-admin w-full @error('value') border-red-500 @enderror" value="{{ old('value', $value->value) }}" required>
                        @endif
                        @error('value')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 flex items-center justify-between">
                        <div>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                                Сохранить изменения
                            </button>
                            <a href="{{ route('admin.attributes.values.index', $attribute->id) }}" class="ml-2 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-medium rounded-lg transition-colors">
                                Отмена
                            </a>
                        </div>
                        
                        @if($value->variants_count == 0)
                        <form action="{{ route('admin.attributes.values.destroy', [$attribute->id, $value->id]) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors" onclick="return confirm('Уверены, что хотите удалить это значение?')">
                                Удалить значение
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    @if($value->variants_count > 0)
    <div class="bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-200 dark:border-amber-800 p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-amber-800 dark:text-amber-200">Значение используется</h3>
                <div class="mt-1 text-sm text-amber-700 dark:text-amber-300">
                    Это значение используется в {{ $value->variants_count }} {{ trans_choice('вариантах|варианте|вариантах', $value->variants_count) }} товаров. Удаление невозможно.
                </div>
            </div>
        </div>
    </div>
    @endif
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