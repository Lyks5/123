@extends('layouts.app')

@section('title', 'Вход - EcoSport')

@section('content')
    <section class="py-16 md:py-24">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="max-w-md mx-auto">
                <h1 class="text-3xl font-bold text-eco-900 mb-8 text-center">Вход в аккаунт</h1>
                
                <div class="bg-white rounded-xl shadow-md p-8">
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf
                        
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
                                autofocus 
                                class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                            >
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Password -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label for="password" class="block text-sm font-medium text-eco-800">
                                    Пароль
                                </label>
                                <a href="{{ route('password.request') }}" class="text-sm text-eco-600 hover:text-eco-700">
                                    Забыли пароль?
                                </a>
                            </div>
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
                        
                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <input 
                                id="remember" 
                                type="checkbox" 
                                name="remember" 
                                class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-eco-300 rounded"
                            >
                            <label for="remember" class="ml-2 block text-sm text-eco-700">
                                Запомнить меня
                            </label>
                        </div>
                        
                        <button 
                            type="submit" 
                            class="w-full py-3 bg-eco-600 hover:bg-eco-700 text-white font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500"
                        >
                            Войти
                        </button>
                    </form>
                    
                    <div class="mt-6 text-center">
                        <p class="text-sm text-eco-700">
                            Нет аккаунта? 
                            <a href="{{ route('register') }}" class="font-medium text-eco-600 hover:text-eco-700">
                                Зарегистрироваться
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
