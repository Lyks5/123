@extends('admin.layouts.app')

@section('title', 'Просмотр обращения #' . $contactRequest->id)

@section('content')
<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Просмотр обращения #{{ $contactRequest->id }}</h1>
        <a href="{{ route('admin.contact-requests.index') }}" class="btn-secondary-admin">
            Назад к списку
        </a>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card-admin dark:bg-gray-800 dark:border-gray-700 p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">{{ $contactRequest->subject }}</h2>
                
                <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="prose dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300">{{ $contactRequest->message }}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Отправитель</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $contactRequest->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $contactRequest->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Телефон</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $contactRequest->phone ?: 'Не указан' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Дата обращения</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $contactRequest->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="lg:col-span-1">
            <div class="card-admin dark:bg-gray-800 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Статус обращения</h3>
                
                <form action="{{ route('admin.contact-requests.update.status', $contactRequest->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Изменить статус</label>
                        <select name="status" id="status" class="form-input-admin">
                            <option value="new" {{ $contactRequest->status == 'new' ? 'selected' : '' }}>Новое</option>
                            <option value="in_progress" {{ $contactRequest->status == 'in_progress' ? 'selected' : '' }}>В обработке</option>
                            <option value="closed" {{ $contactRequest->status == 'closed' ? 'selected' : '' }}>Закрыто</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-primary-admin w-full">
                        Обновить статус
                    </button>
                </form>
                
                @if($contactRequest->notes)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Заметки</h4>
                        <p class="text-gray-600 dark:text-gray-400">{{ $contactRequest->notes }}</p>
                    </div>
                @endif
                
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <form action="{{ route('admin.contact-requests.destroy', $contactRequest->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger-admin w-full" onclick="return confirm('Вы уверены, что хотите удалить это обращение?')">
                            Удалить обращение
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
