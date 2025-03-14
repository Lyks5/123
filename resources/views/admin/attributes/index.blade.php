@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Атрибуты товаров</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Панель управления</a></li>
        <li class="breadcrumb-item active">Атрибуты товаров</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-tags me-1"></i>
                    Список атрибутов
                </div>
                <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Добавить атрибут
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Тип</th>
                        <th>Значения</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attributes as $attribute)
                    <tr>
                        <td>{{ $attribute->id }}</td>
                        <td>{{ $attribute->name }}</td>
                        <td>
                            @switch($attribute->type)
                                @case('select')
                                    Выбор
                                    @break
                                @case('text')
                                    Текст
                                    @break
                                @case('number')
                                    Число
                                    @break
                                @case('color')
                                    Цвет
                                    @break
                                @case('boolean')
                                    Да/Нет
                                    @break
                                @default
                                    {{ $attribute->type }}
                            @endswitch
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $attribute->values->count() }}</span>
                            <a href="{{ route('admin.attributes.values.index', $attribute) }}" class="btn btn-sm btn-outline-primary ms-2">
                                <i class="bi bi-list"></i> Управлять
                            </a>
                        </td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('admin.attributes.edit', $attribute) }}" class="btn btn-sm btn-info me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.attributes.delete', $attribute) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот атрибут?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection