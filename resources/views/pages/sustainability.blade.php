
@extends('layouts.app')

@section('title', 'Экология и устойчивое развитие - EcoSport')

@section('content')
    <!-- Hero Section -->
    <section class="relative py-20 lg:py-32 overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-r from-eco-50/90 to-eco-50/70"></div>
            <img 
                src="https://images.unsplash.com/photo-1441974231531-c6227db76b6e?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" 
                alt="Устойчивое развитие" 
                class="w-full h-full object-cover"
            />
        </div>
        
        <div class="container-width relative px-4 sm:px-6 lg:px-8 pt-16">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-eco-900 mb-6">
                    Наш подход к устойчивому развитию
                </h1>
                <p class="text-xl text-eco-800 mb-8">
                    Мы верим, что спортивное снаряжение может и должно производиться с минимальным воздействием на планету.
                </p>
            </div>
        </div>
    </section>
    
    <!-- Materials Section -->
    <section class="section-padding bg-white">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                        Экологичные материалы
                    </span>
                    <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                        Выбор материалов с заботой о планете
                    </h2>
                    <p class="text-eco-800 mb-6">
                        Мы тщательно отбираем материалы для наших продуктов, отдавая предпочтение переработанным, биоразлагаемым и органическим вариантам.
                    </p>
                    
                    <div class="space-y-6 mt-8">
                        <!-- Material 1 -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 p-3 bg-eco-100 rounded-xl text-eco-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h12l4 6-10 13L2 9Z"></path><path d="M11 3 8 9l4 13 4-13-3-6"></path><path d="M2 9h20"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-eco-900 mb-2">Переработанный полиэстер</h3>
                                <p class="text-eco-700">
                                    Наша спортивная одежда изготовлена из переработанных пластиковых бутылок, что помогает уменьшить количество пластика в океанах и на свалках.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Material 2 -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 p-3 bg-eco-100 rounded-xl text-eco-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.5 14.5c0 2.76-2.01 5-4.5 5s-4.5-2.24-4.5-5 2.01-5 4.5-5 4.5 2.24 4.5 5Z"></path><path d="M13 2v5"></path><path d="M13 17v5"></path><path d="m17 7-2.3 2"></path><path d="m8.3 15.7-2.3 2"></path><path d="m20 13-5 .02"></path><path d="M9 13H4"></path><path d="m17 18.95-2.3-2"></path><path d="m8.3 7.3-2.3-2"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-eco-900 mb-2">Органический хлопок</h3>
                                <p class="text-eco-700">
                                    Мы используем только органический хлопок, выращенный без пестицидов и химических удобрений, что защищает почву и экономит воду.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Material 3 -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 p-3 bg-eco-100 rounded-xl text-eco-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H6a3 3 0 0 0-3 3v10Z"></path><path d="M4 6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2"></path><path d="M15 14v2"></path><path d="M9 14v2"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-eco-900 mb-2">Натуральный каучук</h3>
                                <p class="text-eco-700">
                                    Наши коврики для йоги и другие аксессуары изготовлены из натурального каучука, собранного экологически ответственным способом.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="relative">
                    <img 
                        src="https://www.az-online.de/bilder/2013/11/21/3233246/2007087139-kl_crestock_peterhahncom-2Dea.jpg" 
                        alt="Экологичные материалы" 
                        class="rounded-2xl shadow-xl w-full aspect-[4/3] object-cover"
                    />
                    <div class="absolute -bottom-6 -left-6 w-1/2 rounded-xl overflow-hidden shadow-lg">
                        <img 
                            src="https://avatars.mds.yandex.net/get-altay/905403/2a000001887bb59bd971abab9a0cd25efff3/XXL_height" 
                            alt="Переработанные материалы" 
                            class="w-full aspect-square object-cover"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Production Process Section -->
    <section class="section-padding bg-eco-50">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                    Производственный процесс
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                    Минимизация воздействия на каждом этапе
                </h2>
                <p class="text-eco-700">
                    Мы стремимся уменьшить экологический след на всех этапах производства — от выбора сырья до упаковки и доставки.
                </p>
            </div>
            
            <div class="relative">
                <!-- Timeline Line -->
                <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-eco-200"></div>
                
                <!-- Timeline Items -->
                <div class="relative space-y-12">
                    <!-- Step 1 -->
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="md:w-1/2 md:pr-12 md:text-right mb-6 md:mb-0">
                            <h3 class="text-xl font-semibold text-eco-900 mb-2">Источники сырья</h3>
                            <p class="text-eco-700">
                                Мы тщательно выбираем поставщиков, которые разделяют наши ценности устойчивого развития и справедливой торговли.
                            </p>
                        </div>
                        <div class="relative z-10 md:mx-4">
                            <div class="w-12 h-12 bg-eco-600 rounded-full flex items-center justify-center text-white">
                                1
                            </div>
                        </div>
                        <div class="md:w-1/2 md:pl-12">
                            <img 
                                src="https://avatars.mds.yandex.net/i?id=f09f3b89aa42b756d22245ed88e7ea98_l-10414202-images-thumbs&n=13" 
                                alt="Источники сырья" 
                                class="rounded-xl shadow-md w-full aspect-[3/2] object-cover"
                            />
                        </div>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="md:w-1/2 md:pr-12 md:text-right mb-6 md:mb-0 md:order-1">
                            <img 
                                src="https://images.unsplash.com/photo-1511688878353-3a2f5be94cd7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                alt="Производство" 
                                class="rounded-xl shadow-md w-full aspect-[3/2] object-cover"
                            />
                        </div>
                        <div class="relative z-10 md:mx-4">
                            <div class="w-12 h-12 bg-eco-600 rounded-full flex items-center justify-center text-white">
                                2
                            </div>
                        </div>
                        <div class="md:w-1/2 md:pl-12 md:order-0">
                            <h3 class="text-xl font-semibold text-eco-900 mb-2">Энергоэффективное производство</h3>
                            <p class="text-eco-700">
                                Наши производственные объекты работают на возобновляемых источниках энергии, а процессы оптимизированы для минимизации отходов.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="md:w-1/2 md:pr-12 md:text-right mb-6 md:mb-0">
                            <h3 class="text-xl font-semibold text-eco-900 mb-2">Экологичная упаковка</h3>
                            <p class="text-eco-700">
                                Мы используем минимум упаковки, изготовленной из переработанных или биоразлагаемых материалов, отказываясь от пластика где это возможно.
                            </p>
                        </div>
                        <div class="relative z-10 md:mx-4">
                            <div class="w-12 h-12 bg-eco-600 rounded-full flex items-center justify-center text-white">
                                3
                            </div>
                        </div>
                        <div class="md:w-1/2 md:pl-12">
                            <img 
                                src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                alt="Экологичная упаковка" 
                                class="rounded-xl shadow-md w-full aspect-[3/2] object-cover"
                            />
                        </div>
                    </div>
                    
                    <!-- Step 4 -->
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="md:w-1/2 md:pr-12 md:text-right mb-6 md:mb-0 md:order-1">
                            <img 
                                src="https://images.unsplash.com/photo-1578575437130-527eed3abbec?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                alt="Оптимизированная логистика" 
                                class="rounded-xl shadow-md w-full aspect-[3/2] object-cover"
                            />
                        </div>
                        <div class="relative z-10 md:mx-4">
                            <div class="w-12 h-12 bg-eco-600 rounded-full flex items-center justify-center text-white">
                                4
                            </div>
                        </div>
                        <div class="md:w-1/2 md:pl-12 md:order-0">
                            <h3 class="text-xl font-semibold text-eco-900 mb-2">Оптимизированная логистика</h3>
                            <p class="text-eco-700">
                                Мы планируем маршруты доставки для минимизации выбросов углекислого газа и компенсируем неизбежные выбросы через партнерские программы.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Eco Impact Section -->
    <section class="section-padding bg-white">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                    Наше влияние
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                    Измеримый эко-эффект
                </h2>
                <p class="text-eco-700">
                    Мы гордимся конкретными результатами наших экологических инициатив и постоянно работаем над их улучшением.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Impact 1 -->
                <div class="bg-eco-50 rounded-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-eco-100 rounded-full flex items-center justify-center mx-auto mb-6 text-eco-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 13 9 4 2 4 2 13"></path><path d="M13 18 13 4 22 4 22 18"></path><path d="M2 18 1 18 1 19 2 19"></path><circle cx="5.5" cy="15.5" r="2.5"></circle><path d="M9 19c0-1.105-.895-2-2-2h-3c-1.105 0-2 .895-2 2v1h7v-1Z"></path></svg>
                    </div>
                    <h3 class="text-3xl font-bold text-eco-900 mb-2">5+ млн</h3>
                    <p class="text-eco-700">
                        Пластиковых бутылок переработано для наших продуктов с 2020 года
                    </p>
                </div>
                
                <!-- Impact 2 -->
                <div class="bg-eco-50 rounded-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-eco-100 rounded-full flex items-center justify-center mx-auto mb-6 text-eco-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 13a9 9 0 0 0 9 9 9 9 0 0 0 9-9 9 9 0 0 0-9-9 8.9 8.9 0 0 0-4 .9"></path><path d="M17.41 11.91A9 9 0 0 0 2 12"></path><path d="M9.13 5.32A4 4 0 1 0 6 10H2"></path></svg>
                    </div>
                    <h3 class="text-3xl font-bold text-eco-900 mb-2">85%</h3>
                    <p class="text-eco-700">
                        Сокращение выбросов углекислого газа в нашем производстве
                    </p>
                </div>
                
                <!-- Impact 3 -->
                <div class="bg-eco-50 rounded-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-eco-100 rounded-full flex items-center justify-center mx-auto mb-6 text-eco-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="M12 22V2"></path></svg>
                    </div>
                    <h3 class="text-3xl font-bold text-eco-900 mb-2">100%</h3>
                    <p class="text-eco-700">
                        Всех наших продуктов сертифицированы экологическими организациями
                    </p>
                </div>
                
                <!-- Impact 4 -->
                <div class="bg-eco-50 rounded-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-eco-100 rounded-full flex items-center justify-center mx-auto mb-6 text-eco-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11a8 8 0 0 1 16 0"></path><path d="M4 9a10 10 0 0 1 11 6"></path><path d="M13 22 16 19 13 16"></path><path d="M18 22h-3"></path><path d="M18 13c.5-1.5 2-3 4-3"></path><path d="M16 6c3 0 4.5-1.5 5-3"></path><path d="M16 3h5v5"></path></svg>
                    </div>
                    <h3 class="text-3xl font-bold text-eco-900 mb-2">20+</h3>
                    <p class="text-eco-700">
                        Экологических инициатив поддержано по всему миру
                    </p>
                </div>
            </div>
            
            <div class="mt-16 text-center">
                <a 
                    href="#" 
                    class="inline-flex items-center px-6 py-3 rounded-full bg-eco-600 hover:bg-eco-700 text-white font-medium transition-colors"
                >
                    Скачать наш ежегодный отчет об устойчивом развитии
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Partners Section -->
    <section class="section-padding bg-eco-50">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                    Наши партнеры
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                    Вместе за устойчивое будущее
                </h2>
                <p class="text-eco-700">
                    Мы сотрудничаем с ведущими экологическими организациями и инициативами, которые помогают усилить наше положительное влияние.
                </p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- Partner logos would be placed here -->
                <div class="bg-white rounded-xl shadow-sm p-6 flex items-center justify-center h-24">
                    <div class="text-eco-900 font-semibold">Партнер 1</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex items-center justify-center h-24">
                    <div class="text-eco-900 font-semibold">Партнер 2</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex items-center justify-center h-24">
                    <div class="text-eco-900 font-semibold">Партнер 3</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex items-center justify-center h-24">
                    <div class="text-eco-900 font-semibold">Партнер 4</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex items-center justify-center h-24">
                    <div class="text-eco-900 font-semibold">Партнер 5</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex items-center justify-center h-24">
                    <div class="text-eco-900 font-semibold">Партнер 6</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex items-center justify-center h-24">
                    <div class="text-eco-900 font-semibold">Партнер 7</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex items-center justify-center h-24">
                    <div class="text-eco-900 font-semibold">Партнер 8</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="section-padding bg-eco-600 text-white">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">
                    Присоединяйтесь к движению за экологичный спорт
                </h2>
                <p class="text-xl opacity-90 mb-8">
                    Каждая покупка в EcoSport — это вклад в более устойчивое будущее для всех. Выбирайте ответственно!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('shop') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-white text-eco-600 font-medium transition-all hover:bg-eco-50 focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-eco-600 focus:outline-none">
                        Перейти в магазин
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('blog') }}" class="eco-btn inline-flex items-center justify-center">
                        Наш эко-блог
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Newsletter Section -->
    @include('components.newsletter')
@endsection
