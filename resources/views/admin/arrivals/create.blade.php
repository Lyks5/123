@extends('admin.layouts.app')

@section('title', 'Создание поступления')

@section('content')
<div class="container px-6 mx-auto">
    <div class="py-4">
        <h2 class="text-2xl font-semibold text-gray-800">Создание поступления</h2>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 max-w-3xl">
        <form action="{{ route('admin.arrivals.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Название -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Название товара</label>
                    <input type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Статус -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Статус</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Активно</option>
                        <option value="used" {{ old('status') == 'used' ? 'selected' : '' }}>Использовано</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Количество -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Количество</label>
                    <input type="number"
                        name="quantity"
                        id="quantity"
                        min="1"
                        value="{{ old('quantity') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                        required
                    >
                    @error('quantity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Дата поступления -->
                <div>
                    <label for="arrival_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Дата поступления</label>
                    <input type="date"
                        name="arrival_date"
                        id="arrival_date"
                        value="{{ old('arrival_date', date('Y-m-d')) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                        required
                    >
                    @error('arrival_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Цена закупки -->
                <div>
                    <label for="purchase_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Цена закупки</label>
                    <div class="relative">
                        <input type="number"
                            name="purchase_price"
                            id="purchase_price"
                            step="0.01"
                            min="0"
                            value="{{ old('purchase_price') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                            required
                        >
                        <span class="absolute right-3 top-2 text-gray-500">₽</span>
                    </div>
                    @error('purchase_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Комментарий -->
                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Комментарий (необязательно)</label>
                    <textarea
                        name="comment"
                        id="comment"
                        rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                    >{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Кнопки -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.arrivals.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Отмена
                    </a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Сохранить
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection