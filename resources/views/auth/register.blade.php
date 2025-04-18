@extends('layouts.app')

@section('title', 'Регистрация - EcoSport')

@section('content')
    <section class="py-16 md:py-24">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="max-w-md mx-auto">
                <h1 class="text-3xl font-bold text-eco-900 mb-8 text-center">Создание аккаунта</h1>
                
                <div class="bg-white rounded-xl shadow-md p-8">
                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-eco-800 mb-1">
                                Имя
                            </label>
                            <input 
                                id="name" 
                                type="text" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required 
                                autofocus 
                                class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-eco-800 mb-1">
                                Email
                            </label>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                            >
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-eco-800 mb-1">
                                Пароль
                            </label>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required 
                                class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                            >
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-eco-800 mb-1">
                                Подтверждение пароля
                            </label>
                            <input 
                                id="password_confirmation" 
                                type="password" 
                                name="password_confirmation" 
                                required 
                                class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                            >
                        </div>
                        
                        <button 
                            type="submit" 
                            class="w-full py-3 bg-eco-600 hover:bg-eco-700 text-white font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500"
                        >
                            Зарегистрироваться
                        </button>
                    </form>
                    
                    <div class="mt-6 text-center">
                        <p class="text-sm text-eco-700">
                            Уже есть аккаунт? 
                            <a href="{{ route('login') }}" class="font-medium text-eco-600 hover:text-eco-700">
                                Войти
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
