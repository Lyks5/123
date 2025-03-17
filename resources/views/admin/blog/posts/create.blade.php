@extends('admin.layouts.app')

@section('title', 'Добавить статью')

@section('content')
    <div class="max-w-5xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Новая статья блога</h1>

        <form action="{{ route('admin.blog.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="bg-white rounded-lg shadow p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Заголовок</label>
                    <input type="text" name="title" required
                        class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700">
                </div>

                <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Краткое описание</label>
        <textarea name="excerpt" rows="3" required
            class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700"
            placeholder="Краткий анонс статьи (до 255 символов)"></textarea>
    </div>
    
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Категории</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($categories as $category)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                class="rounded border-gray-300 text-eco-700">
                            <span>{{ $category->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Обложка</label>
                    <input type="file" name="featured_image" accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-eco-50 file:text-eco-700 hover:file:bg-eco-100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Контент</label>
                    <textarea name="content" id="editor" rows="15"
                        class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Статус</label>
                        <select name="status" class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700">
                            <option value="draft">Черновик</option>
                            <option value="published">Опубликовано</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Дата публикации</label>
                        <input type="datetime-local" name="published_at"
                            class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700">
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-gray">Отмена</a>
                    <button type="submit" class="btn btn-primary">Опубликовать статью</button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.3.2/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => console.error(error));
    </script>
    @endpush
@endsection