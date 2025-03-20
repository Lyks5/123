@extends('admin.layouts.app')

@section('title', 'Редактирование категории блога')

@section('content')
<div>
    <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Редактирование категории блога</h1>
    
    <div class="card-admin dark:bg-gray-800 dark:border-gray-700 p-6">
        <form action="{{ route('admin.blog.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" 
                       class="form-input-admin">
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL-slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" 
                       class="form-input-admin">
                @error('slug')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание</label>
                <textarea name="description" id="description" rows="4" 
                          class="form-input-admin">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.blog.categories.index') }}" class="btn-secondary-admin">
                    Отмена
                </a>
                <button type="submit" class="btn-primary-admin">
                    Сохранить
                </button>
            </div>
        </form>
    </div>
</div>
@endsection