@extends('admin.layouts.app')

@section('title', 'Редактировать эко-инициативу')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Редактировать экологическую инициативу</h1>

        <form action="{{ route('admin.initiatives.update', $initiative) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-lg shadow p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Название</label>
                    <input type="text" name="title" required 
                        class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700"
                        value="{{ old('title', $initiative->title) }}"
                        placeholder="Чистый город">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Цель</label>
                    <input type="text" name="goal" required 
                        class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700"
                        value="{{ old('goal', $initiative->goal) }}"
                        placeholder="Опишите цель инициативы">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Дата начала</label>
                        <input type="date" name="start_date" required 
                            class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700"
                            value="{{ old('start_date', $initiative->start_date) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Дата окончания</label>
                        <input type="date" name="end_date" 
                            class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700"
                            value="{{ old('end_date', $initiative->end_date) }}">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Изображение</label>
                    <input type="file" name="image" accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-eco-50 file:text-eco-700 hover:file:bg-eco-100">
                    @if ($initiative->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $initiative->image) }}" alt="{{ $initiative->title }}" class="w-32 h-32 object-cover rounded">
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
                    <textarea name="description" rows="5" required
                        class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700"
                        placeholder="Подробное описание инициативы">{{ old('description', $initiative->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Метрика</label>
                        <input type="text" name="impact_metric" 
                            class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700"
                            value="{{ old('impact_metric', $initiative->impact_metric) }}"
                            placeholder="Собрано пластика">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Значение</label>
                        <input type="number" name="impact_value" 
                            class="w-full rounded border-gray-300 focus:border-eco-700 focus:ring-eco-700"
                            value="{{ old('impact_value', $initiative->impact_value) }}"
                            placeholder="1500">
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.initiatives.index') }}" 
                        class="btn btn-gray">Отмена</a>
                    <button type="submit" 
                        class="btn btn-primary">Обновить инициативу</button>
                </div>
            </div>
        </form>
    </div>
@endsection