@extends('layouts.app')

@section('title', 'Магазин - Экологичные спортивные товары')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-eco-50 py-20 lg:py-24 overflow-hidden">
        <div class="container-width relative px-4 sm:px-6 lg:px-8 pt-16">
            <div class="max-w-3xl">
                <h1 class="text-4xl sm:text-5xl font-bold text-eco-900 mb-6">
                    Экологичное спортивное снаряжение
                </h1>
                <p class="text-xl text-eco-800 mb-8 max-w-2xl">
                    Откройте для себя нашу коллекцию экологически ответственных спортивных товаров, созданных с заботой о планете и вашем здоровье.
                </p>
            </div>
        </div>
    </section>
    
    <!-- Shop Section -->
    <section class="section-padding bg-white">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">
                <!-- Sidebar Filters -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24 space-y-8">
                        <!-- Активные фильтры -->
                        <div id="active-filters" class="flex flex-wrap gap-2"></div>

                        <!-- Categories -->
                        <div class="bg-eco-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-eco-900 mb-4">Категории</h3>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                        id="category-all" 
                                        name="category[]" 
                                        value="all" 
                                        class="rounded border-eco-300 text-eco-600 focus:ring-eco-500"
                                        {{ request()->category == 'all' ? 'checked' : '' }}
                                    >
                                    <label for="category-all" class="ml-2 text-eco-800">Все категории</label>
                                </div>
                                
                                @foreach($categories as $category)
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                        id="category-{{ $category->id }}" 
                                        name="category[]" 
                                        value="{{ $category->id }}" 
                                        class="rounded border-eco-300 text-eco-600 focus:ring-eco-500"
                                        {{ in_array($category->id, explode(',', request()->category ?? '')) ? 'checked' : '' }}
                                    >
                                    <label for="category-{{ $category->id }}" class="ml-2 text-eco-800">
                                        {{ $category->name }}
                                        <span class="text-eco-600 text-sm">({{ $category->products_count }})</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="bg-eco-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-eco-900 mb-4">Цена</h3>
                            <div class="space-y-4">
                                <div id="price-range" class="mb-4"></div>
                                <div class="flex items-center justify-between">
                                    <input type="number" 
                                        id="min-price" 
                                        name="min_price" 
                                        min="{{ $priceRange['min'] }}" 
                                        max="{{ $priceRange['max'] }}" 
                                        value="{{ request()->min_price ?? $priceRange['min'] }}"
                                        placeholder="От" 
                                        class="w-[45%] rounded-lg border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                    >
                                    <span class="text-eco-800">—</span>
                                    <input type="number" 
                                        id="max-price" 
                                        name="max_price" 
                                        min="{{ $priceRange['min'] }}" 
                                        max="{{ $priceRange['max'] }}" 
                                        value="{{ request()->max_price ?? $priceRange['max'] }}"
                                        placeholder="До" 
                                        class="w-[45%] rounded-lg border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Eco Score -->
                        <div class="bg-eco-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-eco-900 mb-4">Эко-рейтинг</h3>
                            <div class="space-y-2">
                                <select id="eco-score" name="eco_score" class="w-full rounded-lg border-eco-300 focus:border-eco-500 focus:ring-eco-500">
                                    <option value="">Все</option>
                                    <option value="90" {{ request()->eco_score == '90' ? 'selected' : '' }}>90+ Отлично</option>
                                    <option value="80" {{ request()->eco_score == '80' ? 'selected' : '' }}>80+ Очень хорошо</option>
                                    <option value="70" {{ request()->eco_score == '70' ? 'selected' : '' }}>70+ Хорошо</option>
                                    <option value="60" {{ request()->eco_score == '60' ? 'selected' : '' }}>60+ Удовлетворительно</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Eco Features -->
                        <div class="bg-eco-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-eco-900 mb-4">Эко-особенности</h3>
                            <div class="space-y-2">
                                @foreach($ecoFeatures as $feature)
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                        id="feature-{{ $feature->id }}" 
                                        name="features[]" 
                                        value="{{ $feature->id }}" 
                                        class="rounded border-eco-300 text-eco-600 focus:ring-eco-500"
                                        {{ in_array($feature->id, explode(',', request()->eco_features ?? '')) ? 'checked' : '' }}
                                    >
                                    <label for="feature-{{ $feature->id }}" class="ml-2 text-eco-800">
                                        {{ $feature->name }}
                                        <span class="text-eco-600 text-sm">({{ $feature->products_count }})</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Clear Filters -->
                        <button id="clear-filters" class="w-full py-2 mb-6 border border-eco-300 bg-white text-eco-800 font-semibold rounded-lg transition-colors hover:bg-eco-100">
                            Очистить фильтры
                        </button>
                    </div>
                </div>
                
                <!-- Product Grid -->
                <div class="lg:col-span-3">
                    <!-- Sort Options -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 pb-4 border-b border-eco-100">
                        <div class="mb-4 sm:mb-0 flex flex-col gap-2">
                            <span id="products-count" class="text-eco-800">
                                Показано {{ $products->firstItem() }} - {{ $products->lastItem() }} из {{ $products->total() }} товаров
                            </span>
                            <form method="get" class="flex gap-2">
                                <input
                                    type="text"
                                    id="product-search"
                                    name="search"
                                    value="{{ request()->search ?? '' }}"
                                    placeholder="Поиск по названию"
                                    class="w-full sm:w-72 rounded-lg border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                    autocomplete="off"
                                >
                                <button type="submit" class="px-4 py-2 bg-eco-600 text-white rounded hover:bg-eco-700">
                                    Найти
                                </button>
                            </form>
                        </div>
                        <div class="flex items-center">
                            <span class="text-eco-800 mr-2">Сортировать по:</span>
                            <select id="sort" name="sort" class="rounded-lg border-eco-300 focus:border-eco-500 focus:ring-eco-500">
                                <option value="default" {{ request()->sort == 'default' ? 'selected' : '' }}>По умолчанию</option>
                                <option value="popular" {{ request()->sort == 'popular' ? 'selected' : '' }}>Популярности</option>
                                <option value="newest" {{ request()->sort == 'newest' ? 'selected' : '' }}>Новизне</option>
                                <option value="price-low" {{ request()->sort == 'price-low' ? 'selected' : '' }}>Цене: низкая-высокая</option>
                                <option value="price-high" {{ request()->sort == 'price-high' ? 'selected' : '' }}>Цене: высокая-низкая</option>
                                <option value="eco-high" {{ request()->sort == 'eco-high' ? 'selected' : '' }}>Эко-рейтингу</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Products Loader -->
                    <div id="products-loader" class="flex justify-center items-center py-12 hidden">
                        <svg class="animate-spin h-10 w-10 text-eco-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                    </div>
                    <!-- Products -->
                    <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @include('components.product-grid', ['products' => $products])
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $products->links('components.custom-pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Newsletter Section -->
    @include('components.newsletter')
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.js"></script>
    @vite(['resources/js/shop.js'])
@endpush
