@extends('admin.layouts.app')

@section('title', 'Редактирование эко-характеристики')

@section('content')
<div class="container px-6 mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Редактирование эко-характеристики</h1>
        <p class="text-gray-600 mt-1">Измените информацию об эко-характеристике</p>
    </div>
    
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    
    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.eco-features.update', $ecoFeature) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-6">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Название</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $ecoFeature->name) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="slug" class="block text-sm font-medium text-gray-700">Slug (автоматически создается из названия)</label>
                    <input type="text" value="{{ $ecoFeature->slug }}" disabled readonly
                        class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                    <p class="text-xs text-gray-500 mt-1">Slug будет автоматически обновлен при изменении названия</p>
                </div>
                
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Описание</label>
                    <textarea name="description" id="description" rows="3" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">{{ old('description', $ecoFeature->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="icon" class="block text-sm font-medium text-gray-700">Иконка (CSS класс)</label>
                    <input type="text" name="icon" id="icon" value="{{ old('icon', $ecoFeature->icon) }}" placeholder="fa fa-leaf"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                    <p class="text-xs text-gray-500 mt-1">Например: fa fa-leaf, bi bi-tree, etc.</p>
                    @error('icon')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Связанные товары</h3>
                    @if($ecoFeature->products->count() > 0)
                        <ul class="list-disc list-inside text-sm text-gray-600">
                            @foreach($ecoFeature->products as $product)
                                <li>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-eco-600 hover:underline">
                                        {{ $product->name }} 
                                        @if($product->pivot->value)
                                            <span class="text-gray-500">({{ $product->pivot->value }})</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500">Нет связанных товаров</p>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <label for="is_active" class="inline-flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $ecoFeature->is_active) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-eco-600 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                    <span class="ml-2 text-sm font-medium text-gray-700">Активна</span>
                </label>
                @error('is_active')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
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