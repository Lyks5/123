@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Добавление атрибута</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Панель управления</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Атрибуты товаров</a></li>
        <li class="breadcrumb-item active">Добавление атрибута</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-plus-circle me-1"></i>
            Форма добавления атрибута
        </div>
        <div class="card-body">
            <form action="{{ route('admin.attributes.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Название атрибута</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="type" class="form-label">Тип атрибута</label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="select" {{ old('type') == 'select' ? 'selected' : '' }}>Выбор (значения из списка)</option>
                        <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Текст</option>
                        <option value="number" {{ old('type') == 'number' ? 'selected' : '' }}>Число</option>
                        <option value="color" {{ old('type') == 'color' ? 'selected' : '' }}>Цвет</option>
                        <option value="boolean" {{ old('type') == 'boolean' ? 'selected' : '' }}>Да/Нет</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection