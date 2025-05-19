@extends('layouts.app')

@section('title', 'Контакты - EcoSport')

@section('content')
    <!-- Hero Section -->
    <section class="relative py-20 lg:py-24 overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-r from-eco-50/90 to-eco-50/70"></div>
            <img 
                src="{{ asset('img/contact-hero.jpg') }}" 
                alt="Связаться с EcoSport" 
                class="w-full h-full object-cover"
            />
        </div>
        
        <div class="container-width relative px-4 sm:px-6 lg:px-8 pt-16">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl sm:text-5xl font-bold text-eco-900 mb-6">
                    Свяжитесь с нами
                </h1>
                <p class="text-xl text-eco-800 mb-8">
                    У вас есть вопросы о наших продуктах, доставке или вы хотите предложить сотрудничество? Мы всегда рады общению.
                </p>
            </div>
        </div>
    </section>
    
    <!-- Contact Section -->
    <section class="section-padding bg-white">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-12">
                <!-- Contact Info -->
                <div class="md:col-span-1 space-y-8">
                    <!-- Address -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-12 h-12 bg-eco-100 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="text-eco-700" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-eco-900 mb-2">Адрес</h3>
                            <p class="text-eco-700">
                                123 Зеленая улица<br>
                                Эко Город, 12345<br>
                                Россия
                            </p>
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-12 h-12 bg-eco-100 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="text-eco-700" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-eco-900 mb-2">Email</h3>
                            <a href="mailto:info@ecosport.ru" class="text-eco-700 hover:text-eco-600">
                                info@ecosport.ru
                            </a>
                            <p class="text-eco-600 text-sm mt-1">
                                Ответ в течение 24 часов
                            </p>
                        </div>
                    </div>
                    
                    <!-- Phone -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-12 h-12 bg-eco-100 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="text-eco-700" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-eco-900 mb-2">Телефон</h3>
                            <a href="tel:+71234567890" class="text-eco-700 hover:text-eco-600">
                                +7 (123) 456-7890
                            </a>
                            <p class="text-eco-600 text-sm mt-1">
                                Пн-Пт, 9:00 - 18:00
                            </p>
                        </div>
                    </div>
                    
                    <!-- Social Media -->
                    <div>
                        <h3 class="text-lg font-semibold text-eco-900 mb-4">Социальные сети</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 bg-eco-100 rounded-full flex items-center justify-center text-eco-700 hover:bg-eco-200 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-eco-100 rounded-full flex items-center justify-center text-eco-700 hover:bg-eco-200 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-eco-100 rounded-full flex items-center justify-center text-eco-700 hover:bg-eco-200 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div class="md:col-span-2">
                    <div class="bg-eco-50 rounded-2xl p-8">
                        <h2 class="text-2xl font-semibold text-eco-900 mb-6">Отправьте нам сообщение</h2>
                        <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                            @csrf
                            
                            @if(session('success'))
                                <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6">
                                    {{ session('success') }}
                                </div>
                            @endif
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-eco-900 mb-2">Имя</label>
                                    <input 
                                        type="text" 
                                        id="name" 
                                        name="name" 
                                        required 
                                        class="w-full rounded-lg border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                        value="{{ old('name') }}"
                                    >
                                    @error('name')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="email" class="block text-eco-900 mb-2">Email</label>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        required 
                                        class="w-full rounded-lg border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                        value="{{ old('email') }}"
                                    >
                                    @error('email')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-eco-900 mb-2">Телефон (опционально)</label>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    class="w-full rounded-lg border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                    value="{{ old('phone') }}"
                                >
                                @error('phone')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="subject" class="block text-eco-900 mb-2">Тема</label>
                                <select 
                                    id="subject" 
                                    name="subject" 
                                    required 
                                    class="w-full rounded-lg border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                >
                                    <option value="" {{ old('subject') == '' ? 'selected' : '' }}>Выберите тему</option>
                                    <option value="Вопрос о продукте" {{ old('subject') == 'Вопрос о продукте' ? 'selected' : '' }}>Вопрос о продукте</option>
                                    <option value="Отслеживание заказа" {{ old('subject') == 'Отслеживание заказа' ? 'selected' : '' }}>Отслеживание заказа</option>
                                    <option value="Возврат и обмен" {{ old('subject') == 'Возврат и обмен' ? 'selected' : '' }}>Возврат и обмен</option>
                                    <option value="Сотрудничество" {{ old('subject') == 'Сотрудничество' ? 'selected' : '' }}>Сотрудничество</option>
                                    <option value="Другое" {{ old('subject') == 'Другое' ? 'selected' : '' }}>Другое</option>
                                </select>
                                @error('subject')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="message" class="block text-eco-900 mb-2">Сообщение</label>
                                <textarea 
                                    id="message" 
                                    name="message" 
                                    rows="5" 
                                    required 
                                    class="w-full rounded-lg border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                >{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <button type="submit" class="eco-btn">
                                    Отправить сообщение
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Map Section -->
    <section class="section-padding bg-white pt-0">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center mb-12">
                <h2 class="text-3xl font-bold text-eco-900 mb-4">Наше расположение</h2>
                <p class="text-eco-700">
                    Вы всегда можете посетить наш офис для личной консультации или знакомства с продукцией.
                </p>
            </div>
            <div class="rounded-2xl overflow-hidden shadow-md h-[400px]">
                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-600">Интерактивная карта</span>
                </div>
            </div>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section class="section-padding bg-eco-50">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center mb-16">
                <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                    Часто задаваемые вопросы
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                    Ответы на популярные вопросы
                </h2>
                <p class="text-eco-700">
                    Не нашли ответ на свой вопрос? Свяжитесь с нами, и мы с радостью поможем.
                </p>
            </div>
            
            <div class="max-w-4xl mx-auto space-y-6">
                <div x-data="{ open: false }" class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <button 
                        @click="open = !open" 
                        class="flex justify-between items-center w-full p-6 text-left"
                    >
                        <span class="text-lg font-semibold text-eco-900">Какие способы доставки вы предлагаете?</span>
                        <svg 
                            xmlns="http://www.w3.org/2000/svg" 
                            :class="{'rotate-180': open}" 
                            class="h-5 w-5 transform transition-transform duration-200 text-eco-700" 
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="px-6 pb-6 pt-0">
                        <p class="text-eco-700">
                            Мы предлагаем несколько способов доставки: курьерская доставка по городу, доставка Почтой России, СДЭК и другими популярными службами доставки. При оформлении заказа вы можете выбрать наиболее удобный для вас вариант.
                        </p>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <button 
                        @click="open = !open" 
                        class="flex justify-between items-center w-full p-6 text-left"
                    >
                        <span class="text-lg font-semibold text-eco-900">Как оформить возврат товара?</span>
                        <svg 
                            xmlns="http://www.w3.org/2000/svg" 
                            :class="{'rotate-180': open}" 
                            class="h-5 w-5 transform transition-transform duration-200 text-eco-700" 
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="px-6 pb-6 pt-0">
                        <p class="text-eco-700">
                            Вы можете вернуть товар в течение 14 дней с момента получения, если он не был в использовании и сохранил товарный вид. Для оформления возврата свяжитесь с нашей службой поддержки по телефону или email, и мы предоставим вам подробную инструкцию.
                        </p>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <button 
                        @click="open = !open" 
                        class="flex justify-between items-center w-full p-6 text-left"
                    >
                        <span class="text-lg font-semibold text-eco-900">Как определить свой размер одежды?</span>
                        <svg 
                            xmlns="http://www.w3.org/2000/svg" 
                            :class="{'rotate-180': open}" 
                            class="h-5 w-5 transform transition-transform duration-200 text-eco-700" 
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="px-6 pb-6 pt-0">
                        <p class="text-eco-700">
                            На странице каждого товара вы найдете таблицу размеров с подробными измерениями. Если у вас остаются сомнения, вы всегда можете обратиться к нашим консультантам, которые помогут вам выбрать подходящий размер.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Newsletter Section -->
    @include('components.newsletter')
@endsection
