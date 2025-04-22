@extends('admin.layouts.app')

@section('title', 'Управление записями блога')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Записи блога</h1>
            <a href="{{ route('admin.blog.posts.create') }}" class="bg-eco-600 hover:bg-eco-700 text-white font-bold py-2 px-4 rounded">
                Добавить запись
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif
        
        <!-- Filters -->
        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
            <form action="{{ route('admin.blog.posts.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Поиск</label>
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Поиск по заголовку..."
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50"
                    >
                </div>
                
                <div class="w-[200px]">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Категория</label>
                    <select 
                        id="category" 
                        name="category" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50"
                    >
                        <option value="">Все категории</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="w-[200px]">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
                    <select 
                        id="status" 
                        name="status" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50"
                    >
                        <option value="">Все статусы</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Черновик</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Опубликовано</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Запланировано</option>
                    </select>
                </div>
                
                
                
                <div class="flex items-end">
                    <button type="submit" class="bg-eco-600 hover:bg-eco-700 text-white py-2 px-4 rounded">
                        Фильтровать
                    </button>
                    <a href="{{ route('admin.blog.posts.index') }}" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded">
                        Сбросить
                    </a>
                </div>
            </form>
        </div>

        @if($posts->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr class="text-gray-700">
                            <th class="py-3 px-4 text-left">ID</th>
                            <th class="py-3 px-4 text-left">Изображение</th>
                            <th class="py-3 px-4 text-left">Заголовок</th>
                            <th class="py-3 px-4 text-left">Категории</th>
                            <th class="py-3 px-4 text-left">Статус</th>
                            <th class="py-3 px-4 text-left">Автор</th>
                            <th class="py-3 px-4 text-left">Дата публикации</th>
                            <th class="py-3 px-4 text-left">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($posts as $post)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $post->id }}</td>
                                <td class="py-3 px-4">
                                    @if($post->image)
                                        <img src="{{ $post->image }}" alt="{{ $post->title }}" class="w-16 h-12 object-cover rounded">
                                    @else
                                        <div class="w-16 h-12 bg-gray-200 rounded flex items-center justify-center">
                                            <span class="text-gray-500 text-xs">Нет фото</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-gray-900">{{ $post->title }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($post->excerpt, 50) }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($post->categories as $category)
                                            <span class="px-2 py-1 bg-gray-100 text-xs rounded-full">{{ $category->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    @if($post->status == 'published')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Опубликовано</span>
                                    @elseif($post->status == 'draft')
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Черновик</span>
                                    @elseif($post->status == 'scheduled')
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Запланировано</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">{{ $post->author->name }}</td>
                                <td class="py-3 px-4">{{ $post->published_at ? $post->published_at->format('d.m.Y H:i') : 'Не опубликовано' }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="text-blue-500 hover:text-blue-700" title="Просмотреть">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.blog.posts.edit', $post->id) }}" class="text-yellow-500 hover:text-yellow-700" title="Редактировать">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.blog.posts.delete', $post->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту запись?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700" title="Удалить">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $posts->links() }}
            </div>
        @else
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Записей блога не найдено. Создайте первую запись, нажав кнопку "Добавить запись".
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection