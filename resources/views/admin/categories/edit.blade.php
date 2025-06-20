@extends('admin.layouts.app')

@section('title', 'Редактирование категории')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Редактирование категории</h1>
            <p class="text-gray-600 mt-1">{{ $category->name }}</p>
        </div>

        <form action="{{ route('admin.categories.update', $category->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700">Название категории</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
            </div>
            
            <div class="mb-6">
                <label for="parent_id" class="block text-sm font-medium text-gray-700">Родительская категория (опционально)</label>
                <select name="parent_id" id="parent_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                    <option value="">Нет родительской категории</option>
                    @foreach($categories as $parentCategory)
                        <option value="{{ $parentCategory->id }}" {{ (old('parent_id', $category->parent_id) == $parentCategory->id) ? 'selected' : '' }}>
                            {{ $parentCategory->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700">Описание</label>
                <textarea name="description" id="description" rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">{{ old('description', $category->description) }}</textarea>
            </div>
            
            <div class="mb-6">
                <div class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-eco-600 focus:ring-eco-500">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Категория активна</label>
                </div>
                @error('is_active')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end">
                <a href="{{ route('admin.categories.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                    Отмена
                </a>
                <button type="submit" class="bg-eco-600 hover:bg-eco-700 text-white font-bold py-2 px-4 rounded">
                    Сохранить изменения
                </button>
            </div>
        </form>
    </div>

@endsection