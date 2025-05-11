@extends('layouts.app')

@section('title', 'Мой профиль - ЭкоМаркет')

@section('content')
<!-- Header spacing -->
<div class="h-20"></div>

<div class="bg-gradient-to-b from-eco-50 to-white py-12 min-h-screen">
    <div class="container mx-auto px-4">
        <!-- Page header -->
        <div class="mb-10 max-w-3xl mx-auto text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-eco-900 mb-3">Личный кабинет</h1>
            <p class="text-eco-600 text-lg max-w-2xl mx-auto">Управляйте своим профилем и просматривайте заказы</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 max-w-7xl mx-auto">
            <!-- Sidebar navigation -->
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
                            <a href="{{ route('account') }}" class="block px-4 py-2.5 rounded-lg text-eco-700 hover:bg-eco-50 transition-colors">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </svg>
                                    <span class="font-medium">Обзор</span>
                                </div>
                            </a>
                            
                            <a href="{{ route('account.orders') }}" class="block px-4 py-2.5 rounded-lg text-eco-700 hover:bg-eco-50 transition-colors">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                        <line x1="3" y1="6" x2="21" y2="6"></line>
                                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                                    </svg>
                                    <span class="font-medium">Мои заказы</span>
                                </div>
                            </a>
                            
                            <a href="{{ route('account.profile') }}" class="block px-4 py-2.5 rounded-lg bg-eco-100 text-eco-900 transition-colors">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <span class="font-medium">Профиль</span>
                                </div>
                            </a>

                            <a href="{{ route('account.wishlists') }}" class="block px-4 py-2.5 rounded-lg text-eco-700 hover:bg-eco-50 transition-colors">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                    <span class="font-medium">Избранное</span>
                                </div>
                            </a>
        
                            <a href="{{ route('account.addresses') }}" class="block px-4 py-2.5 rounded-lg text-eco-700 hover:bg-eco-50 transition-colors">
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

            <!-- Main content -->
            <div class="lg:col-span-3 space-y-8">
                @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-lg mb-6 animate-fade-in">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
                @endif
                
                <!-- Personal Info -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-eco-100">
                        <h2 class="text-xl font-semibold text-eco-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-eco-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            Личные данные
                        </h2>
                        <p class="text-sm text-eco-600 mt-1">Обновите основную информацию о себе</p>
                    </div>
                    
                    <div class="p-6">
                        <form action="{{ route('account.update') }}" method="POST" class="space-y-6">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-eco-800 mb-1.5">
                                        Имя
                                    </label>
                                    <input 
                                        id="name" 
                                        name="name" 
                                        type="text" 
                                        value="{{ old('name', $user->name) }}" 
                                        class="w-full rounded-lg border-eco-200 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 transition-colors"
                                    >
                                    @error('name')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-eco-800 mb-1.5">
                                        Email
                                    </label>
                                    <input 
                                        id="email" 
                                        name="email" 
                                        type="email" 
                                        value="{{ old('email', $user->email) }}" 
                                        class="w-full rounded-lg border-eco-200 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 transition-colors"
                                    >
                                    @error('email')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-eco-800 mb-1.5">
                                        Телефон
                                    </label>
                                    <input 
                                        id="phone" 
                                        name="phone" 
                                        type="text" 
                                        value="{{ old('phone', $user->phone) }}" 
                                        class="w-full rounded-lg border-eco-200 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 transition-colors"
                                    >
                                    @error('phone')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="birth_date" class="block text-sm font-medium text-eco-800 mb-1.5">
                                        Дата рождения
                                    </label>
                                    <input 
                                        id="birth_date" 
                                        name="birth_date" 
                                        type="date" 
                                        value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}" 
                                        class="w-full rounded-lg border-eco-200 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 transition-colors"
                                    >
                                    @error('birth_date')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-eco-800 mb-1.5">
                                        Пол
                                    </label>
                                    <select 
                                        id="gender" 
                                        name="gender" 
                                        class="w-full rounded-lg border-eco-200 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 transition-colors"
                                    >
                                        <option value="">Не указано</option>
                                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Мужской</option>
                                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Женский</option>
                                        <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Другой</option>
                                    </select>
                                    @error('gender')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            
                                <div class="md:col-span-2">
                                    <label for="bio" class="block text-sm font-medium text-eco-800 mb-1.5">
                                        О себе
                                    </label>
                                    <textarea 
                                        id="bio" 
                                        name="bio" 
                                        rows="3" 
                                        class="w-full rounded-lg border-eco-200 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 transition-colors"
                                    >{{ old('bio', $user->bio) }}</textarea>
                                    @error('bio')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="pt-2">
                                <button type="submit" class="px-4 py-2.5 bg-eco-600 hover:bg-eco-700 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                        <polyline points="7 3 7 8 15 8"></polyline>
                                    </svg>
                                    Сохранить изменения
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Password Change -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-eco-100">
                        <h2 class="text-xl font-semibold text-eco-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-eco-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            Сменить пароль
                        </h2>
                        <p class="text-sm text-eco-600 mt-1">Обновите пароль для обеспечения безопасности аккаунта</p>
                    </div>
                    
                    <div class="p-6">
                        <form action="{{ route('account.password.update') }}" method="POST" class="space-y-5">
                            @csrf
                            
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-eco-800 mb-1.5">
                                    Текущий пароль
                                </label>
                                <input 
                                    id="current_password" 
                                    name="current_password" 
                                    type="password" 
                                    class="w-full rounded-lg border-eco-200 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 transition-colors"
                                >
                                @error('current_password')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-eco-800 mb-1.5">
                                        Новый пароль
                                    </label>
                                    <input 
                                        id="password" 
                                        name="password" 
                                        type="password" 
                                        class="w-full rounded-lg border-eco-200 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 transition-colors"
                                    >
                                    @error('password')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-eco-800 mb-1.5">
                                        Подтверждение нового пароля
                                    </label>
                                    <input 
                                        id="password_confirmation" 
                                        name="password_confirmation" 
                                        type="password" 
                                        class="w-full rounded-lg border-eco-200 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 transition-colors"
                                    >
                                </div>
                            </div>
                            
                            <div class="pt-2">
                                <button type="submit" class="px-4 py-2.5 bg-eco-600 hover:bg-eco-700 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                    Обновить пароль
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Eco Impact -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-eco-100">
                        <h2 class="text-xl font-semibold text-eco-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-eco-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                                <path d="M19 7v4h-4"></path>
                            </svg>
                            Ваш эко-вклад
                        </h2>
                        <p class="text-sm text-eco-600 mt-1">Отслеживайте положительное влияние ваших покупок на окружающую среду</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-eco-50 p-4 rounded-lg">
                                <div class="text-eco-600 text-sm font-medium mb-1">Углеродный след</div>
                                <div class="text-2xl font-bold text-eco-800">
                                    {{ number_format($user->getTotalEcoImpact()['carbon_saved'] ?? 0, 1) }} кг
                                </div>
                                <div class="text-xs text-eco-500 mt-1">CO2 сэкономлено</div>
                            </div>
                            
                            <div class="bg-eco-50 p-4 rounded-lg">
                                <div class="text-eco-600 text-sm font-medium mb-1">Пластик</div>
                                <div class="text-2xl font-bold text-eco-800">
                                    {{ number_format($user->getTotalEcoImpact()['plastic_saved'] ?? 0, 1) }} кг
                                </div>
                                <div class="text-xs text-eco-500 mt-1">пластика не использовано</div>
                            </div>
                            
                            <div class="bg-eco-50 p-4 rounded-lg">
                                <div class="text-eco-600 text-sm font-medium mb-1">Вода</div>
                                <div class="text-2xl font-bold text-eco-800">
                                    {{ number_format($user->getTotalEcoImpact()['water_saved'] ?? 0, 1) }} л
                                </div>
                                <div class="text-xs text-eco-500 mt-1">воды сэкономлено</div>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-eco-100">
                            <p class="text-eco-700 text-sm">
                                Эти данные отражают экологический эффект от ваших покупок на нашей платформе. 
                                Спасибо, что помогаете делать мир лучше!
                            </p>
                            
                            <div class="mt-4 flex items-center">
                                <div class="h-2.5 w-full bg-gray-200 rounded-full overflow-hidden">
                                    @php
                                        $ecoScore = $user->eco_impact_score ?? 0;
                                        $scorePercent = min(100, max(0, $ecoScore * 10)); // Convert score to percentage (0-10 → 0-100%)
                                    @endphp
                                    <div class="h-full bg-eco-500 rounded-full" style="width: {{ $scorePercent }}%"></div>
                                </div>
                                <div class="ml-4 min-w-16 text-center">
                                    <span class="text-eco-900 font-semibold">{{ $ecoScore }}/10</span>
                                </div>
                            </div>
                            
                            <div class="mt-1 text-xs text-eco-600">Ваш эко-рейтинг</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection