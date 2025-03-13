
<nav 
    x-data="{ isOpen: false, isScrolled: false }"
    x-init="window.addEventListener('scroll', () => { isScrolled = window.scrollY > 20 })"
    :class="isScrolled ? 'bg-white/90 backdrop-blur-md shadow-sm' : 'bg-transparent'"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
>
    <div class="container-width px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-xl font-semibold text-eco-800">
                    EcoSport
                </a>
            </div>
            
            <div class="hidden md:block">
                <div class="ml-10 flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-foreground hover:text-eco-600 transition-colors">
                        Главная
                    </a>
                    <a href="{{ route('shop') }}" class="text-foreground hover:text-eco-600 transition-colors">
                        Магазин
                    </a>
                    <a href="{{ route('about') }}" class="text-foreground hover:text-eco-600 transition-colors">
                        О нас
                    </a>
                    <a href="{{ route('sustainability') }}" class="text-foreground hover:text-eco-600 transition-colors">
                        Экология
                    </a>
                    <a href="{{ route('blog') }}" class="text-foreground hover:text-eco-600 transition-colors">
                        Блог
                    </a>
                    <a href="{{ route('contact') }}" class="text-foreground hover:text-eco-600 transition-colors">
                        Контакты
                    </a>
                </div>
            </div>
            
            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ route('account') }}" aria-label="Аккаунт" class="p-2 text-foreground hover:text-eco-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </a>
                <a href="{{ route('cart') }}" aria-label="Корзина" class="p-2 text-foreground hover:text-eco-600 transition-colors relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    @if (session()->has('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-1 -right-1 bg-eco-600 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">
                            {{ count(session('cart')) }}
                        </span>
                    @else
                        <span class="absolute -top-1 -right-1 bg-eco-600 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">
                            0
                        </span>
                    @endif
                </a>
            </div>
            
            <div class="md:hidden flex items-center">
                <button
                    @click="isOpen = !isOpen"
                    class="inline-flex items-center justify-center p-2 rounded-md text-foreground hover:text-eco-600 focus:outline-none"
                    aria-expanded="false"
                >
                    <span class="sr-only">Открыть меню</span>
                    <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" class="block h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                    <svg x-show="isOpen" xmlns="http://www.w3.org/2000/svg" class="block h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Мобильное меню -->
    <div x-show="isOpen" class="md:hidden" style="display: none;">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white/95 backdrop-blur-md shadow-lg">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-foreground hover:bg-eco-50">
                Главная
            </a>
            <a href="{{ route('shop') }}" class="block px-3 py-2 rounded-md text-base font-medium text-foreground hover:bg-eco-50">
                Магазин
            </a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-md text-base font-medium text-foreground hover:bg-eco-50">
                О нас
            </a>
            <a href="{{ route('sustainability') }}" class="block px-3 py-2 rounded-md text-base font-medium text-foreground hover:bg-eco-50">
                Экология
            </a>
            <a href="{{ route('blog') }}" class="block px-3 py-2 rounded-md text-base font-medium text-foreground hover:bg-eco-50">
                Блог
            </a>
            <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-md text-base font-medium text-foreground hover:bg-eco-50">
                Контакты
            </a>
            <div class="flex items-center space-x-4 px-3 py-2">
                <a href="{{ route('account') }}" aria-label="Аккаунт" class="p-1 text-foreground hover:text-eco-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </a>
                <a href="{{ route('cart') }}" aria-label="Корзина" class="p-1 text-foreground hover:text-eco-600 transition-colors relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    @if (session()->has('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-1 -right-1 bg-eco-600 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">
                            {{ count(session('cart')) }}
                        </span>
                    @else
                        <span class="absolute -top-1 -right-1 bg-eco-600 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">
                            0
                        </span>
                    @endif
                </a>
            </div>
        </div>
    </div>
</nav>