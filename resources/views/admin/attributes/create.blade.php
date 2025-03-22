@extends('admin.layouts.app')

@section('title', 'Добавить атрибут')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Добавить атрибут</h2>
        <a href="{{ route('admin.attributes.index') }}" class="btn-secondary-admin">
            Назад к списку
        </a>
    </div>

    <div class="card-admin">
        <div class="p-6">
            <form action="{{ route('admin.attributes.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название</label>
                        <input type="text" name="name" id="name" class="form-input-admin @error('name') border-red-500 @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Тип атрибута</label>
                        <select name="type" id="type" class="form-input-admin @error('type') border-red-500 @enderror" required>
    @if(in_array(old('type'), ['select', 'radio', 'checkbox', 'color']))
        @if(old('type') == 'select')
            <option value="select" selected>Выбор из списка</option>
        @elseif(old('type') == 'radio')
            <option value="radio" selected>Радиокнопки</option>
        @elseif(old('type') == 'checkbox')
            <option value="checkbox" selected>Флажки</option>
        @elseif(old('type') == 'color')
            <option value="color" selected>Цвет</option>
        @endif
    @else
        <option value="">Выберите тип</option>
        <option value="select">Выбор из списка</option>
        <option value="radio">Радиокнопки</option>
        <option value="checkbox">Флажки</option>
        <option value="color">Цвет</option>
    @endif
</select>
                        @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="btn-primary-admin">Сохранить атрибут</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection