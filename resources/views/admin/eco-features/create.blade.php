@extends('admin.layouts.app')

@section('title', 'Создание эко-характеристики')

@section('content')
<div class="container px-6 mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Создание эко-характеристики</h1>
        <p class="text-gray-600 mt-1">Заполните информацию о новой эко-характеристике</p>
    </div>
    
    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.eco-features.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 gap-6">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Название</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Описание</label>
                    <textarea name="description" id="description" rows="3" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="icon" class="block text-sm font-medium text-gray-700">Иконка (CSS класс)</label>
                    <input type="text" name="icon" id="icon" value="{{ old('icon') }}" placeholder="fa fa-leaf"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                    <p class="text-xs text-gray-500 mt-1">Например: fa fa-leaf, bi bi-tree, etc.</p>
                    @error('icon')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="flex justify-end mt-6">
                <a href="{{ route('admin.eco-features.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                    Отмена
                </a>
                <button type="submit" class="bg-eco-600 hover:bg-eco-700 text-white font-bold py-2 px-4 rounded">
                    Сохранить
                </button>
            </div>
        </form>
    </div>
</div>
@endsection