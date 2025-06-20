@extends('layouts.app')

@section('title', 'Избранное - EcoStore')

@section('content')
<!-- Header spacing -->
<div class="h-20"></div>

<div class="bg-gradient-to-b from-eco-50 to-white py-12 min-h-screen">
    <div class="container mx-auto px-4">
        <!-- Page header -->
        <div class="mb-10 max-w-3xl mx-auto text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-eco-900 mb-3">Избранное</h1>
            <p class="text-eco-600 text-lg max-w-2xl mx-auto">Добавляйте понравившиеся товары в избранное, чтобы не потерять их</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 max-w-7xl mx-auto">
            <!-- Sidebar navigation -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden sticky top-24">
                    @include('account.partials.sidebar')
                </div>
            </div>

            <!-- Основной контент -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-sm border border-eco-100 overflow-hidden">
                    <div class="border-b border-eco-100 bg-gradient-to-r from-eco-50/70 to-white px-6 py-4">
                        <h2 class="text-xl font-semibold text-eco-900">Ваши избранные товары</h2>
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
                                    <div class="relative w-full h-80 rounded-lg overflow-hidden bg-eco-50">
                                        <a href="{{ route('product.show', $product->sku) }}" class="block">
                                            <img src="{{ $product->image }}"
                                                 alt="{{ $product->name }}"
                                                 class="absolute inset-0 w-full h-full object-center object-cover group-hover:opacity-90 transition-opacity">
                                        </a>
                                    </div>
                                    <div class="mt-4">
                                        <div class="flex items-center justify-between gap-2">
                                            <a href="{{ route('product.show', $product->sku) }}" class="block flex-1">
                                                <h3 class="text-sm font-medium text-eco-900 mb-0">{{ $product->name }}</h3>
                                            </a>
                                            <button type="button"
                                                    id="wishlist-btn"
                                                    data-product-id="{{ $product->id }}"
                                                    class="border border-eco-200 text-eco-800 hover:bg-eco-50/80 rounded-full p-3 transition-all duration-300 hover:shadow-md hover:shadow-eco-100/20 hover:border-eco-300 group bg-eco-50/80 text-yellow-500"
                                                    title="Удалить из избранного">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" stroke="currentColor">
                                                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3.332.787-4.5 2.05C10.932 3.786 9.36 3 7.5 3A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="mt-1 text-lg font-semibold text-eco-600">{{ number_format($product->price, 0, '.', ' ') }} ₽</p>
                                    </div>
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
<script src="{{ mix('js/components/product/wishlist.js') }}" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('#wishlist-btn').forEach(button => {
            const productId = button.dataset.productId;
            initWishlist(productId);
        });
    });
</script>
@endpush