@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Просмотр обращения #{{ $request->id }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Панель управления</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.contact-requests.index') }}">Обращения пользователей</a></li>
        <li class="breadcrumb-item active">Просмотр обращения #{{ $request->id }}</li>
    </ol>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-envelope-open me-1"></i>
                    Информация об обращении
                </div>
                <div class="card-body">
                    <dl class="row">
<dt class="col-sm-3">Имя:</dt>
<dd class="col-sm-9">{{ $request->name }}</dd>

                        
<dt class="col-sm-3">Email:</dt>
<dd class="col-sm-9">
    <a href="mailto:{{ $request->email }}">{{ $request->email }}</a>
</dd>

                        
<dt class="col-sm-3">Телефон:</dt>
<dd class="col-sm-9">
    @if($request->phone)
        <a href="tel:{{ $request->phone }}">{{ $request->phone }}</a>
    @else
        <span class="text-muted">Не указан</span>
    @endif
</dd>

                        
<dt class="col-sm-3">Тема:</dt>
<dd class="col-sm-9">{{ $request->subject }}</dd>

                        
<dt class="col-sm-3">Дата создания:</dt>
<dd class="col-sm-9">{{ $request->created_at->format('d.m.Y H:i') }}</dd>

                        
<dt class="col-sm-12">Сообщение:</dt>
<dd class="col-sm-12">
    <div class="p-3 bg-light rounded">
        {!! nl2br(e($request->message)) !!}
    </div>
</dd>

                    </dl>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-pencil me-1"></i>
                    Примечания
                </div>
                <div class="card-body">
@if($request->notes)
    <div class="p-3 bg-light rounded mb-3">
        {!! nl2br(e($request->notes)) !!}
    </div>

                    @else
                        <p class="text-muted">Примечаний пока нет.</p>
                    @endif
                    
                    <form action="{{ route('admin.contact-requests.add.note', $request) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="notes" class="form-label">Добавить примечание</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-gear me-1"></i>
                    Управление
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.contact-requests.update.status', $request) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Статус обращения</label>
                            <select class="form-select" id="status" name="status">
                                <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>
                                    Ожидает
                                </option>
                                <option value="processing" {{ $request->status == 'processing' ? 'selected' : '' }}>
                                    В обработке
                                </option>
                                <option value="completed" {{ $request->status == 'completed' ? 'selected' : '' }}>
                                    Обработано
                                </option>
                                <option value="rejected" {{ $request->status == 'rejected' ? 'selected' : '' }}>
                                    Отклонено
                                </option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Обновить статус</button>
                    </form>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <a href="mailto:{{ $request->email }}?subject=Re: {{ $request->subject }}" class="btn btn-outline-primary">
                            <i class="bi bi-reply"></i> Ответить по Email
                        </a>
                        
@if($request->phone)
    <a href="tel:{{ $request->phone }}" class="btn btn-outline-secondary">

                                <i class="bi bi-telephone"></i> Позвонить
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-info-circle me-1"></i>
                    Статус
                </div>
                <div class="card-body">
<h4>
    @switch($request->status)

                            @case('pending')
                                <span class="badge bg-warning">Ожидает</span>
                                @break
                            @case('processing')
                                <span class="badge bg-info">В обработке</span>
                                @break
                            @case('completed')
                                <span class="badge bg-success">Обработано</span>
                                @break
                            @case('rejected')
                                <span class="badge bg-danger">Отклонено</span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ $contactRequest->status }}</span>
                        @endswitch
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
