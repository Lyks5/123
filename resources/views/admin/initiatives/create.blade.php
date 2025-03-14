@extends('admin.layouts.app')

@section('title', 'Создать эко-инициативу')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Новая экологическая инициатива</h1>

        <form action="{{ route('admin.initiatives.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="bg-white rounded-lg shadow p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Название</label>
                    <input type="text" name="title" required 
                        class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700"
                        placeholder="Чистый город">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Дата начала</label>
                        <input type="date" name="start_date" required 
                            class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Дата окончания</label>
                        <input type="date" name="end_date" 
                            class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Изображение</label>
                    <input type="file" name="image" accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-eco-50 file:text-eco-700 hover:file:bg-eco-100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
                    <textarea name="description" rows="5" required
                        class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700"
                        placeholder="Подробное описание инициативы"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Метрика</label>
                        <input type="text" name="impact_metric" 
                            class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700"
                            placeholder="Собрано пластика">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Значение</label>
                        <input type="number" name="impact_value" 
                            class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700"
                            placeholder="1500">
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.initiatives.index') }}" 
                        class="btn btn-gray">Отмена</a>
                    <button type="submit" 
                        class="btn btn-primary">Создать инициативу</button>
                </div>
            </div>
        </form>
    </div>
@endsection