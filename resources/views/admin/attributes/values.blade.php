@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Значения атрибута "{{ $attribute->name }}"</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Панель управления</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Атрибуты товаров</a></li>
        <li class="breadcrumb-item active">Значения атрибута</li>
    </ol>
    
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-list me-1"></i>
                    Список значений
                </div>
                <div class="card-body">
                    @if($attribute->values->count() > 0)
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Значение</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attribute->values as $value)
                                <tr>
                                    <td>{{ $value->id }}</td>
                                    <td>
                                        @if($attribute->type == 'color')
                                            <span class="d-inline-block me-2" style="width: 20px; height: 20px; background-color: {{ $value->value }}; border: 1px solid #ccc;"></span>
                                            {{ $value->value }}
                                        @else
                                            {{ $value->value }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#editValueModal{{ $value->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('admin.attributes.values.delete', [$attribute, $value]) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить это значение?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <!-- Edit Value Modal -->
                                        <div class="modal fade" id="editValueModal{{ $value->id }}" tabindex="-1" aria-labelledby="editValueModalLabel{{ $value->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editValueModalLabel{{ $value->id }}">Редактировать значение</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('admin.attributes.values.update', [$attribute, $value]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="value{{ $value->id }}" class="form-label">Значение</label>
                                                                @if($attribute->type == 'color')
                                                                    <input type="color" class="form-control form-control-color" id="value{{ $value->id }}" name="value" value="{{ $value->value }}">
                                                                @else
                                                                    <input type="text" class="form-control" id="value{{ $value->id }}" name="value" value="{{ $value->value }}" required>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                                            <button type="submit" class="btn btn-primary">Сохранить</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Для этого атрибута пока не добавлено ни одного значения.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-plus-circle me-1"></i>
                    Добавить новое значение
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.attributes.values.store', $attribute) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="value" class="form-label">Значение</label>
                            @if($attribute->type == 'color')
                                <input type="color" class="form-control form-control-color @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value', '#ffffff') }}">
                            @else
                                <input type="text" class="form-control @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value') }}" required>
                            @endif
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Добавить значение</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-info-circle me-1"></i>
                    Информация об атрибуте
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Название:</dt>
                        <dd class="col-sm-8">{{ $attribute->name }}</dd>
                        
                        <dt class="col-sm-4">Тип:</dt>
                        <dd class="col-sm-8">
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
                        </dd>
                        
                        <dt class="col-sm-4">Количество значений:</dt>
                        <dd class="col-sm-8">{{ $attribute->values->count() }}</dd>
                    </dl>
                    
                    <div class="mt-3">
                        <a href="{{ route('admin.attributes.edit', $attribute) }}" class="btn btn-info">
                            <i class="bi bi-pencil"></i> Редактировать атрибут
                        </a>
                        <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Назад к списку
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
