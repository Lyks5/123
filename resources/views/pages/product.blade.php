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
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                            @if($product->category)
                                <a href="{{ route('shop', ['category' => $product->category]) }}" class="text-sm text-eco-600 hover:text-eco-900 ml-1 md:ml-2">
                                {{ $product->category ? $product->category->name : 'Без категории' }}
                                </a>
                            @else
                                <span class="text-sm text-gray-500">Без категории</span>
                            @endif
                            </div>
                        </li>
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
                            <img 
                                src="{{ $product->image }}" 
                                alt="{{ $product->name }}" 
                                class="w-full h-full object-cover"
                            />
                        </div>
                        
                        @if($product->images->count() > 0)
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($product->images->take(4) as $image)
                            <div class="aspect-square rounded-lg overflow-hidden bg-eco-50">
                                <img 
                                    src="{{ asset('storage/' . $image->path) }}" 
                                    alt="{{ $product->name }}" 
                                    class="w-full h-full object-cover cursor-pointer hover:opacity-80 transition-opacity"
                                />
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    
                    <!-- Product Details -->
                    <div class="flex flex-col">
                        <div>
                            <div class="text-sm text-eco-600 mb-2">{{ $product->category->name }}</div>
                            <h1 class="text-2xl md:text-3xl font-bold text-eco-900 mb-2">
                                {{ $product->name }}
                            </h1>
                            <div class="flex items-center space-x-2 mb-4">
                                <div class="px-2 py-1 bg-eco-100 text-eco-800 text-xs rounded-full">
                                    {{ $product->eco_feature->name }}
                                </div>
                                @if($product->is_new)
                                    <div class="px-2 py-1 bg-eco-500 text-white text-xs rounded-full">
                                        Новинка
                                    </div>
                                @endif
                            </div>
                            <div class="text-xl md:text-2xl font-bold text-eco-900 mb-6">
                                {{ number_format($product->price, 2, ',', ' ') }} ₽
                            </div>
                            
                            <div class="prose text-eco-700 mb-6">
                                <p>{{ $product->description }}</p>
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
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                                        Добавить в корзину
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
                </div>
                
                <!-- Delivery info -->
                <div class="px-6 py-4 bg-eco-50 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-eco-700 mr-3"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M10 4h4"/><path d="M4 10h16"/><path d="M8 18V4h8v14"/><path d="M18 18H2V8l4-4h12l4 4v10h-4"/></svg>
                        <div>
                            <div class="text-sm font-medium text-eco-900">Экологичная доставка</div>
                            <div class="text-xs text-eco-700">С минимальным углеродным следом</div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-eco-700 mr-3"><path d="M4 14v6a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-6"/><path d="M9 16v3h6v-3"/><path d="M12 2v11.3m-7-1L12 9l7 3.3"/></svg>
                        <div>
                            <div class="text-sm font-medium text-eco-900">Экологичная упаковка</div>
                            <div class="text-xs text-eco-700">Полностью перерабатываемая</div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-eco-700 mr-3"><path d="M21 9V3H9v6"/><path d="M3 11V3"/><path d="M3 9h18"/><path d="M13 14a2 2 0 0 0-2 2v4"/><path d="M9 18h8"/><path d="M15 14.5V14a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v.5"/></svg>
                        <div>
                            <div class="text-sm font-medium text-eco-900">Устойчивые материалы</div>
                            <div class="text-xs text-eco-700">Для лучшего будущего планеты</div>
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
                                {!! $product->full_description !!}
                                
                                <h4 class="text-lg font-semibold text-eco-900 mt-6 mb-3">Преимущества экологичных материалов</h4>
                                <ul>
                                    <li>Снижение углеродного следа на 70% по сравнению с традиционными материалами</li>
                                    <li>Полностью перерабатываемая конструкция для циклической экономики</li>
                                    <li>Производство с использованием возобновляемых источников энергии</li>
                                    <li>Нетоксичные компоненты, безопасные для пользователя и окружающей среды</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Specifications Tab -->
                        <div id="content-specifications" class="tab-content hidden" role="tabpanel">
                            <div class="prose text-eco-800 max-w-none">
                                <h3 class="text-xl font-semibold text-eco-900 mb-4">Технические характеристики</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="text-lg font-medium text-eco-900 mb-3">Материалы</h4>
                                        <ul class="space-y-2">
                                            @foreach($product->attributes->where('group', 'material') as $attribute)
                                            <li class="flex justify-between">
                                                <span class="text-eco-700">{{ $attribute->name }}:</span>
                                                <span class="text-eco-900 font-medium">{{ $attribute->value }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-medium text-eco-900 mb-3">Сертификаты</h4>
                                        <ul class="space-y-2">
                                            @foreach($product->attributes->where('group', 'certification') as $attribute)
                                            <li class="flex justify-between">
                                                <span class="text-eco-700">{{ $attribute->name }}:</span>
                                                <span class="text-eco-900 font-medium">{{ $attribute->value }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Reviews Tab -->
                        <div id="content-reviews" class="tab-content hidden" role="tabpanel">
                            <div class="prose text-eco-800 max-w-none">
                                <h3 class="text-xl font-semibold text-eco-900 mb-4">Отзывы клиентов</h3>
                                
                                @if($product->reviews->count() > 0)
                                    <div class="space-y-6">
                                        @foreach($product->reviews as $review)
                                        <div class="border-b border-eco-100 pb-6 last:border-b-0">
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex items-center">
                                                    <div class="mr-3">
                                                        <div class="w-10 h-10 bg-eco-100 rounded-full flex items-center justify-center text-eco-700 font-medium">
                                                            {{ substr($review->name, 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="font-medium text-eco-900">{{ $review->name }}</div>
                                                        <div class="text-sm text-eco-600">{{ $review->created_at->format('d.m.Y') }}</div>
                                                    </div>
                                                </div>
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
                                            </div>
                                            <p class="text-eco-700">{{ $review->comment }}</p>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-eco-700 mb-4">
                                        Отзывы пока отсутствуют. Будьте первым, кто оставит отзыв об этом товаре!
                                    </p>
                                @endif
                                
                                <!-- Review Form -->
                                <div class="mt-8 bg-eco-50 p-6 rounded-xl">
                                    <h4 class="text-lg font-semibold text-eco-900 mb-4">Оставить отзыв</h4>
                                    <form action="{{ route('product.review', $product->id) }}" method="POST">
                                        @csrf
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label for="name" class="block text-sm font-medium text-eco-700 mb-1">Ваше имя</label>
                                                <input 
                                                    type="text" 
                                                    id="name" 
                                                    name="name" 
                                                    required 
                                                    class="w-full px-4 py-2 border border-eco-200 rounded-lg focus:ring-eco-500 focus:border-eco-500"
                                                >
                                            </div>
                                            <div>
                                                <label for="email" class="block text-sm font-medium text-eco-700 mb-1">Ваш email</label>
                                                <input 
                                                    type="email" 
                                                    id="email" 
                                                    name="email" 
                                                    required 
                                                    class="w-full px-4 py-2 border border-eco-200 rounded-lg focus:ring-eco-500 focus:border-eco-500"
                                                >
                                            </div>
                                        </div>
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
                                            <label for="comment" class="block text-sm font-medium text-eco-700 mb-1">Ваш отзыв</label>
                                            <textarea 
                                                id="comment" 
                                                name="comment" 
                                                rows="4" 
                                                required 
                                                class="w-full px-4 py-2 border border-eco-200 rounded-lg focus:ring-eco-500 focus:border-eco-500"
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
        
        // Initialize the first tab
        document.addEventListener('DOMContentLoaded', function() {
            openTab('description');
            setRating(5);
        });
    </script>
@endsection