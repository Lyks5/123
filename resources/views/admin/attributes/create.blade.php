@extends('admin.layouts.app')

@section('title', 'Создать атрибут')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-4xl mx-auto p-6">
        <!-- Заголовок и навигация -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Создать атрибут
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Создайте новый атрибут для определения характеристик товаров
                    </p>
                </div>
                <a href="{{ route('admin.attributes.index') }}"
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
                <form id="attribute-form" action="{{ route('admin.attributes.store') }}" method="POST" class="space-y-8">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Название атрибута -->
                        <div class="input-group">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Название атрибута
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text"
                                       name="name"
                                       id="name"
                                       class="form-input-admin @error('name') border-red-500 @enderror"
                                       value="{{ old('name') }}"
                                       placeholder="Например: Размер, Цвет, Материал"
                                       required
                                       minlength="3"
                                       maxlength="255">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v4m1.5-2H21l-3 6 3 6H3l3-6-3-6h16.5z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="help-text mt-2">
                                Введите уникальное название атрибута, которое будет отображаться в характеристиках товара
                            </p>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Тип атрибута -->
                        <div class="input-group">
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Тип атрибута
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="type"
                                        id="type"
                                        class="form-input-admin @error('type') border-red-500 @enderror"
                                        required>
                                    <option value="select" {{ old('type') == 'select' ? 'selected' : '' }}>
                                        Выбор из списка
                                    </option>
                                    <option value="radio" {{ old('type') == 'radio' ? 'selected' : '' }}>
                                        Радиокнопки
                                    </option>
                                    <option value="checkbox" {{ old('type') == 'checkbox' ? 'selected' : '' }}>
                                        Флажки
                                    </option>
                                    <option value="color" {{ old('type') == 'color' ? 'selected' : '' }}>
                                        Цвет
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="help-text mt-2">
                                Выберите способ отображения значений атрибута в карточке товара
                            </p>
                            @error('type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Кнопки формы -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button"
                                onclick="window.location.href='{{ route('admin.attributes.index') }}'"
                                class="btn-secondary-admin">
                            Отмена
                        </button>
                        <button type="submit" class="btn-primary-admin">
                            Создать атрибут
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Справка -->
        <div class="mt-6 bg-blue-50 dark:bg-blue-900 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        Подсказка по созданию атрибута
                    </h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <p>Атрибуты помогают определить характеристики товаров в вашем магазине. Выбирайте тип атрибута исходя из того, как покупатели будут выбирать товар:</p>
                        <ul class="list-disc list-inside mt-2">
                            <li>Используйте "Выбор из списка" для большого количества значений</li>
                            <li>Используйте "Радиокнопки" когда важно видеть все варианты сразу</li>
                            <li>Используйте "Флажки" когда можно выбрать несколько значений</li>
                            <li>Используйте "Цвет" для работы с цветовыми характеристиками</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Уведомления -->
    <div id="notifications" class="fixed bottom-4 right-4 z-50"></div>
</div>
@endsection