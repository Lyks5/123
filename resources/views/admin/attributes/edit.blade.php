@extends('admin.layouts.app')

@section('title', 'Редактировать атрибут')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Редактировать атрибут</h2>
        <a href="{{ route('admin.attributes.index') }}" class="btn-secondary-admin">
            Назад к списку
        </a>
    </div>

    <div class="card-admin">
        <div class="p-6">
            <form action="{{ route('admin.attributes.update', $attribute->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название</label>
                        <input type="text" name="name" id="name" class="form-input-admin @error('name') border-red-500 @enderror" value="{{ old('name', $attribute->name) }}" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Тип атрибута</label>
                        <select name="type" id="type" class="form-input-admin @error('type') border-red-500 @enderror" required>
                            <option value="select" {{ old('type', $attribute->type) == 'select' ? 'selected' : '' }}>Выбор из списка</option>
                            <option value="radio" {{ old('type', $attribute->type) == 'radio' ? 'selected' : '' }}>Радиокнопки</option>
                            <option value="checkbox" {{ old('type', $attribute->type) == 'checkbox' ? 'selected' : '' }}>Флажки</option>
                            <option value="color" {{ old('type', $attribute->type) == 'color' ? 'selected' : '' }}>Цвет</option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between items-center pt-4">
                        <a href="{{ route('admin.attributes.values.index', $attribute->id) }}" class="btn-secondary-admin">
                            Управление значениями ({{ $attribute->values()->count() }})
                        </a>
                        
                        <button type="submit" class="btn-primary-admin">Обновить атрибут</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection