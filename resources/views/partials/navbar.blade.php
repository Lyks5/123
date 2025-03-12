<nav x-data="{ isOpen: false, isScrolled: false }" 
     @scroll.window="isScrolled = window.scrollY > 20"
     class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
     :class="isScrolled ? 'bg-white/90 backdrop-blur-md shadow-sm' : 'bg-transparent'">
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Логотип -->
            <a href="{{ route('home') }}" class="text-xl font-semibold text-eco-800">
                EcoSport
            </a>

            <!-- Десктопное меню -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-eco-600 transition-colors">Главная</a>
                <a href="{{ route('shop') }}" class="text-gray-700 hover:text-eco-600 transition-colors">Магазин</a>
                <a href="{{ route('about') }}" class="text-gray-700 hover:text-eco-600 transition-colors">О нас</a>
                <a href="{{ route('sustainability') }}" class="text-gray-700 hover:text-eco-600 transition-colors">Устойчивость</a>
                <a href="{{ route('blog') }}" class="text-gray-700 hover:text-eco-600 transition-colors">Блог</a>
                <a href="{{ route('contact') }}" class="text-gray-700 hover:text-eco-600 transition-colors">Контакты</a>
            </div>

            <!-- Иконки аккаунта и корзины -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ route('account') }}" class="p-2 text-gray-700 hover:text-eco-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </a>
                <a href="{{ route('cart') }}" class="p-2 text-gray-700 hover:text-eco-600 transition-colors relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-eco-600 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">0</span>
                </a>
            </div>

            <!-- Кнопка мобильного меню -->
            <button @click="isOpen = !isOpen" class="md:hidden p-2 text-gray-700 hover:text-eco-600">
                <svg class="w-6 h-6" :class="{ 'hidden': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg class="w-6 h-6" :class="{ 'hidden': !isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Мобильное меню -->
    <div class="md:hidden" x-show="isOpen" @click.away="isOpen = false">
        <div class="px-2 pt-2 pb-3 space-y-1 bg-white/95 backdrop-blur-md shadow-lg">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-eco-50">Главная</a>
            <a href="{{ route('shop') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-eco-50">Магазин</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-eco-50">О нас</a>
            <a href="{{ route('sustainability') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-eco-50">Устойчивость</a>
            <a href="{{ route('blog') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-eco-50">Блог</a>
            <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-eco-50">Контакты</a>
            
            <div class="flex items-center space-x-4 px-3 py-2">
                <a href="{{ route('account') }}" class="p-1 text-gray-700 hover:text-eco-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </a>
                <a href="{{ route('cart') }}" class="p-1 text-gray-700 hover:text-eco-600 relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-eco-600 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">0</span>
                </a>
            </div>
        </div>
    </div>
</nav>