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

    <div class="card-admin">
        <div class="p-6">
        <form action="{{ route('admin.attributes.values.store', $attribute->id) }}" method="POST">
    @csrf
    <input type="hidden" name="type" value="{{ $attribute->type }}"> <!-- Передаем тип атрибута -->
    
    <div class="grid grid-cols-1 gap-6">
        <div>
            <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Значение</label>
            @if($attribute->type == 'color')
                <div class="flex space-x-3">
                    <input type="color" name="color_picker" id="color_picker" class="h-10 w-10 rounded border border-gray-300 dark:border-gray-700 cursor-pointer" value="#3498db">
                    <input type="text" name="value" id="value" class="form-input-admin @error('value') border-red-500 @enderror" value="{{ old('value', '#3498db') }}" required>
                </div>
            @else
                <input type="text" name="value" id="value" class="form-input-admin @error('value') border-red-500 @enderror" value="{{ old('value') }}" required>
            @endif
            @error('value')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-4">
            <button type="submit" class="btn-primary-admin">Сохранить значение</button>
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