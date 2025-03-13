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
                        <!-- Categories -->
                        <div class="bg-eco-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-eco-900 mb-4">Категории</h3>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="category-all" name="category[]" value="all" class="rounded border-eco-300 text-eco-600 focus:ring-eco-500">
                                    <label for="category-all" class="ml-2 text-eco-800">Все категории</label>
                                </div>
                                
                                @foreach($categories as $category)
                                <div class="flex items-center">
                                    <input type="checkbox" id="category-{{ $category->id }}" name="category[]" value="{{ $category->id }}" class="rounded border-eco-300 text-eco-600 focus:ring-eco-500">
                                    <label for="category-{{ $category->id }}" class="ml-2 text-eco-800">{{ $category->name }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="bg-eco-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-eco-900 mb-4">Цена</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <input type="number" placeholder="От" class="w-[45%] rounded-lg border-eco-300 focus:border-eco-500 focus:ring-eco-500">
                                    <span class="text-eco-800">—</span>
                                    <input type="number" placeholder="До" class="w-[45%] rounded-lg border-eco-300 focus:border-eco-500 focus:ring-eco-500">
                                </div>
                                <button class="w-full py-2 bg-eco-600 hover:bg-eco-700 text-white rounded-lg transition-colors">
                                    Применить
                                </button>
                            </div>
                        </div>
                        
                        <!-- Eco Features -->
                        <div class="bg-eco-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-eco-900 mb-4">Эко-особенности</h3>
                            <div class="space-y-2">
                                @foreach($ecoFeatures as $feature)
                                <div class="flex items-center">
                                    <input type="checkbox" id="feature-{{ $feature->id }}" name="features[]" value="{{ $feature->id }}" class="rounded border-eco-300 text-eco-600 focus:ring-eco-500">
                                    <label for="feature-{{ $feature->id }}" class="ml-2 text-eco-800">{{ $feature->name }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Clear Filters -->
                        <button class="w-full py-2 border border-eco-300 hover:bg-eco-50 text-eco-800 rounded-lg transition-colors">
                            Очистить фильтры
                        </button>
                    </div>
                </div>
                
                <!-- Product Grid -->
                <div class="lg:col-span-3">
                    <!-- Sort Options -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 pb-4 border-b border-eco-100">
                        <div class="mb-4 sm:mb-0">
                            <span class="text-eco-800">Показано {{ $products->firstItem() }} - {{ $products->lastItem() }} из {{ $products->total() }} товаров</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-eco-800 mr-2">Сортировать по:</span>
                            <select class="rounded-lg border-eco-300 focus:border-eco-500 focus:ring-eco-500">
                                <option>Популярности</option>
                                <option>Новизне</option>
                                <option>Цене: низкая-высокая</option>
                                <option>Цене: высокая-низкая</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Products -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            @include('components.product-card', ['product' => $product])
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Eco Benefits Section -->
    <section class="section-padding bg-eco-50">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                    Наши преимущества
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                    Почему стоит выбрать экологичное снаряжение
                </h2>
                <p class="text-eco-700">
                    Наши продукты не только помогают вам достичь лучших результатов, но и вносят вклад в сохранение окружающей среды.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Benefit 1 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm">
                    <div class="w-16 h-16 bg-eco-100 rounded-full flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-eco-700" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 1 0 10 10H12V2Z"></path><path d="M12 2a10 10 0 0 1 10 10"></path><circle cx="12" cy="12" r="2"></circle></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-eco-900 mb-4">Снижение углеродного следа</h3>
                    <p class="text-eco-700">
                        Наши продукты производятся с использованием возобновляемых источников энергии и оптимизированных производственных процессов.
                    </p>
                </div>
                
                <!-- Benefit 2 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm">
                    <div class="w-16 h-16 bg-eco-100 rounded-full flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-eco-700" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-eco-900 mb-4">Переработанные материалы</h3>
                    <p class="text-eco-700">
                        Мы используем переработанный пластик, органический хлопок и другие экологичные материалы, чтобы уменьшить потребление новых ресурсов.
                    </p>
                </div>
                
                <!-- Benefit 3 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm">
                    <div class="w-16 h-16 bg-eco-100 rounded-full flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-eco-700" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 18.5h-19"></path><path d="M12 18.5V5.7"></path><path d="M7 11.5 12 7l5 4.5"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-eco-900 mb-4">Справедливая торговля</h3>
                    <p class="text-eco-700">
                        Мы поддерживаем справедливые условия труда для всех работников в нашей цепочке поставок и сотрудничаем с поставщиками, разделяющими наши ценности.
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Newsletter Section -->
    @include('components.newsletter')
@endsection
