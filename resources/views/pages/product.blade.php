@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('shop') }}" class="text-eco-700 hover:text-eco-900 flex items-center">

                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Назад в магазин
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
                <!-- Изображение товара -->
                <div class="aspect-w-4 aspect-h-3">
                    <img src="{{ asset('storage/'.$product->image) }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover rounded-lg">
                </div>

                <!-- Информация о товаре -->
                <div class="space-y-4">
                    <div class="text-sm text-eco-600">
                        {{ $product->category->name }}
                    </div>
                    
                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ $product->name }}
                    </h1>

                    <div class="flex items-center space-x-2">
                        @if($product->is_new)
                        <span class="px-3 py-1 bg-eco-500 text-white text-sm rounded-full">
                            Новинка
                        </span>
                        @endif
                        <span class="px-3 py-1 bg-eco-100 text-eco-800 text-sm rounded-full">
                            {{ $product->eco_feature }}
                        </span>
                    </div>

                    <div class="text-2xl font-bold text-eco-700">
                        {{ number_format($product->price, 0, '.', ' ') }} ₽
                    </div>

                    <p class="text-gray-600">
                        {{ $product->short_description }}
                    </p>

                    <!-- Форма добавления в корзину -->
                    <form action="{{ route('cart.add', $product) }}" method="POST">
                        @csrf
                        <div class="flex items-center space-x-4 mt-6">
                            <div class="flex items-center border border-gray-200 rounded">
                                <button type="button" 
                                        @click="quantity = Math.max(1, quantity - 1)"
                                        class="px-4 py-2 text-gray-600 hover:text-gray-800">
                                    -
                                </button>
                                <input type="number" 
                                       name="quantity" 
                                       x-model="quantity"
                                       value="1" 
                                       min="1"
                                       class="w-16 text-center border-x border-gray-200">
                                <button type="button" 
                                        @click="quantity++"
                                        class="px-4 py-2 text-gray-600 hover:text-gray-800">
                                    +
                                </button>
                            </div>
                            
                            <button type="submit" 
                                    class="bg-eco-600 hover:bg-eco-700 text-white px-6 py-2 rounded-lg flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                В корзину
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Блок экологических преимуществ -->
            <div class="bg-eco-50 p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-eco-100 rounded-full">
                        <svg class="w-6 h-6 text-eco-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Эко-сертифицировано</h3>
                        <p class="text-sm text-gray-600">Соответствует стандартам EcoCert</p>
                    </div>
                </div>
                
                <!-- Добавьте остальные преимущества аналогично -->
            </div>
        </div>

        <!-- Табы с описанием -->
        <div class="my-8">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8">
                    <button @click="activeTab = 'description'" 
                            :class="activeTab === 'description' ? 'border-eco-600 text-eco-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="py-4 px-1 border-b-2 font-medium">
                        Описание
                    </button>
                    <button @click="activeTab = 'specs'" 
                            :class="activeTab === 'specs' ? 'border-eco-600 text-eco-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="py-4 px-1 border-b-2 font-medium">
                        Характеристики
                    </button>
                </nav>
            </div>

            <div x-show="activeTab === 'description'" class="p-6 bg-white prose">
                {!! $product->description !!}
            </div>

            <div x-show="activeTab === 'specs'" class="p-6 bg-white">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($product->specifications as $spec)
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">{{ $spec['name'] }}</span>
                        <span class="text-gray-900">{{ $spec['value'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Похожие товары -->
        <section class="my-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Похожие товары</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $product)
                @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('productPage', () => ({
        activeTab: 'description',
        quantity: 1
    }))
})
</script>
@endpush
