@extends('admin.layouts.app')

@section('title', 'Редактировать значение атрибута')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-4xl mx-auto p-6">
        <!-- Заголовок и навигация -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Редактировать значение атрибута "{{ $attribute->name }}"
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Измените значение атрибута и его параметры
                    </p>
                </div>
                <a href="{{ route('admin.attributes.values.index', $attribute) }}"
                   class="btn-secondary-admin transition-transform duration-200 ease-in-out transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Назад к списку
                </a>
            </div>
        </div>

        <!-- Основная карточка -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="p-8">
                <form action="{{ route('admin.attributes.values.update', ['attribute' => $attribute->id, 'valueId' => $value->id]) }}"
                      method="POST"
                      class="space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Значение -->
                        <div class="input-group">
                            <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Значение
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="value"
                                   id="value"
                                   class="form-input-admin @error('value') border-red-500 @enderror"
                                   value="{{ old('value', $value->value) }}"
                                   required>
                            @error('value')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Порядок отображения -->
                        <div class="input-group">
                            <label for="display_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Порядок отображения
                            </label>
                            <input type="number"
                                   name="display_order"
                                   id="display_order"
                                   class="form-input-admin @error('display_order') border-red-500 @enderror"
                                   value="{{ old('display_order', $value->display_order) }}"
                                   min="0">
                            @error('display_order')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($attribute->type === 'color')
                        <!-- Цвет -->
                        <div class="input-group">
                            <label for="hex_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Цвет (HEX)
                            </label>
                            <div class="flex items-center space-x-2">
                                <input type="color"
                                       name="hex_color"
                                       id="hex_color"
                                       class="form-color-input @error('hex_color') border-red-500 @enderror"
                                       value="{{ old('hex_color', $value->hex_color) }}">
                                <input type="text"
                                       id="hex_color_text"
                                       class="form-input-admin"
                                       value="{{ old('hex_color', $value->hex_color) }}"
                                       readonly>
                            </div>
                            @error('hex_color')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif
                    </div>

                    <!-- Кнопки формы -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button"
                                onclick="window.location.href='{{ route('admin.attributes.values.index', $attribute) }}'"
                                class="btn-secondary-admin">
                            Отмена
                        </button>
                        <button type="submit" class="btn-primary-admin">
                            Сохранить изменения
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorInput = document.getElementById('hex_color');
        const textInput = document.getElementById('hex_color_text');

        if (colorInput && textInput) {
            colorInput.addEventListener('input', function(e) {
                textInput.value = e.target.value.toUpperCase();
            });
        }
    });
</script>
@endpush
@endsection