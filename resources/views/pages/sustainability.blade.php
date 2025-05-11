@extends('layouts.app')

@section('title', 'Устойчивое развитие - EcoSport')

@section('content')
    <!-- Hero Section -->
    <section class="relative py-24 lg:py-32 overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-r from-eco-50/90 to-eco-50/70"></div>
            <img 
                src="https://images.unsplash.com/photo-1525695230005-efd074980869?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" 
                alt="Устойчивое развитие"
                class="w-full h-full object-cover"
            />
        </div>
        
        <div class="container-width relative px-4 sm:px-6 lg:px-8 pt-16">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl sm:text-5xl font-bold text-eco-900 mb-6 reveal">
                    Устойчивое развитие
                </h1>
                <p class="text-xl text-eco-800 mb-8 reveal">
                    Наш подход к экологически ответственному бизнесу и вклад в создание более устойчивого будущего.
                </p>
            </div>
        </div>
    </section>
    
    <!-- Our Approach Section -->
    <section class="section-padding bg-white">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="reveal">
                    <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                        Наш подход
                    </span>
                    <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                        Устойчивость в каждом аспекте
                    </h2>
                    <div class="prose prose-lg text-eco-700 max-w-none">
                        <p>
                            В EcoSport мы понимаем, что устойчивое развитие — это не просто модное словосочетание, а основа нашего бизнеса. Мы стремимся интегрировать экологические принципы во все аспекты нашей деятельности.
                        </p>
                        <p>
                            Наш подход к устойчивому развитию основан на трех ключевых принципах:
                        </p>
                        <ul>
                            <li>Минимизация негативного воздействия на окружающую среду</li>
                            <li>Социальная ответственность и справедливая торговля</li>
                            <li>Инновации для создания более экологичных продуктов</li>
                        </ul>
                        <p>
                            Эти принципы направляют каждое наше решение — от выбора поставщиков до разработки новых продуктов и упаковки.
                        </p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 reveal">
                    <div class="overflow-hidden rounded-xl">
                        <img 
                            src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                            alt="Экологичные материалы" 
                            class="w-full h-full object-cover transition-transform hover:scale-105 duration-700"
                        />
                    </div>
                    <div class="overflow-hidden rounded-xl mt-8">
                        <img 
                            src="https://images.unsplash.com/photo-1601653469202-1c5a220c2788?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                            alt="Экологичное производство" 
                            class="w-full h-full object-cover transition-transform hover:scale-105 duration-700"
                        />
                    </div>
                    <div class="overflow-hidden rounded-xl">
                        <img 
                            src="https://images.unsplash.com/photo-1550359113-e300c3437d6a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                            alt="Социальная ответственность" 
                            class="w-full h-full object-cover transition-transform hover:scale-105 duration-700"
                        />
                    </div>
                    <div class="overflow-hidden rounded-xl mt-8">
                        <img 
                            src="https://images.unsplash.com/photo-1531171673193-f06bd4f6890e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                            alt="Инновации" 
                            class="w-full h-full object-cover transition-transform hover:scale-105 duration-700"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Materials Section -->
    <section class="section-padding bg-eco-50">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal">
                <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                    Материалы
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                    Экологичные материалы
                </h2>
                <p class="text-eco-700 text-lg">
                    Мы тщательно выбираем материалы для наших продуктов, отдавая предпочтение тем, которые оказывают минимальное воздействие на окружающую среду.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl p-8 shadow-md reveal">
                    <div class="w-16 h-16 rounded-full bg-eco-100 flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-eco-700"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-eco-900 mb-4">Переработанный полиэстер</h3>
                    <p class="text-eco-700">
                        Наша спортивная одежда изготовлена из полиэстера, полученного из переработанных пластиковых бутылок, что помогает сократить количество отходов.
                    </p>
                </div>
                
                <div class="bg-white rounded-xl p-8 shadow-md reveal">
                    <div class="w-16 h-16 rounded-full bg-eco-100 flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-eco-700"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-eco-900 mb-4">Органический хлопок</h3>
                    <p class="text-eco-700">
                        Мы используем органический хлопок, выращенный без пестицидов и химических удобрений, что сохраняет здоровье почвы и биоразнообразие.
                    </p>
                </div>
                
                <div class="bg-white rounded-xl p-8 shadow-md reveal">
                    <div class="w-16 h-16 rounded-full bg-eco-100 flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-eco-700"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-eco-900 mb-4">Океанический пластик</h3>
                    <p class="text-eco-700">
                        Для некоторых наших товаров мы используем переработанный пластик, собранный из океана, помогая решать проблему загрязнения морской среды.
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Production Process Section -->
    <section class="section-padding bg-white">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="order-2 lg:order-1 reveal">
                    <div class="rounded-2xl overflow-hidden">
                        <img 
                            src="https://images.unsplash.com/photo-1581091014534-676b835a30e3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80" 
                            alt="Экологичное производство" 
                            class="w-full h-auto"
                        />
                    </div>
                </div>
                
                <div class="order-1 lg:order-2 reveal">
                    <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                        Производство
                    </span>
                    <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                        Экологичный процесс производства
                    </h2>
                    <div class="space-y-4 text-eco-700">
                        <p>
                            Наше производство основано на принципах устойчивого развития и минимизации воздействия на окружающую среду:
                        </p>
                        
                        <div class="flex items-start">
                            <div class="mr-4 text-eco-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m4.93 4.93 14.14 14.14"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-eco-900">Возобновляемая энергия</h3>
                                <p class="text-eco-700">Наши производственные объекты на 80% работают на возобновляемых источниках энергии, таких как солнечная и ветровая.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="mr-4 text-eco-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m4.93 4.93 14.14 14.14"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-eco-900">Сокращение отходов</h3>
                                <p class="text-eco-700">Мы внедрили систему безотходного производства, где 95% отходов перерабатываются или повторно используются.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="mr-4 text-eco-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m4.93 4.93 14.14 14.14"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-eco-900">Экономия воды</h3>
                                <p class="text-eco-700">Наши инновационные технологии позволяют сократить потребление воды на 80% по сравнению с традиционными методами производства.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Environmental Initiatives Section -->
    <section class="section-padding bg-eco-50">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal">
                <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                    Наши инициативы
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                    Экологические инициативы
                </h2>
                <p class="text-eco-700 text-lg">
                    Мы активно участвуем в различных экологических проектах, направленных на сохранение и восстановление окружающей среды.
                </p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($initiatives as $initiative)
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all reveal">
                    <div class="h-48 overflow-hidden">
                        <img 
                            src="{{ $initiative->image_url }}" 
                            alt="{{ $initiative->title }}" 
                            class="w-full h-full object-cover transition-transform hover:scale-105 duration-700"
                        />
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-eco-900 mb-2">{{ $initiative->title }}</h3>
                        <p class="text-eco-700 mb-4">
                            {{ Str::limit($initiative->description, 120) }}
                        </p>
                        <div class="flex items-center text-eco-600 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                            {{ $initiative->created_at->format('d.m.Y') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    
    <!-- Impact Metrics Section -->
    <section class="section-padding bg-white">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal">
                <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                    Наш вклад
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                    Измеримое влияние
                </h2>
                <p class="text-eco-700 text-lg">
                    Мы отслеживаем наше воздействие и гордимся конкретными результатами, которых нам удалось достичь.
                </p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all text-center reveal">
                    <div class="w-16 h-16 rounded-full bg-eco-100 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-eco-700"><path d="M21.1 8.4c-.2-.4-.5-.8-.9-1.1-1-.7-2.4-.6-3.2.3L16 9l-6.1 6.1-1.6-1.6c-.5-.5-1.2-.5-1.7 0l-3.4 3.4c-.5.5-.5 1.3 0 1.7l3.4 3.4h.8"/><path d="m22 22-5-5"/><path d="m17 22 5-5"/><path d="M22.2 6.5c-.3-1.6-1.7-2.9-3.5-2.8-1.7.1-3 1.4-3.2 3-.2 2 1.2 3.7 3.1 4 1.1.1 2.2-.2 2.9-.9"/></svg>
                    </div>
                    <h3 class="text-4xl font-bold text-eco-900 mb-1">5+</h3>
                    <p class="text-eco-700">миллионов пластиковых бутылок переработано</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all text-center reveal">
                    <div class="w-16 h-16 rounded-full bg-eco-100 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-eco-700"><path d="M12 22c4.97 0 9-2.69 9-6s-4.03-6-9-6-9 2.69-9 6 4.03 6 9 6Z"/><path d="M12 16v6"/><path d="M9 18v4"/><path d="M15 18v4"/><path d="M12 13V2"/><path d="M10 7V2"/><path d="M14 7V2"/></svg>
                    </div>
                    <h3 class="text-4xl font-bold text-eco-900 mb-1">10K+</h3>
                    <p class="text-eco-700">деревьев посажено</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all text-center reveal">
                    <div class="w-16 h-16 rounded-full bg-eco-100 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-eco-700"><path d="M22 12A10 10 0 1 1 12 2"/><path d="M22 12A10 10 0 0 0 12 2"/><path d="M2 12h10"/><path d="M12 2v10"/><path d="m13 7.4 2.7-2.7"/><path d="M13 16.6 15.7 14"/><path d="m13 7.4-2.7-2.7"/><path d="M13 16.6 10.3 14"/></svg>
                    </div>
                    <h3 class="text-4xl font-bold text-eco-900 mb-1">80%</h3>
                    <p class="text-eco-700">сокращение потребления воды</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all text-center reveal">
                    <div class="w-16 h-16 rounded-full bg-eco-100 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-eco-700"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="m16 13-3.5 3.5-2-2L8 17"/></svg>
                    </div>
                    <h3 class="text-4xl font-bold text-eco-900 mb-1">95%</h3>
                    <p class="text-eco-700">переработка производственных отходов</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Certifications Section -->
    <section class="section-padding bg-eco-50">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal">
                <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                    Сертификация
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                    Наши экологические сертификаты
                </h2>
                <p class="text-eco-700 text-lg">
                    Мы гордимся признанием наших усилий в области устойчивого развития от ведущих сертификационных организаций.
                </p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl p-6 flex flex-col items-center justify-center shadow-md hover:shadow-xl transition-all reveal">
                    <div class="w-20 h-20 mb-4">
                        <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" class="w-full h-full text-eco-700">
                            <circle cx="50" cy="50" r="45" stroke="currentColor" stroke-width="2" fill="none"/>
                            <path d="M30 52L45 67L70 37" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-eco-900 mb-2 text-center">Global Recycled Standard</h3>
                </div>
                
                <div class="bg-white rounded-xl p-6 flex flex-col items-center justify-center shadow-md hover:shadow-xl transition-all reveal">
                    <div class="w-20 h-20 mb-4">
                        <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" class="w-full h-full text-eco-700">
                            <path d="M50 15 L85 35 L85 65 L50 85 L15 65 L15 35 Z" stroke="currentColor" stroke-width="2" fill="none"/>
                            <path d="M50 15 L50 45 M50 45 L85 65 M50 45 L15 65 M50 45 L50 85" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-eco-900 mb-2 text-center">Bluesign Certified</h3>
                </div>
                
                <div class="bg-white rounded-xl p-6 flex flex-col items-center justify-center shadow-md hover:shadow-xl transition-all reveal">
                    <div class="w-20 h-20 mb-4">
                        <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" class="w-full h-full text-eco-700">
                            <path d="M50 15 C65 15 80 25 85 40 C90 55 80 75 65 82 C50 90 30 85 20 70 C10 55 15 35 30 25 C35 20 45 15 50 15 Z" stroke="currentColor" stroke-width="2" fill="none"/>
                            <path d="M40 40 C45 35 55 35 60 40 C65 45 65 55 60 60 C55 65 45 65 40 60 C35 55 35 45 40 40 Z" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-eco-900 mb-2 text-center">GOTS Certified</h3>
                </div>
                
                <div class="bg-white rounded-xl p-6 flex flex-col items-center justify-center shadow-md hover:shadow-xl transition-all reveal">
                    <div class="w-20 h-20 mb-4">
                        <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" class="w-full h-full text-eco-700">
                            <circle cx="50" cy="50" r="35" stroke="currentColor" stroke-width="2" fill="none"/>
                            <path d="M30 50 L45 65 L70 35" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M50 15 L50 25 M85 50 L75 50 M50 85 L50 75 M15 50 L25 50" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-eco-900 mb-2 text-center">Carbon Neutral</h3>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Goals Section -->
    <section class="section-padding bg-white">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="reveal">
                    <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                        Наши цели
                    </span>
                    <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                        Наши цели до 2030 года
                    </h2>
                    <div class="space-y-6 text-eco-700">
                        <div class="flex items-start">
                            <div class="mr-4 text-eco-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-eco-900">Углеродная нейтральность</h3>
                                <p class="text-eco-700">Достичь полной углеродной нейтральности во всех наших операциях и цепочках поставок.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="mr-4 text-eco-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-eco-900">Циркулярность</h3>
                                <p class="text-eco-700">Внедрить принципы циркулярной экономики во все наши продукты, обеспечив их повторное использование или полную переработку.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="mr-4 text-eco-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-eco-900">Сокращение отходов</h3>
                                <p class="text-eco-700">Полностью устранить отходы, отправляемые на свалки, и добиться 100% переработки или компостирования всех материалов.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="mr-4 text-eco-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-eco-900">Водосбережение</h3>
                                <p class="text-eco-700">Внедрить замкнутые системы водоснабжения на всех производственных объектах, сведя к минимуму потребление свежей воды.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="reveal">
                    <div class="rounded-2xl overflow-hidden">
                        <img 
                            src="https://images.unsplash.com/photo-1584324778773-ad5f8426e877?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80" 
                            alt="Наши цели до 2030 года" 
                            class="w-full h-auto"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="section-padding bg-eco-50">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="bg-eco-600 rounded-2xl p-8 md:p-12 overflow-hidden relative reveal">
                <div class="absolute inset-0 bg-[url('/img/dots-pattern.svg')] opacity-10"></div>
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="md:max-w-xl">
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                            Присоединяйтесь к нашей миссии
                        </h2>
                        <p class="text-white/90 text-lg mb-6">
                            Вместе мы можем сделать спорт более экологичным. Выбирайте устойчивые продукты и присоединяйтесь к движению за более зеленое будущее.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('shop') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-white text-eco-700 font-medium hover:bg-eco-50 transition-colors">
                                В магазин
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-full border border-white/30 text-white font-medium hover:bg-white/10 transition-colors">
                                Связаться с нами
                            </a>
                        </div>
                    </div>
                    <div class="relative w-full md:w-auto">
                        <img 
                            src="https://images.unsplash.com/photo-1605000797499-95a51c5269ae?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                            alt="Присоединяйтесь к нашей миссии" 
                            class="w-full md:w-64 lg:w-80 h-auto rounded-xl"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Newsletter Section -->
    @include('components.newsletter')
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation for elements with .reveal class when they enter viewport
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });
        
        document.querySelectorAll('.reveal').forEach(el => {
            observer.observe(el);
        });
    });
</script>
@endpush