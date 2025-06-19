@extends('layouts.app')

@section('title', $product->name . ' - EcoStore')

@section('content')
<main class="product-page pt-24 pb-16 min-h-screen bg-gradient-to-br from-eco-50 via-eco-100 to-eco-50">
    <div class="container-width mx-auto max-w-7xl px-4 sm:px-6 space-y-8">
        <!-- Навигация -->
        <nav class="mb-8" aria-label="Навигация по сайту">
            <ol class="flex items-center space-x-2 text-xs text-gray-400" itemscope itemtype="https://schema.org/BreadcrumbList">
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a href="{{ route('home') }}" class="hover:underline text-eco-600" itemprop="item">
                        <span itemprop="name">Главная</span>
                    </a>
                    <meta itemprop="position" content="1" />
                </li>
                <li>/</li>
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a href="{{ route('shop') }}" class="hover:underline text-eco-600" itemprop="item">
                        <span itemprop="name">Магазин</span>
                    </a>
                    <meta itemprop="position" content="2" />
                </li>
                @if($product->category)
                    <li>/</li>
                    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a href="{{ route('shop', ['category' => $product->category->slug]) }}" 
                           class="hover:underline text-eco-600" itemprop="item">
                            <span itemprop="name">{{ $product->category->name }}</span>
                        </a>
                        <meta itemprop="position" content="3" />
                    </li>
                @endif
                <li>/</li>
                <li class="text-eco-900" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <!-- Основная информация о продукте -->
        <article class="product-main-card" itemscope itemtype="https://schema.org/Product">
            <div class="product-main-grid grid grid-cols-1 md:grid-cols-2 gap-0">
                <!-- Галерея -->
                <section class="p-6 md:p-8 border-b md:border-b-0 md:border-r border-eco-100" aria-label="Галерея продукта">
                    <div class="product-gallery" role="region" aria-label="Просмотр изображений">
                        <x-product.gallery
                            :product="$product"
                            :images="$product->images"
                            :mainImage="$product->primary_image ? $product->primary_image->url : null" />
                    </div>
                </section>

                <!-- Информация и действия -->
                <section class="p-6 md:p-8 flex flex-col" aria-label="Информация о продукте">
                    <div class="mb-8">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <h1 class="text-2xl font-bold text-eco-900 mb-2" itemprop="name">{{ $product->name }}</h1>
                                    <button id="wishlist-btn"
                                            class="inline-flex items-center justify-center p-2 rounded-full border border-eco-200 bg-white text-eco-600 hover:bg-eco-50/80 transition{{ $product->in_wishlist ? ' bg-eco-50/80 text-yellow-500' : '' }}"
                                            data-product-id="{{ $product->id }}"
                                            title="{{ $product->in_wishlist ? 'Товар в избранном' : 'Добавить в избранное' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="{{ $product->in_wishlist ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3.332.787-4.5 2.05C10.932 3.786 9.36 3 7.5 3A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                                        </svg>
                                    </button>
                                </div>
                                @if($product->category)
                                    <div class="text-sm text-eco-600 mb-4">
                                        <a href="{{ route('shop', ['category' => $product->category->slug]) }}"
                                           class="hover:text-eco-700 transition-colors">
                                            {{ $product->category->name }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="text-right">
                                <div class="product-price text-2xl font-bold text-eco-900" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                    <span itemprop="price">{{ number_format($product->price, 0, '.', ' ') }}</span>
                                    <span itemprop="priceCurrency" class="text-eco-700">₽</span>
                                </div>
                                @if($product->old_price)
                                    <div class="text-sm text-eco-600 line-through">
                                        {{ number_format($product->old_price, 0, '.', ' ') }} ₽
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if($product->short_description)
                            <p class="text-eco-700 mt-4">{{ $product->short_description }}</p>
                        @endif
                    </div>

                    <!-- Действия с продуктом -->
                    <div class="mt-auto" aria-label="Действия с продуктом">
                        <x-product.purchase :product="$product" />
                    </div>
                </section>
            </div>

            <!-- Детальная информация -->
            <section class="border-t border-eco-100" aria-label="Подробная информация">
                <div class="p-6 md:p-8">
                    <x-product.tabs 
                        :product="$product"
                        :productAttributes="$productAttributes"
                        :reviews="$reviews"
                        :ecoFeatures="$ecoFeatures" />
                </div>
            </section>
        </article>

        <!-- Дополнительная информация -->
        <div class="product-info-grid grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Эко-характеристики -->
            <aside class="eco-features" aria-label="Эко-характеристики">
                <x-product.eco-features :ecoFeatures="$ecoFeatures" />
            </aside>

            <!-- Информация о доставке -->
            <aside class="bg-white/80 rounded-2xl border border-eco-100 shadow-inner p-8" aria-label="Информация о доставке">
                <h3 class="text-lg font-semibold mb-4 text-eco-900">Доставка и оплата</h3>
                <ul class="space-y-4" role="list">
                    <li class="group">
                        <div class="flex items-center space-x-4 p-3 rounded-lg hover:bg-eco-50/50 transition-colors duration-300">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-eco-100 text-eco-700 border border-eco-200 group-hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" stroke="currentColor" fill="none" aria-hidden="true">
                                    <circle cx="8" cy="21" r="1"/>
                                    <circle cx="19" cy="21" r="1"/>
                                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                                </svg>
                            </div>
                            <span class="text-eco-800 group-hover:text-eco-900 transition-colors duration-300">Экологичная доставка — низкий углеродный след</span>
                        </div>
                    </li>
                    <li class="group">
                        <div class="flex items-center space-x-4 p-3 rounded-lg hover:bg-eco-50/50 transition-colors duration-300">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-eco-100 text-eco-700 border border-eco-200 group-hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" stroke="currentColor" fill="none" aria-hidden="true">
                                    <rect width="20" height="14" x="2" y="5" rx="2"/>
                                    <line x1="2" x2="22" y1="10" y2="10"/>
                                </svg>
                            </div>
                            <span class="text-eco-800 group-hover:text-eco-900 transition-colors duration-300">Оплата — карта, рассрочка, при получении</span>
                        </div>
                    </li>
                    <li class="group">
                        <div class="flex items-center space-x-4 p-3 rounded-lg hover:bg-eco-50/50 transition-colors duration-300">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-eco-100 text-eco-700 border border-eco-200 group-hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" stroke="currentColor" fill="none" aria-hidden="true">
                                    <path d="M14 19a6 6 0 0 0-12 0"/>
                                    <circle cx="8" cy="9" r="4"/>
                                    <line x1="19" x2="19" y1="8" y2="14"/>
                                    <line x1="22" x2="16" y1="11" y2="11"/>
                                </svg>
                            </div>
                            <span class="text-eco-800 group-hover:text-eco-900 transition-colors duration-300">Эко-бонусы — увеличьте свой рейтинг за покупки</span>
                        </div>
                    </li>
                </ul>
            </aside>
        </div>

        <!-- Похожие товары -->
        <section aria-label="Похожие товары">
            <x-product.related :relatedProducts="$relatedProducts" />
        </section>
    </div>
</main>

@push('scripts')
    <script type="module">
        // Дожидаемся загрузки всех модулей через Vite
        document.addEventListener('DOMContentLoaded', function () {
            // Даем время на инициализацию всех модулей
            setTimeout(() => {
                if (typeof window.EcoStoreProductPage === 'undefined') {
                    console.error('Product page script not loaded properly');
                    return;
                }
                window.EcoStoreProductPage.init(@json($product->name), @json($product->id));
            }, 100);
        });
    </script>
@endpush
@endsection
<script src="{{ asset('js/wishlist.js') }}"></script>