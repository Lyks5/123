@extends('layouts.app')

@section('title', 'Избранные товары - ЭкоМаркет')

@section('content')
<div class="bg-gray-50 py-8 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-eco-900">Избранные товары</h1>
            <p class="text-eco-700">Ваша персональная коллекция экологичных товаров</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Боковое меню -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-eco-100 rounded-full flex items-center justify-center text-eco-700">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        @endif
                    </div>
                    <div class="ml-4">
                        <h3 class="font-medium text-eco-900">{{ auth()->user()->name }}</h3>
                        <p class="text-sm text-eco-600">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <nav class="space-y-1">
                    <a href="{{ route('account') }}" class="block px-4 py-2 rounded-md text-eco-700 hover:bg-eco-50 hover:text-eco-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Главная
                    </a>
                    <a href="{{ route('account.orders') }}" class="block px-4 py-2 rounded-md text-eco-700 hover:bg-eco-50 hover:text-eco-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        Мои заказы
                    </a>
                    <a href="{{ route('account.wishlists') }}" class="block px-4 py-2 rounded-md bg-eco-50 text-eco-900 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                        Избранное
                    </a>
                    <a href="{{ route('account.addresses') }}" class="block px-4 py-2 rounded-md text-eco-700 hover:bg-eco-50 hover:text-eco-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        Адреса
                    </a>
                    @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded-md text-eco-700 hover:bg-eco-50 hover:text-eco-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="3" y1="9" x2="21" y2="9"></line>
                            <line x1="9" y1="21" x2="9" y2="9"></line>
                        </svg>
                        Админ-панель
                    </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 rounded-md text-eco-700 hover:bg-eco-50 hover:text-eco-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            Выйти
                        </button>
                    </form>
                </nav>
            </div>

            <!-- Основное содержимое -->
            <div class="col-span-1 md:col-span-3">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-6 border-b border-gray-200 pb-4">
                        <div>
                            <h2 class="text-xl font-semibold text-eco-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-eco-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                                Избранные товары
                            </h2>
                            <p class="text-sm text-eco-600">Товары, которые вы отметили как понравившиеся</p>
                        </div>
                    </div>
                    
                    @if(session('success'))
                        <div class="bg-green-50 text-green-800 p-4 rounded-md mb-6">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($wishlistItems->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($wishlistItems as $item)
                                <div class="border border-eco-200 rounded-lg overflow-hidden bg-white transition-all hover:shadow">
                                    <div class="relative h-48 bg-eco-100">
                                        @if($item->product->images->isNotEmpty())
                                            <img 
                                                src="{{ asset('storage/' . $item->product->images->where('is_primary', true)->first()->image_path) }}" 
                                                alt="{{ $item->product->name }}" 
                                                class="w-full h-full object-cover"
                                            >
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-eco-50 text-eco-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                    <polyline points="21 15 16 10 5 21"></polyline>
                                                </svg>
                                            </div>
                                        @endif
                                        <form action="{{ route('account.wishlists.remove', $item->product_id) }}" method="POST" class="absolute top-2 right-2">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                class="bg-white/80 backdrop-blur-sm p-1.5 rounded-full hover:bg-white hover:text-red-500 transition-colors"
                                                aria-label="Удалить из избранного"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="p-4">
                                        <div class="mb-2">
                                            <h3 class="text-base font-medium line-clamp-2 text-eco-900">{{ $item->product->name }}</h3>
                                            <p class="text-xs text-eco-600">{{ $item->product->category->name }}</p>
                                        </div>
                                        <p class="font-semibold text-lg text-eco-900 mb-4">{{ number_format($item->product->price, 0, '.', ' ') }} ₽</p>
                                        <div class="flex gap-2">
                                            <form action="{{ route('cart.add') }}" method="POST" class="w-full">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="w-full bg-eco-600 hover:bg-eco-700 text-white py-2 px-4 rounded transition-colors">
                                                    В корзину
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="mx-auto w-16 h-16 rounded-full bg-eco-50 flex items-center justify-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-eco-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-eco-900 mb-2">У вас еще нет избранных товаров</h3>
                            <p class="text-eco-600 mb-6 max-w-md mx-auto">Добавляйте понравившиеся товары в избранное, чтобы быстро найти их позже</p>
                            <a href="{{ route('shop') }}" class="inline-block bg-eco-600 hover:bg-eco-700 text-white py-2 px-4 rounded transition-colors">
                                Перейти в магазин
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection