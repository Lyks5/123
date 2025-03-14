@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Обращения пользователей</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Панель управления</a></li>
        <li class="breadcrumb-item active">Обращения пользователей</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-envelope me-1"></i>
            Список обращений
        </div>
        <div class="card-body">
            <table class="table table-bordered datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Тема</th>
                        <th>Статус</th>
                        <th>Дата</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                    <tr>
                        <td>{{ $request->id }}</td>
                        <td>{{ $request->name }}</td>
                        <td>{{ $request->email }}</td>
                        <td>{{ $request->subject }}</td>
                        <td>
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
                                    <span class="badge bg-secondary">{{ $request->status }}</span>
                            @endswitch
                        </td>
                        <td>{{ $request->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.contact-requests.show', $request) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection