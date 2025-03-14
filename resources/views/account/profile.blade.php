
@extends('layouts.app')

@section('title', 'Мой профиль - ЭкоМаркет')

@section('content')
<div class="bg-gray-50 py-8 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-eco-900">Мой профиль</h1>
            <p class="text-eco-700">Управление личными данными</p>
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
                    <a href="{{ route('account.profile') }}" class="block px-4 py-2 rounded-md bg-eco-50 text-eco-900 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Профиль
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
            <div class="col-span-1 md:col-span-3 space-y-6">
                <!-- Профиль -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-eco-900">Личные данные</h2>
                        <p class="text-sm text-eco-600">Обновите основную информацию о себе</p>
                    </div>
                    <div class="p-6">
                        @if(session('success'))
                            <div class="bg-green-50 text-green-800 p-4 rounded-md mb-6">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <form action="{{ route('account.update') }}" method="POST" class="space-y-4">

                            @csrf
                            
                            <div>
                                <label for="name" class="block text-sm font-medium text-eco-800 mb-1">
                                    Имя
                                </label>
                                <input 
                                    id="name" 
                                    name="name" 
                                    type="text" 
                                    value="{{ old('name', $user->name) }}" 
                                    class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                >
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-eco-800 mb-1">
                                    Email
                                </label>
                                <input 
                                    id="email" 
                                    name="email" 
                                    type="email" 
                                    value="{{ old('email', $user->email) }}" 
                                    class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                >
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-eco-800 mb-1">
                                    Телефон
                                </label>
                                <input 
                                    id="phone" 
                                    name="phone" 
                                    type="text" 
                                    value="{{ old('phone', $user->phone) }}" 
                                    class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                >
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-eco-800 mb-1">
                                    Дата рождения
                                </label>
                                <input 
                                    id="birth_date" 
                                    name="birth_date" 
                                    type="date" 
                                    value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}" 
                                    class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                >
                                @error('birth_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="gender" class="block text-sm font-medium text-eco-800 mb-1">
                                    Пол
                                </label>
                                <select 
                                    id="gender" 
                                    name="gender" 
                                    class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                >
                                    <option value="">Не указано</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Мужской</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Женский</option>
                                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Другой</option>
                                </select>
                                @error('gender')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="bio" class="block text-sm font-medium text-eco-800 mb-1">
                                    О себе
                                </label>
                                <textarea 
                                    id="bio" 
                                    name="bio" 
                                    rows="3" 
                                    class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                >{{ old('bio', $user->bio) }}</textarea>
                                @error('bio')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="pt-4">
                                <button type="submit" class="px-4 py-2 bg-eco-600 hover:bg-eco-700 text-white font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500">
                                    Сохранить изменения
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Смена пароля -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-eco-900">Сменить пароль</h2>
                        <p class="text-sm text-eco-600">Обновите пароль для обеспечения безопасности аккаунта</p>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('account.password.update') }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-eco-800 mb-1">
                                    Текущий пароль
                                </label>
                                <input 
                                    id="current_password" 
                                    name="current_password" 
                                    type="password" 
                                    class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                >
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-eco-800 mb-1">
                                    Новый пароль
                                </label>
                                <input 
                                    id="password" 
                                    name="password" 
                                    type="password" 
                                    class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                >
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-eco-800 mb-1">
                                    Подтверждение нового пароля
                                </label>
                                <input 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    type="password" 
                                    class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                >
                            </div>
                            
                            <div class="pt-4">
                                <button type="submit" class="px-4 py-2 bg-eco-600 hover:bg-eco-700 text-white font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500">
                                    Обновить пароль
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
