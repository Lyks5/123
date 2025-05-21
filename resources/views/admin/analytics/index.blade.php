@extends('admin.layouts.app')

@section('title', 'Аналитика')

@push('styles')
    <link href="{{ asset('css/analytics.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/tippy.js@6/dist/tippy-bundle.umd.min.js"></script>
    <script src="{{ asset('js/analytics.js') }}"></script>
@endpush

@section('content')
<div x-data="{ mobileFiltersOpen: false }" class="bg-gray-100 dark:bg-gray-900 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold dark:text-white">Аналитика</h1>
            @include('admin.analytics.components.filters')
        </div>

        @include('admin.analytics.components.summary-cards')

        <!-- Графики -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
            @include('admin.analytics.components.charts.revenue')
            @include('admin.analytics.components.charts.categories')
        </div>

        <!-- Экологическая аналитика -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-8">
            @include('admin.analytics.components.environmental')
            @include('admin.analytics.components.top-products')
        </div>

        <!-- Пользователи и отзывы -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
            @include('admin.analytics.components.top-customers')
            @include('admin.analytics.components.reviews')
        </div>
    </div>
</div>
@endsection