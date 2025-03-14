@extends('admin.layouts.app')

@section('title', 'Создать категорию блога')

@section('content')
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Новая категория блога</h1>

        <form action="{{ route('admin.blog.categories.store') }}" method="POST">
            @csrf

            <div class="bg-white rounded-lg shadow p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Название категории</label>
                    <input type="text" name="name" required 
                        class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700"
                        placeholder="Экологичный образ жизни">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">URL-адрес</label>
                    <input type="text" name="slug" required 
                        class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700"
                        placeholder="eco-life">
                    <p class="mt-1 text-sm text-gray-500">Уникальный идентификатор для URL</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700"
                        placeholder="Краткое описание категории"></textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.blog.categories.index') }}" 
                        class="btn btn-gray">Отмена</a>
                    <button type="submit" 
                        class="btn btn-primary">Создать категорию</button>
                </div>
            </div>
        </form>
    </div>
@endsection