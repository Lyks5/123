@extends('admin.layouts.app')

@section('title', 'Эко-инициативы')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Экологические инициативы</h1>
        <a href="{{ route('admin.initiatives.create') }}" class="btn btn-primary">
            Добавить инициативу
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Название</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Период</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Прогресс</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Действия</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($initiatives as $initiative)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($initiative->image)
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded" src="{{ asset('storage/'.$initiative->image) }}" alt="{{ $initiative->title }}">
                            </div>
                            @endif
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $initiative->title }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $initiative->start_date->format('d.m.Y') }} - 
                        {{ $initiative->end_date?->format('d.m.Y') ?? 'по н.в.' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-24 mr-2">
                                <div class="h-2 bg-gray-200 rounded">
                                    <div class="h-2 bg-eco-700 rounded" style="width: {{ $initiative->progress }}%"></div>
                                </div>
                            </div>
                            <span class="text-sm">{{ $initiative->progress }}%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.initiatives.edit', $initiative) }}" class="text-eco-700 hover:text-eco-900 mr-4">Редактировать</a>
                        <form action="{{ route('admin.initiatives.delete', $initiative) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" 
                                onclick="return confirm('Удалить инициативу?')">Удалить</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $initiatives->links() }}
    </div>
@endsection