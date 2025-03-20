@extends('admin.layouts.app')

@section('title', 'Редактирование экологической инициативы')

@section('content')
<div>
    <h1 class="text-2xl font-bold mb-6">Редактирование экологической инициативы</h1>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.initiatives.update', $initiative->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название</label>
                <input type="text" name="title" id="title" value="{{ old('title', $initiative->title) }}" 
                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                @error('title')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL-slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $initiative->slug) }}" 
                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                @error('slug')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Краткое описание</label>
                <textarea name="description" id="description" rows="3" 
                          class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">{{ old('description', $initiative->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Полное описание</label>
                <textarea name="content" id="content" rows="10" 
                          class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">{{ old('content', $initiative->content) }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Изображение</label>
                @if($initiative->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $initiative->image) }}" alt="{{ $initiative->title }}" class="w-32 h-32 object-cover rounded">
                    </div>
                @endif
                <input type="file" name="image" id="image" 
                       class="w-full border border-gray-300 dark:border-gray-700 rounded-md shadow-sm py-2 px-3 dark:bg-gray-900 dark:text-white">
                @error('image')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Статус</label>
                <select name="status" id="status" 
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                    <option value="active" {{ old('status', $initiative->status) == 'active' ? 'selected' : '' }}>Активная</option>
                    <option value="completed" {{ old('status', $initiative->status) == 'completed' ? 'selected' : '' }}>Завершенная</option>
                    <option value="upcoming" {{ old('status', $initiative->status) == 'upcoming' ? 'selected' : '' }}>Предстоящая</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="target_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Целевая сумма (₽)</label>
                <input type="number" name="target_amount" id="target_amount" value="{{ old('target_amount', $initiative->target_amount) }}" 
                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                @error('target_amount')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="current_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Текущая сумма (₽)</label>
                <input type="number" name="current_amount" id="current_amount" value="{{ old('current_amount', $initiative->current_amount) }}" 
                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                @error('current_amount')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.initiatives.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Отмена
                </a>
                <button type="submit" class="px-4 py-2 bg-eco-600 text-white rounded-md hover:bg-eco-700 transition-colors">
                    Сохранить
                </button>
            </div>
        </form>
    </div>
</div>
@endsection