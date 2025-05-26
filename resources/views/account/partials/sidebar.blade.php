<div class="lg:col-span-1">
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden sticky top-24">
        <div class="p-6 border-b border-eco-100">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-eco-100 rounded-full flex items-center justify-center text-eco-600 mr-4">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    @endif
                </div>
                <div>
                    <h3 class="font-medium text-eco-900">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-eco-600">{{ auth()->user()->email }}</p>
                    @if(auth()->user()->is_admin)
                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            Администратор
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <nav class="p-3">
            <div class="space-y-1">
                <a href="{{ route('account') }}" class="block px-4 py-2.5 rounded-lg {{ request()->routeIs('account') ? 'bg-eco-100 text-eco-900' : 'text-eco-700 hover:bg-eco-50' }} transition-colors">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span class="font-medium">Обзор</span>
                    </div>
                </a>
                
                <a href="{{ route('account.orders') }}" class="block px-4 py-2.5 rounded-lg {{ request()->routeIs('account.orders') ? 'bg-eco-100 text-eco-900' : 'text-eco-700 hover:bg-eco-50' }} transition-colors">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        <span class="font-medium">Мои заказы</span>
                    </div>
                </a>
                
                <a href="{{ route('account.profile') }}" class="block px-4 py-2.5 rounded-lg {{ request()->routeIs('account.profile') ? 'bg-eco-100 text-eco-900' : 'text-eco-700 hover:bg-eco-50' }} transition-colors">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span class="font-medium">Профиль</span>
                    </div>
                </a>
                
                <a href="{{ route('account.wishlists') }}" class="block px-4 py-2.5 rounded-lg {{ request()->routeIs('account.wishlists') ? 'bg-eco-100 text-eco-900' : 'text-eco-700 hover:bg-eco-50' }} transition-colors">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                        <span class="font-medium">Избранное</span>
                    </div>
                </a>
                
                <a href="{{ route('account.addresses') }}" class="block px-4 py-2.5 rounded-lg {{ request()->routeIs('account.addresses') ? 'bg-eco-100 text-eco-900' : 'text-eco-700 hover:bg-eco-50' }} transition-colors">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span class="font-medium">Адреса</span>
                    </div>
                </a>
                
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 rounded-lg text-eco-700 hover:bg-eco-50 transition-colors mt-4 border-t border-eco-100 pt-4">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="3" y1="9" x2="21" y2="9"></line>
                                <line x1="9" y1="21" x2="9" y2="9"></line>
                            </svg>
                            <span class="font-medium">Админ-панель</span>
                        </div>
                    </a>
                @endif
                
                <form method="POST" action="{{ route('logout') }}" class="mt-4 border-t border-eco-100 pt-4">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span class="font-medium">Выйти</span>
                        </div>
                    </button>
                </form>
            </div>
        </nav>
    </div>
</div>