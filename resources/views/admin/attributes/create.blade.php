@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Добавить атрибут</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Панель управления</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">Атрибуты товаров</a></li>
        <li class="breadcrumb-item active">Добавить атрибут</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-plus-circle me-1"></i>
            Добавить новый атрибут
        </div>
        <div class="card-body">
            <form action="{{ route('admin.attributes.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Название атрибута</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Тип атрибута</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="select">Выбор</option>
                        <option value="text">Текст</option>
                        <option value="number">Число</option>
                        <option value="color">Цвет</option>
                        <option value="boolean">Да/Нет</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Сохранить атрибут</button>
                <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
</div>
@endsection
