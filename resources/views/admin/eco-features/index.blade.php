@extends('admin.layouts.app')

@section('title', 'Эко-характеристики')

@section('content')
<div class="container px-6 mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Эко-характеристики</h1>
        <a href="{{ route('admin.eco-features.create') }}" class="bg-eco-600 hover:bg-eco-700 text-white font-bold py-2 px-4 rounded">
            Добавить
        </a>
    </div>
    
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    
    @if($errors->has('general'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p>{{ $errors->first('general') }}</p>
        </div>
    @endif
    
    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-4 mb-6">
        <form action="{{ route('admin.eco-features.index') }}" method="GET">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex-grow">
                    <label for="search" class="sr-only">Поиск</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Поиск по названию или описанию..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-eco-500 focus:border-eco-500">
                </div>
                <div>
                    <label for="sort" class="sr-only">Сортировка</label>
                    <select name="sort" id="sort" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-eco-500 focus:border-eco-500">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>По названию</option>
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Сначала новые</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Сначала старые</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="bg-eco-600 hover:bg-eco-700 text-white font-bold py-2 px-4 rounded">
                        Применить
                    </button>
                    @if(request()->anyFilled(['search', 'sort']))
                        <a href="{{ route('admin.eco-features.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded ml-2">
                            Сбросить
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
    
    <!-- Features list -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Название
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Slug
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Описание
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Иконка
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Товары
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Действия
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($ecoFeatures as $feature)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $feature->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500">{{ $feature->slug }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500">{{ Str::limit($feature->description, 50) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($feature->icon)
                                <div class="text-sm text-gray-500">
                                    <i class="{{ $feature->icon }}"></i>
                                    <span class="ml-1">{{ $feature->icon }}</span>
                                </div>
                            @else
                                <div class="text-sm text-gray-400">Нет иконки</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $feature->products()->count() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.eco-features.edit', $feature) }}" class="text-eco-600 hover:text-eco-900 mr-3">
                                Редактировать
                            </a>
                            <form action="{{ route('admin.eco-features.delete', $feature) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Вы уверены?')">
                                    Удалить
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            Эко-характеристики не найдены
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $ecoFeatures->links() }}
    </div>
</div>
@endsection