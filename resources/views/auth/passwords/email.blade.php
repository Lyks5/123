extends('layouts.app')

@section('title', 'Сброс пароля - EcoSport')

@section('content')
    <section class="py-16 md:py-24">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="max-w-md mx-auto">
                <h1 class="text-3xl font-bold text-eco-900 mb-8 text-center">Сброс пароля</h1>
                
                <div class="bg-white rounded-xl shadow-md p-8">
                    @if (session('status'))
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                        @csrf
                        
                        <p class="text-eco-700 mb-4">
                            Укажите ваш email, и мы отправим вам ссылку для сброса пароля.
                        </p>
                        
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
                        
                        <button 
                            type="submit" 
                            class="w-full py-3 bg-eco-600 hover:bg-eco-700 text-white font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500"
                        >
                            Отправить ссылку для сброса
                        </button>
                    </form>
                    
                    <div class="mt-6 text-center">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-eco-600 hover:text-eco-700">
                            Вернуться ко входу
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
