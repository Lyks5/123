@extends('layouts.app')

@section('title', $product->name . ' - EcoSport')

@section('content')
    <!-- Product Details Section -->
    <section class="pt-24 pb-16">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <div class="mb-6">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('home') }}" class="text-sm text-eco-600 hover:text-eco-900">
                                Главная
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <a href="{{ route('shop') }}" class="text-sm text-eco-600 hover:text-eco-900 ml-1 md:ml-2">
                                    Магазин
                                </a>
                            </div>
                        </li>
                        @if($product->categories->isNotEmpty())
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <a href="{{ route('shop', ['category' => $product->categories->first()->slug]) }}" class="text-sm text-eco-600 hover:text-eco-900 ml-1 md:ml-2">
                                    {{ $product->categories->first()->name }}
                                </a>
                            </div>
                        </li>
                        @endif
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="text-sm text-gray-500 ml-1 md:ml-2">{{ $product->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Product Info -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
                    <!-- Product Images -->
                    <div>
                        <div class="aspect-[4/3] rounded-xl overflow-hidden bg-eco-50 mb-4">
                            @if($product->primary_image)
                                <img 
                                    src="{{ asset('storage/' . $product->primary_image->image_path) }}" 
                                    alt="{{ $product->primary_image->alt_text }}" 
                                    class="w-full h-full object-cover"
                                    id="main-product-image"
                                />
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-eco-50 text-eco-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        @if($product->images->count() > 1)
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($product->images as $image)
                            <div class="aspect-square rounded-lg overflow-hidden bg-eco-50">
                                <img 
                                    src="{{ asset('storage/' . $image->image_path) }}" 
                                    alt="{{ $image->alt_text }}" 
                                    class="w-full h-full object-cover cursor-pointer hover:opacity-80 transition-opacity product-thumbnail"
                                    data-image="{{ asset('storage/' . $image->image_path) }}"
                                    onclick="updateMainImage(this)"
                                />
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    
                    <!-- Product Details -->
                    <div class="flex flex-col">
                        <div>
                            @if($product->categories->isNotEmpty())
                                <div class="text-sm text-eco-600 mb-2">{{ $product->categories->first()->name }}</div>
                            @endif
                            <h1 class="text-2xl md:text-3xl font-bold text-eco-900 mb-2">
                                {{ $product->name }}
                            </h1>
                            <div class="flex flex-wrap items-center gap-2 mb-4">
                                @if($ecoFeatures->isNotEmpty())
                                    @foreach($ecoFeatures->take(2) as $ecoFeature)
                                        <div class="px-2 py-1 bg-eco-100 text-eco-800 text-xs rounded-full flex items-center">
                                            @if($ecoFeature->icon)
                                                <span class="mr-1">{!! $ecoFeature->icon !!}</span>
                                            @endif
                                            {{ $ecoFeature->name }}
                                        </div>
                                    @endforeach
                                @endif
                                @if($product->is_new)
                                    <div class="px-2 py-1 bg-eco-500 text-white text-xs rounded-full">
                                        Новинка
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        <div class="flex items-center gap-2">
                                            <span class="text-xl md:text-2xl font-bold text-eco-900">
                                                {{ number_format($product->sale_price, 0, ',', ' ') }} ₽
                                            </span>
                                            <span class="text-lg text-eco-500 line-through">
                                                {{ number_format($product->price, 0, ',', ' ') }} ₽
                                            </span>
                                            <span class="px-2 py-1 bg-red-100 text-red-600 text-xs font-medium rounded-full">
                                                -{{ round((1 - $product->sale_price / $product->price) * 100) }}%
                                            </span>
                                        </div>
                                    @else
                                        <div class="text-xl md:text-2xl font-bold text-eco-900">
                                            {{ number_format($product->price, 0, ',', ' ') }} ₽
                                        </div>
                                    @endif
                                </div>
                                <div class="text-sm {{ $product->stock_quantity > 0 ? 'text-green-600' : 'text-red-500' }}">
                                    @if($product->stock_quantity > 10)
                                        В наличии
                                    @elseif($product->stock_quantity > 0)
                                        Осталось: {{ $product->stock_quantity }} шт.
                                    @else
                                        Нет в наличии
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Product Attributes (Color, Size, etc) -->
                            @if(isset($productAttributes) && count($productAttributes) > 0)
                                <div class="mb-6 space-y-4">
                                    @foreach($productAttributes as $group => $attributes)
                                        @foreach($attributes as $attribute)
                                            <div>
                                                <h3 class="text-sm font-medium text-eco-800 mb-2">{{ $attribute->name }}:</h3>
                                                
                                                @if($group == 'color' && isset($attribute->values))
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($attribute->values as $value)
                                                            <div 
                                                                class="w-8 h-8 rounded-full cursor-pointer border-2 border-transparent hover:border-eco-500 transition-all"
                                                                style="background-color: {{ $value->hex_color }};"
                                                                title="{{ $value->value }}"
                                                            ></div>
                                                        @endforeach
                                                    </div>
                                                @elseif(isset($attribute->values))
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($attribute->values as $value)
                                                            <div class="px-3 py-1 border border-eco-200 rounded-lg text-sm cursor-pointer hover:bg-eco-50 transition-colors">
                                                                {{ $value->value }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif(isset($attribute->value))
                                                    @if($group == 'color' && isset($attribute->hex_color))
                                                        <div class="flex items-center">
                                                            <div 
                                                                class="w-6 h-6 rounded-full mr-2"
                                                                style="background-color: {{ $attribute->hex_color }};"
                                                            ></div>
                                                            <span>{{ $attribute->value }}</span>
                                                        </div>
                                                    @else
                                                        <div>{{ $attribute->value }}</div>
                                                    @endif
                                                @endif
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            @endif
                            
                            <div class="prose text-eco-700 mb-6">
                                <p>{{ $product->short_description ?? Str::limit($product->description, 200) }}</p>
                            </div>
                        </div>
                        
                        <!-- Add to cart section -->
                        <div class="mt-auto">
                            <form action="{{ route('cart.add') }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div class="flex items-center space-x-4">
                                    <div class="text-eco-800 font-medium">Количество:</div>
                                    <div class="flex items-center border border-eco-200 rounded-lg">
                                        <button 
                                            type="button"
                                            onclick="decrementQuantity()"
                                            class="py-2 px-4 text-eco-700 hover:text-eco-900 transition-colors"
                                        >
                                            -
                                        </button>
                                        <input
                                            type="number"
                                            name="quantity"
                                            id="quantity"
                                            value="1"
                                            min="1"
                                            class="py-2 px-4 text-eco-900 border-x border-eco-200 w-16 text-center appearance-none"
                                            readonly
                                        >
                                        <button 
                                            type="button"
                                            onclick="incrementQuantity()"
                                            class="py-2 px-4 text-eco-700 hover:text-eco-900 transition-colors"
                                        >
                                            +
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <button 
                                        type="submit"
                                        class="flex-1 bg-eco-600 hover:bg-eco-700 text-white py-3 px-6 rounded-full transition-colors flex items-center justify-center"
                                        @if($product->stock_quantity <= 0) disabled @endif
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                                        @if($product->stock_quantity > 0)
                                            Добавить в корзину
                                        @else
                                            Нет в наличии
                                        @endif
                                    </button>
                                    <button 
                                        type="button"
                                        onclick="addToWishlist({{ $product->id }})"
                                        class="flex-1 border border-eco-200 text-eco-800 hover:bg-eco-50 py-3 px-6 rounded-full transition-colors flex items-center justify-center"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3.332.787-4.5 2.05C10.932 3.786 9.36 3 7.5 3A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                                        В избранное
                                    </button>
                                </div>
                            </form>
                            
                            <div class="mt-6 flex items-center justify-between">
                                <button 
                                    type="button"
                                    onclick="shareProduct()"
                                    class="text-eco-700 hover:text-eco-900 hover:bg-eco-50 py-2 px-4 rounded-lg transition-colors flex items-center"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" x2="12" y1="2" y2="15"/></svg>
                                    Поделиться
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Eco Features and Delivery Info -->
                <div class="px-6 py-4 bg-eco-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Eco Features -->
                        <div>
                            <h3 class="text-lg font-medium text-eco-900 mb-3">Экологические характеристики</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @if($ecoFeatures->isNotEmpty())
                                    @foreach($ecoFeatures as $ecoFeature)
                                        <div class="bg-white p-4 rounded-xl flex items-start">
                                            <div class="flex-shrink-0 h-10 w-10 bg-eco-100 rounded-lg flex items-center justify-center text-eco-600 mr-3">
                                                @if($ecoFeature->icon)
                                                    {!! $ecoFeature->icon !!}
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M12 6v6l4 2"></path>
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-eco-900">{{ $ecoFeature->name }}</div>
                                                <div class="text-xs text-eco-700">
                                                    @if($ecoFeature->value)
                                                        {{ $ecoFeature->value }}
                                                    @else
                                                        {{ Str::limit($ecoFeature->description, 70) }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-span-2 bg-white p-4 rounded-xl">
                                        <p class="text-eco-600 text-sm">Информация об экологических характеристиках не представлена.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Delivery Info -->
                        <div>
                            <h3 class="text-lg font-medium text-eco-900 mb-3">Доставка и оплата</h3>
                            <div class="space-y-3">
                                <div class="bg-white p-4 rounded-xl flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-eco-100 rounded-lg flex items-center justify-center text-eco-600 mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="8" cy="21" r="1"/>
                                            <circle cx="19" cy="21" r="1"/>
                                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-eco-900">Экологичная доставка</div>
                                        <div class="text-xs text-eco-700">С минимальным углеродным следом</div>
                                    </div>
                                </div>
                                <div class="bg-white p-4 rounded-xl flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-eco-100 rounded-lg flex items-center justify-center text-eco-600 mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect width="20" height="14" x="2" y="5" rx="2"/>
                                            <line x1="2" x2="22" y1="10" y2="10"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-eco-900">Удобная оплата</div>
                                        <div class="text-xs text-eco-700">Картой онлайн, при получении или в рассрочку</div>
                                    </div>
                                </div>
                                <div class="bg-white p-4 rounded-xl flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-eco-100 rounded-lg flex items-center justify-center text-eco-600 mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 19a6 6 0 0 0-12 0"/>
                                            <circle cx="8" cy="9" r="4"/>
                                            <line x1="19" x2="19" y1="8" y2="14"/>
                                            <line x1="22" x2="16" y1="11" y2="11"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-eco-900">Бонусы за покупку</div>
                                        <div class="text-xs text-eco-700">Увеличьте свой эко-рейтинг при каждой покупке</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product Details Tabs -->
            <div class="mt-12">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="border-b border-eco-100">
                        <nav class="flex" role="tablist">
                            <button 
                                id="tab-description" 
                                class="text-eco-900 px-6 py-4 font-medium border-b-2 border-eco-600"
                                role="tab"
                                aria-selected="true"
                                onclick="openTab('description')"
                            >
                                Описание
                            </button>
                            <button 
                                id="tab-specifications" 
                                class="text-eco-600 hover:text-eco-900 px-6 py-4 font-medium border-b-2 border-transparent"
                                role="tab"
                                aria-selected="false"
                                onclick="openTab('specifications')"
                            >
                                Характеристики
                            </button>
                            <button 
                                id="tab-reviews" 
                                class="text-eco-600 hover:text-eco-900 px-6 py-4 font-medium border-b-2 border-transparent"
                                role="tab"
                                aria-selected="false"
                                onclick="openTab('reviews')"
                            >
                                Отзывы
                            </button>
                        </nav>
                    </div>
                    
                    <div class="p-6">
                        <!-- Description Tab -->
                        <div id="content-description" class="tab-content block" role="tabpanel">
                            <div class="prose text-eco-800 max-w-none">
                                <h3 class="text-xl font-semibold text-eco-900 mb-4">О продукте</h3>
                                <div>
                                    {!! $product->description !!}
                                </div>
                                
                                @if($ecoFeatures->isNotEmpty())
                                <div class="mt-8">
                                    <h4 class="text-lg font-semibold text-eco-900 mt-6 mb-3">Преимущества экологичных материалов</h4>
                                    <ul class="space-y-2">
                                        @foreach($ecoFeatures as $feature)
                                            <li class="flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-eco-600 mt-0.5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="9 11 12 14 22 4"></polyline>
                                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                                </svg>
                                                <span>{{ $feature->description }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Specifications Tab -->
                        <div id="content-specifications" class="tab-content hidden" role="tabpanel">
                            <div class="prose text-eco-800 max-w-none">
                                <h3 class="text-xl font-semibold text-eco-900 mb-4">Технические характеристики</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                                    <!-- Product Attributes -->
                                    @if(isset($productAttributes) && count($productAttributes) > 0)
                                        @foreach($productAttributes as $group => $attributes)
                                            <div class="col-span-1">
                                                <h4 class="text-lg font-medium text-eco-900 mb-3 capitalize">{{ $group }}</h4>
                                                <div class="space-y-2">
                                                    @foreach($attributes as $attribute)
                                                        <div class="flex justify-between py-2 border-b border-eco-100">
                                                            <span class="text-eco-700">{{ $attribute->name }}:</span>
                                                            @if(isset($attribute->values))
                                                                <span class="text-eco-900 font-medium">
                                                                    @if($group == 'color')
                                                                        <div class="flex items-center gap-1">
                                                                            @foreach($attribute->values as $value)
                                                                                <div class="w-4 h-4 rounded-full" style="background-color: {{ $value->hex_color }};" title="{{ $value->value }}"></div>
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        {{ $attribute->values->pluck('value')->implode(', ') }}
                                                                    @endif
                                                                </span>
                                                            @elseif(isset($attribute->value))
                                                                <span class="text-eco-900 font-medium">
                                                                    @if($group == 'color' && isset($attribute->hex_color))
                                                                        <div class="flex items-center">
                                                                            <div class="w-4 h-4 rounded-full mr-1" style="background-color: {{ $attribute->hex_color }};"></div>
                                                                            {{ $attribute->value }}
                                                                        </div>
                                                                    @else
                                                                        {{ $attribute->value }}
                                                                    @endif
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-span-2">
                                            <p class="text-eco-600">Характеристики не указаны для данного товара.</p>
                                        </div>
                                    @endif
                                    
                                    <!-- Basic Product Info -->
                                    <div class="col-span-1">
                                        <h4 class="text-lg font-medium text-eco-900 mb-3">Основная информация</h4>
                                        <div class="space-y-2">
                                            <div class="flex justify-between py-2 border-b border-eco-100">
                                                <span class="text-eco-700">Артикул:</span>
                                                <span class="text-eco-900 font-medium">{{ $product->sku }}</span>
                                            </div>
                                            @if($product->categories->isNotEmpty())
                                                <div class="flex justify-between py-2 border-b border-eco-100">
                                                    <span class="text-eco-700">Категория:</span>
                                                    <span class="text-eco-900 font-medium">
                                                        {{ $product->categories->pluck('name')->implode(', ') }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="flex justify-between py-2 border-b border-eco-100">
                                                <span class="text-eco-700">Наличие:</span>
                                                <span class="text-eco-900 font-medium {{ $product->stock_quantity > 0 ? 'text-green-600' : 'text-red-500' }}">
                                                    @if($product->stock_quantity > 10)
                                                        В наличии
                                                    @elseif($product->stock_quantity > 0)
                                                        Осталось: {{ $product->stock_quantity }} шт.
                                                    @else
                                                        Нет в наличии
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Reviews Tab -->
                        <div id="content-reviews" class="tab-content hidden" role="tabpanel">
                            <div class="prose text-eco-800 max-w-none">
                                <h3 class="text-xl font-semibold text-eco-900 mb-4">Отзывы клиентов</h3>
                                
                                @if($reviews->count() > 0)
                                    <div class="space-y-6">
                                        @foreach($reviews as $review)
                                        <div class="border-b border-eco-100 pb-6 last:border-b-0">
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex items-center">
                                                    <div class="mr-3">
                                                        <div class="w-10 h-10 bg-eco-100 rounded-full flex items-center justify-center text-eco-700 font-medium">
                                                            {{ substr($review->user->name ?? 'A', 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="font-medium text-eco-900">{{ $review->user->name ?? 'Анонимный пользователь' }}</div>
                                                        <div class="text-sm text-eco-600">{{ $review->created_at->format('d.m.Y') }}</div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-4">
                                                    <div class="flex items-center">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $review->rating)
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="text-yellow-400" viewBox="0 0 16 16">
                                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                                                </svg>
                                                            @else
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="text-gray-300" viewBox="0 0 16 16">
                                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                                                </svg>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    @if(auth()->check() && auth()->id() === $review->user_id)
                                                        <button 
                                                            type="button" 
                                                            class="text-eco-600 hover:text-eco-900 text-sm font-medium"
                                                            onclick="toggleEditForm({{ $review->id }})"
                                                        >
                                                            Редактировать
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                            <h4 class="text-eco-900 font-medium mb-2">{{ $review->title }}</h4>
                                            <p class="text-eco-700">{{ $review->comment }}</p>

                                            @if(auth()->check() && auth()->id() === $review->user_id)
                                            <form 
                                                id="edit-review-form-{{ $review->id }}" 
                                                action="{{ route('product.review.update', $product->slug) }}" 
                                                method="post" 
                                                class="mt-4 hidden bg-eco-50 p-4 rounded-lg"
                                            >
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="review_id" value="{{ $review->id }}">
                                                <div class="mb-4">
                                                    <label for="rating-{{ $review->id }}" class="block text-sm font-medium text-eco-700 mb-1">Оценка</label>
                                                    <div class="flex items-center space-x-1">
                                                        @for($i = 1; $i <= 5; $i++)
                                                        <button 
                                                            type="button" 
                                                            onclick="setRatingEdit({{ $review->id }}, {{ $i }})"
                                                            class="rating-star-edit-{{ $review->id }} text-gray-300 hover:text-yellow-400 focus:outline-none"
                                                            data-rating="{{ $i }}"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                                            </svg>
                                                        </button>
                                                        @endfor
                                                        <input type="hidden" id="rating-edit-{{ $review->id }}" name="rating" value="{{ $review->rating }}">
                                                    </div>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="title-{{ $review->id }}" class="block text-sm font-medium text-eco-700 mb-1">Заголовок отзыва</label>
                                                    <input 
                                                        type="text" 
                                                        id="title-{{ $review->id }}" 
                                                        name="title" 
                                                        required 
                                                        class="w-full px-4 py-2 border border-eco-200 rounded-lg focus:ring-eco-500 focus:border-eco-500"
                                                        placeholder="Кратко опишите ваши впечатления"
                                                        value="{{ $review->title }}"
                                                    >
                                                </div>
                                                <div class="mb-4">
                                                    <label for="comment-{{ $review->id }}" class="block text-sm font-medium text-eco-700 mb-1">Ваш отзыв</label>
                                                    <textarea 
                                                        id="comment-{{ $review->id }}" 
                                                        name="comment" 
                                                        rows="4" 
                                                        required 
                                                        class="w-full px-4 py-2 border border-eco-200 rounded-lg focus:ring-eco-500 focus:border-eco-500"
                                                        placeholder="Поделитесь своим опытом использования товара"
                                                    >{{ $review->comment }}</textarea>
                                                </div>
                                                <button 
                                                    type="submit" 
                                                    class="bg-eco-600 hover:bg-eco-700 text-white font-medium py-2 px-6 rounded-lg transition-colors"
                                                >
                                                    Обновить отзыв
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                    
                                    <div class="mt-6">
                                        {{ $reviews->links() }}
                                    </div>
                                @else
                                    <p class="text-eco-700 mb-4">
                                        Отзывы пока отсутствуют. Будьте первым, кто оставит отзыв об этом товаре!
                                    </p>
                                @endif
                                
                                <!-- Review Form -->
                                @if(auth()->check() && !$reviews->where('user_id', auth()->id())->count())
                                <div class="mt-8 bg-eco-50 p-6 rounded-xl">
                                    <h4 class="text-lg font-semibold text-eco-900 mb-4">Оставить отзыв</h4>
                                    <form action="{{ route('product.review.submit', $product->slug) }}" method="post">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="rating" class="block text-sm font-medium text-eco-700 mb-1">Оценка</label>
                                            <div class="flex items-center space-x-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                <button 
                                                    type="button" 
                                                    onclick="setRating({{ $i }})"
                                                    class="rating-star text-gray-300 hover:text-yellow-400 focus:outline-none"
                                                    data-rating="{{ $i }}"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                                    </svg>
                                                </button>
                                                @endfor
                                                <input type="hidden" id="rating" name="rating" value="5">
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label for="title" class="block text-sm font-medium text-eco-700 mb-1">Заголовок отзыва</label>
                                            <input 
                                                type="text" 
                                                id="title" 
                                                name="title" 
                                                required 
                                                class="w-full px-4 py-2 border border-eco-200 rounded-lg focus:ring-eco-500 focus:border-eco-500"
                                                placeholder="Кратко опишите ваши впечатления"
                                            >
                                        </div>
                                        <div class="mb-4">
                                            <label for="comment" class="block text-sm font-medium text-eco-700 mb-1">Ваш отзыв</label>
                                            <textarea 
                                                id="comment" 
                                                name="comment" 
                                                rows="4" 
                                                required 
                                                class="w-full px-4 py-2 border border-eco-200 rounded-lg focus:ring-eco-500 focus:border-eco-500"
                                                placeholder="Поделитесь своим опытом использования товара"
                                            ></textarea>
                                        </div>
                                        <button 
                                            type="submit" 
                                            class="bg-eco-600 hover:bg-eco-700 text-white font-medium py-2 px-6 rounded-lg transition-colors"
                                        >
                                            Отправить отзыв
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Related Products -->
            <div class="my-12">
                <h2 class="text-2xl font-bold text-eco-900 mb-6">Похожие товары</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        @include('components.product-card', ['product' => $relatedProduct])
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    
    <script>
        function openTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('[role="tab"]').forEach(tab => {
                tab.classList.remove('border-eco-600', 'text-eco-900');
                tab.classList.add('border-transparent', 'text-eco-600');
                tab.setAttribute('aria-selected', 'false');
            });
            
            // Show the selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Add active class to the selected tab
            const selectedTab = document.getElementById('tab-' + tabName);
            selectedTab.classList.remove('border-transparent', 'text-eco-600');
            selectedTab.classList.add('border-eco-600', 'text-eco-900');
            selectedTab.setAttribute('aria-selected', 'true');
        }
        
        function incrementQuantity() {
            const quantityInput = document.getElementById('quantity');
            quantityInput.value = parseInt(quantityInput.value) + 1;
        }
        
        function decrementQuantity() {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        }
        
        function setRating(rating) {
            document.getElementById('rating').value = rating;
            
            document.querySelectorAll('.rating-star').forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }
        
        function addToWishlist(productId) {
            fetch('/wishlist/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Товар добавлен в избранное');
                } else {
                    alert(data.message || 'Ошибка при добавлении товара в избранное');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка при добавлении товара в избранное');
            });
        }
        
        function shareProduct() {
            if (navigator.share) {
                navigator.share({
                    title: "{{ $product->name }}",
                    text: "Посмотрите этот товар: {{ $product->name }}",
                    url: window.location.href,
                })
                .catch(error => console.log('Ошибка при попытке поделиться', error));
            } else {
                alert('Функция "Поделиться" недоступна в вашем браузере');
            }
        }
        
        function updateMainImage(thumbnailImg) {
            const mainImage = document.getElementById('main-product-image');
            mainImage.src = thumbnailImg.getAttribute('data-image');
        }
        
        // Initialize the first tab
        document.addEventListener('DOMContentLoaded', function() {
            openTab('description');
            @if(isset($review))
                setRating({{ $review->rating }});
            @else
                setRating(5);
            @endif
        });

        function toggleEditForm(reviewId) {
            const form = document.getElementById('edit-review-form-' + reviewId);
            if (form) {
                form.classList.toggle('hidden');
            }
        }

        function setRatingEdit(reviewId, rating) {
            document.getElementById('rating-edit-' + reviewId).value = rating;

            document.querySelectorAll('.rating-star-edit-' + reviewId).forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }
    </script>
@endsection