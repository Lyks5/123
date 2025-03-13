@extends('layouts.app')

@section('title', 'О нас - EcoSport')

@section('content')
    <!-- Hero Section -->
    <section class="relative py-20 lg:py-32 overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-r from-eco-50/90 to-eco-50/70"></div>
            <img 
                src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" 
                alt="EcoSport команда" 
                class="w-full h-full object-cover"
            />
        </div>
        
        <div class="container-width relative px-4 sm:px-6 lg:px-8 pt-16">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-eco-900 mb-6">
                    Наша миссия и ценности
                </h1>
                <p class="text-xl text-eco-800 mb-8">
                    Мы создаем экологичное спортивное снаряжение для тех, кто заботится о планете так же, как и о своем здоровье.
                </p>
            </div>
        </div>
    </section>
    
    <!-- Our Story Section -->
    <section class="section-padding bg-white">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                        Наша история
                    </span>
                    <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                        От идеи к экологичному бренду
                    </h2>
                    <p class="text-eco-800 mb-6">
                        EcoSport начался с простой идеи: что если спортивное снаряжение может быть не только функциональным и качественным, но и экологически ответственным? В 2015 году группа энтузиастов-экологов и спортсменов объединилась, чтобы воплотить эту идею в жизнь.
                    </p>
                    <p class="text-eco-800 mb-6">
                        Сначала мы выпустили небольшую коллекцию товаров для йоги из переработанных материалов. Успех был моментальным. С тех пор мы выросли, но наши основные принципы остались неизменными - создавать продукты, которые служат как людям, так и планете.
                    </p>
                </div>
                
                <div class="relative">
                    <img 
                        src="https://images.unsplash.com/photo-1551698618-1dfe5d97d256?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                        alt="История EcoSport" 
                        class="rounded-2xl shadow-xl w-full aspect-[4/3] object-cover"
                    />
                    <div class="absolute -bottom-6 -left-6 w-1/2 rounded-xl overflow-hidden shadow-lg">
                        <img 
                            src="https://images.unsplash.com/photo-1542744095-fcf48d80b0fd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                            alt="Экологичные материалы" 
                            class="w-full aspect-square object-cover"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Team Section -->
    <section class="section-padding bg-eco-50">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                    Наша команда
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                    Люди, стоящие за EcoSport
                </h2>
                <p class="text-eco-700">
                    Наша команда объединяет профессионалов из разных областей: от дизайнеров и инженеров до экологов и спортсменов.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Team Member 1 -->
                <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                    <img 
                        src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                        alt="Анна Смирнова" 
                        class="w-full aspect-[3/4] object-cover object-center"
                    />
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-eco-900 mb-1">Анна Смирнова</h3>
                        <p class="text-eco-600 mb-4">Основатель и CEO</p>
                        <p class="text-eco-700">
                            Профессиональный спортсмен и эколог с 15-летним опытом в области устойчивого развития.
                        </p>
                    </div>
                </div>
                
                <!-- Team Member 2 -->
                <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                    <img 
                        src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                        alt="Иван Петров" 
                        class="w-full aspect-[3/4] object-cover object-center"
                    />
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-eco-900 mb-1">Иван Петров</h3>
                        <p class="text-eco-600 mb-4">Директор по продукту</p>
                        <p class="text-eco-700">
                            Инженер-технолог с опытом разработки инновационных материалов и экологически чистых производственных процессов.
                        </p>
                    </div>
                </div>
                
                <!-- Team Member 3 -->
                <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                    <img 
                        src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                        alt="Мария Козлова" 
                        class="w-full aspect-[3/4] object-cover object-center"
                    />
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-eco-900 mb-1">Мария Козлова</h3>
                        <p class="text-eco-600 mb-4">Руководитель устойчивого развития</p>
                        <p class="text-eco-700">
                            Эксперт по экологическим стандартам и сертификациям с опытом работы в международных организациях.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Values Section -->
    <section class="section-padding bg-white">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                    Наши ценности
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                    Принципы, которыми мы руководствуемся
                </h2>
                <p class="text-eco-700">
                    Эти ценности лежат в основе всего, что мы делаем - от разработки продуктов до взаимодействия с клиентами.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Value 1 -->
                <div class="bg-eco-50 rounded-2xl p-8">
                    <div class="w-16 h-16 bg-eco-100 rounded-full flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-eco-700" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"></path><path d="m7.5 4.27 9 5.15"></path><polyline points="3.29 7 12 12 20.71 7"></polyline><line x1="12" x2="12" y1="22" y2="12"></line><circle cx="18.5" cy="15.5" r="2.5"></circle><path d="M20.27 17.27 22 19"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-eco-900 mb-4">Инновации для устойчивости</h3>
                    <p class="text-eco-700">
                        Мы постоянно исследуем новые материалы и технологии, которые могут сделать наши продукты более экологичными без ущерба для качества.
                    </p>
                </div>
                
                <!-- Value 2 -->
                <div class="bg-eco-50 rounded-2xl p-8">
                    <div class="w-16 h-16 bg-eco-100 rounded-full flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-eco-700" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" x2="22" y1="12" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-eco-900 mb-4">Глобальное мышление</h3>
                    <p class="text-eco-700">
                        Мы понимаем, что наши действия имеют глобальные последствия. Мы стремимся к положительному влиянию на окружающую среду во всем мире.
                    </p>
                </div>
                
                <!-- Value 3 -->
                <div class="bg-eco-50 rounded-2xl p-8">
                    <div class="w-16 h-16 bg-eco-100 rounded-full flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-eco-700" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-eco-900 mb-4">Сообщество и прозрачность</h3>
                    <p class="text-eco-700">
                        Мы верим в открытость наших процессов и создание сообщества единомышленников, стремящихся к более здоровому и экологичному образу жизни.
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="section-padding bg-eco-600 text-white">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">
                    Присоединяйтесь к нашему движению
                </h2>
                <p class="text-xl opacity-90 mb-8">
                    EcoSport — это больше, чем просто бренд. Это движение людей, которые верят, что спорт и забота о планете могут идти рука об руку.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('shop') }}" class="eco-btn inline-flex items-center justify-center">
                        Наши продукты
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-white text-eco-600 font-medium transition-all hover:bg-eco-50 focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-eco-600 focus:outline-none">
                        Связаться с нами
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Newsletter Section -->
    @include('components.newsletter')
@endsection
