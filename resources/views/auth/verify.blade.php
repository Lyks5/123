@extends('layouts.app')

@section('title', 'Подтверждение Email - EcoSport')

@section('content')
    <section class="py-16 md:py-24">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="max-w-md mx-auto">
                <h1 class="text-3xl font-bold text-eco-900 mb-8 text-center">Подтверждение Email</h1>
                
                <div class="bg-white rounded-xl shadow-md p-8">
                    @if (session('resent'))
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                            Новая ссылка для подтверждения была отправлена на ваш email.
                        </div>
                    @endif
                    
                    <p class="text-eco-700 mb-4">
                        Прежде чем продолжить, пожалуйста, проверьте свою электронную почту на наличие ссылки для подтверждения.
                    </p>
                    
                    <p class="text-eco-700 mb-6">
                        Если вы не получили письмо, вы можете запросить новое по кнопке ниже.
                    </p>
                    
                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button 
                            type="submit" 
                            class="w-full py-3 bg-eco-600 hover:bg-eco-700 text-white font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500"
                        >
                            Отправить повторно
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection