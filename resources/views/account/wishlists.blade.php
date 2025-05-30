@extends('layouts.app')

@section('title', 'Избранное - EcoStore')

@section('content')
<div class="container-width mx-auto max-w-7xl px-4 sm:px-6 py-8">
    <div class="grid grid-cols-12 gap-8">
        <!-- Боковое меню -->
        <div class="col-span-12 lg:col-span-3">
            @include('account.partials.sidebar')
        </div>

        <!-- Основной контент -->
        <div class="col-span-12 lg:col-span-9">
            <div class="bg-white rounded-2xl shadow-sm border border-eco-100 overflow-hidden">
                <div class="border-b border-eco-100 bg-gradient-to-r from-eco-50/70 to-white px-6 py-4">
                    <h1 class="text-xl font-semibold text-eco-900">Избранное</h1>
                </div>

                <div class="p-6">
                    @if($products->isEmpty())
                        <div class="text-center py-12">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-eco-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-eco-600" fill="none" stroke="currentColor">
                                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3.332.787-4.5 2.05C10.932 3.786 9.36 3 7.5 3A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-eco-900 mb-2">В избранном пока ничего нет</h3>
                            <p class="text-eco-600 mb-6">Добавляйте понравившиеся товары в избранное, чтобы не потерять их</p>
                            <a href="{{ route('shop') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-full text-white bg-gradient-to-r from-eco-600 to-eco-500 hover:from-eco-700 hover:to-eco-600 transition-all duration-300">
                                Перейти в магазин
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($products as $product)
                                <div class="relative group">
                                    <a href="{{ route('product.show', $product->sku) }}" class="block">
                                        <div class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden bg-eco-50">
                                            <img src="{{ $product->primary_image ? asset($product->primary_image->url) : asset('images/placeholder.png') }}" 
                                                 alt="{{ $product->name }}"
                                                 class="w-full h-full object-center object-cover group-hover:opacity-90 transition-opacity">
                                        </div>
                                        <div class="mt-4">
                                            <h3 class="text-sm font-medium text-eco-900">{{ $product->name }}</h3>
                                            <p class="mt-1 text-lg font-semibold text-eco-600">{{ number_format($product->price, 0, '.', ' ') }} ₽</p>
                                        </div>
                                    </a>
                                    <button type="button"
                                            class="wishlist-remove-btn absolute top-2 right-2 p-2 bg-white rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                            data-product-id="{{ $product->id }}"
                                            title="Удалить из избранного">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-eco-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3.332.787-4.5 2.05C10.932 3.786 9.36 3 7.5 3A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ mix('js/wishlist.js') }}" defer></script>
@endpush