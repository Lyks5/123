@extends('layouts.app')

@section('title', 'О нас - EcoSport')

@section('content')
    <!-- Hero Section -->
    <section class="relative py-24 lg:py-32 overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-r from-eco-50/90 to-eco-50/70"></div>
            <img 
                src="https://images.unsplash.com/photo-1551698618-1dfe5d97d256?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" 
                alt="О компании EcoSport" 
                class="w-full h-full object-cover"
            />
        </div>
        
        <div class="container-width relative px-4 sm:px-6 lg:px-8 pt-16">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl sm:text-5xl font-bold text-eco-900 mb-6 reveal">
                    О компании EcoSport
                </h1>
                <p class="text-xl text-eco-800 mb-8 reveal">
                    Мы создаем экологичные спортивные товары, которые не наносят вред планете,
                    но при этом отвечают самым высоким требованиям спортсменов.
                </p>
            </div>
        </div>
    </section>
    
    <!-- Our Story Section -->
    <section class="section-padding bg-white">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="reveal">
                    <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                        Наша история
                    </span>
                    <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                        Как все началось
                    </h2>
                    <div class="prose prose-lg text-eco-700 max-w-none">
                        <p>
                            EcoSport был основан в 2015 году группой единомышленников, объединенных любовью к спорту и заботой об окружающей среде. Мы начали с производства экологичных спортивных аксессуаров в небольшой мастерской.
                        </p>
                        <p>
                            Наблюдая за тем, как традиционная спортивная индустрия вносит вклад в загрязнение планеты, мы решили создать альтернативу — производство, которое учитывает не только потребности спортсменов, но и здоровье нашей планеты.
                        </p>
                        <p>
                            За несколько лет мы выросли из небольшого стартапа в компанию с международным присутствием, но наша основная миссия осталась неизменной: создавать высококачественные спортивные товары с минимальным воздействием на окружающую среду.
                        </p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 reveal">
                    <div class="overflow-hidden rounded-xl">
                        <img 
                            src="https://images.unsplash.com/photo-1551698618-1dfe5d97d256?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                            alt="Начало EcoSport" 
                            class="w-full h-full object-cover transition-transform hover:scale-105 duration-700"
                        />
                    </div>
                    <div class="overflow-hidden rounded-xl mt-8">
                        <img 
                            src="https://images.unsplash.com/photo-1542296332-2e4473faf563?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                            alt="Экологичные материалы" 
                            class="w-full h-full object-cover transition-transform hover:scale-105 duration-700"
                        />
                    </div>
                    <div class="overflow-hidden rounded-xl">
                        <img 
                            src="https://images.unsplash.com/photo-1545239351-ef35f43d514b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                            alt="Производственный процесс" 
                            class="w-full h-full object-cover transition-transform hover:scale-105 duration-700"
                        />
                    </div>
                    <div class="overflow-hidden rounded-xl mt-8">
                        <img 
                            src="https://images.unsplash.com/photo-1506126613408-eca07ce68773?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                            alt="Команда EcoSport" 
                            class="w-full h-full object-cover transition-transform hover:scale-105 duration-700"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Values Section -->
    <section class="section-padding bg-eco-50">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal">
                <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                    Наши ценности
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                    Что нами движет
                </h2>
                <p class="text-eco-700 text-lg">
                    В основе нашей философии лежат принципы, которые определяют каждое наше решение и действие.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl p-8 shadow-md reveal">
                    <div class="w-16 h-16 rounded-full bg-eco-100 flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-eco-700"><path d="M20.42 4.58a5.4 5.4 0 0 0-7.65 0l-.77.78-.77-.78a5.4 5.4 0 0 0-7.65 0C1.46 6.7 1.33 10.28 4 13l8 8 8-8c2.67-2.72 2.54-6.3.42-8.42z"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-eco-900 mb-4">Забота о планете</h3>
                    <p class="text-eco-700">
                        Мы стремимся минимизировать наше воздействие на окружающую среду на каждом этапе — от производства до упаковки и доставки.
                    </p>
                </div>
                
                <div class="bg-white rounded-xl p-8 shadow-md reveal">
                    <div class="w-16 h-16 rounded-full bg-eco-100 flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-eco-700"><circle cx="12" cy="12" r="10"></circle><path d="m4.93 4.93 14.14 14.14"></path></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-eco-900 mb-4">Прозрачность</h3>
                    <p class="text-eco-700">
                        Мы верим в честность и открытость во всем, что делаем. Мы публикуем информацию о нашем производстве и цепочке поставок.
                    </p>
                </div>
                
                <div class="bg-white rounded-xl p-8 shadow-md reveal">
                    <div class="w-16 h-16 rounded-full bg-eco-100 flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-eco-700"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-eco-900 mb-4">Инновации</h3>
                    <p class="text-eco-700">
                        Мы постоянно ищем новые материалы и технологии, которые помогут нам создавать более экологичные и эффективные продукты.
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Team Section -->
    <section class="section-padding bg-white">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal">
                <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                    Наша команда
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                    Люди, стоящие за EcoSport
                </h2>
                <p class="text-eco-700 text-lg">
                    Наша команда — это талантливые профессионалы, объединенные общей миссией и страстью к созданию экологичных спортивных товаров.
                </p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all reveal">
                    <div class="h-64 overflow-hidden">
                        <img 
                            src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                            alt="Александр Петров - Основатель и CEO" 
                            class="w-full h-full object-cover transition-transform hover:scale-105 duration-700"
                        />
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-eco-900 mb-1">Александр Петров</h3>
                        <p class="text-eco-600 mb-4">Основатель и CEO</p>
                        <p class="text-eco-700 text-sm">
                            Александр имеет 15-летний опыт в спортивной индустрии и страстное желание сделать спорт более экологичным.
                        </p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all reveal">
                    <div class="h-64 overflow-hidden">
                        <img 
                            src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                            alt="Екатерина Смирнова - Директор по устойчивому развитию" 
                            class="w-full h-full object-cover transition-transform hover:scale-105 duration-700"
                        />
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-eco-900 mb-1">Екатерина Смирнова</h3>
                        <p class="text-eco-600 mb-4">Директор по устойчивому развитию</p>
                        <p class="text-eco-700 text-sm">
                            Екатерина руководит нашими инициативами по устойчивому развитию и экологическим проектам.
                        </p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all reveal">
                    <div class="h-64 overflow-hidden">
                        <img 
                            src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                            alt="Михаил Иванов - Главный технолог" 
                            class="w-full h-full object-cover transition-transform hover:scale-105 duration-700"
                        />
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-eco-900 mb-1">Михаил Иванов</h3>
                        <p class="text-eco-600 mb-4">Главный технолог</p>
                        <p class="text-eco-700 text-sm">
                            Михаил отвечает за разработку новых материалов и производственных процессов.
                        </p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all reveal">
                    <div class="h-64 overflow-hidden">
                        <img 
                            src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                            alt="Анна Козлова - Директор по маркетингу" 
                            class="w-full h-full object-cover transition-transform hover:scale-105 duration-700"
                        />
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-eco-900 mb-1">Анна Козлова</h3>
                        <p class="text-eco-600 mb-4">Директор по маркетингу</p>
                        <p class="text-eco-700 text-sm">
                            Анна руководит маркетинговыми кампаниями и продвижением нашей миссии экологичного спорта.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

     <!-- Mission Section -->
    
    <!-- Testimonials Section -->
    <section class="section-padding bg-eco-50">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal">
                <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                    Отзывы клиентов
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                    Что говорят о нас
                </h2>
                <p class="text-eco-700 text-lg">
                    Мнения наших клиентов — лучшее подтверждение качества и экологичности нашей продукции.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl p-8 shadow-md reveal">
                    <div class="flex items-center mb-6">
                        <div class="text-eco-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"></path></svg>
                        </div>
                        <div class="text-eco-500 ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"></path></svg>
                        </div>
                        <div class="text-eco-500 ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"></path></svg>
                        </div>
                        <div class="text-eco-500 ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"></path></svg>
                        </div>
                        <div class="text-eco-500 ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"></path></svg>
                        </div>
                    </div>
                    <p class="text-eco-700 mb-6">
                        "Я в восторге от товаров EcoSport! Качество на высшем уровне, а осознание того, что мои покупки не наносят вред планете, дает дополнительное удовлетворение."
                    </p>
                    <div class="flex items-center">
                        <div class="mr-4">
                            <div class="w-12 h-12 rounded-full overflow-hidden">
                                <img 
                                    src="https://randomuser.me/api/portraits/women/45.jpg" 
                                    alt="Ольга К." 
                                    class="w-full h-full object-cover"
                                />
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-eco-900">Ольга К.</h4>
                            <p class="text-eco-600 text-sm">Клиент с 2020 года</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-8 shadow-md reveal">
                    <div class="flex items-center mb-6">
                        <div class="text-eco-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"></path></svg>
                        </div>
                        <div class="text-eco-500 ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"></path></svg>
                        </div>
                        <div class="text-eco-500 ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"></path></svg>
                        </div>
                        <div class="text-eco-500 ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"></path></svg>
                        </div>
                        <div class="text-eco-500 ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"></path></svg>
                        </div>
                    </div>
                    <p class="text-eco-700 mb-6">
                        "Как тренер, я всегда в поиске экологичных альтернатив для моих клиентов. EcoSport предлагает именно то, что нужно — высокое качество, экологичность и стиль."
                    </p>
                    <div class="flex items-center">
                        <div class="mr-4">
                            <div class="w-12 h-12 rounded-full overflow-hidden">
                                <img 
                                    src="https://randomuser.me/api/portraits/men/32.jpg" 
                                    alt="Дмитрий В." 
                                    class="w-full h-full object-cover"
                                />
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-eco-900">Дмитрий В.</h4>
                            <p class="text-eco-600 text-sm">Фитнес-тренер</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-8 shadow-md reveal">
                    <div class="flex items-center mb-6">
                        <div class="text-eco-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"></path></svg>
                        </div>
                        <div class="text-eco-500 ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"></path></svg>
                        </div>
                        <div class="text-eco-500 ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"></path></svg>
                        </div>
                        <div class="text-eco-500 ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"></path></svg>
                        </div>
                        <div class="text-eco-500 ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"></path></svg>
                        </div>
                    </div>
                    <p class="text-eco-700 mb-6">
                        "Великолепное качество и экологичный подход. Я давно искала бренд, который бы соответствовал моим ценностям. EcoSport — это именно то, что мне нужно!"
                    </p>
                    <div class="flex items-center">
                        <div class="mr-4">
                            <div class="w-12 h-12 rounded-full overflow-hidden">
                                <img 
                                    src="https://randomuser.me/api/portraits/women/68.jpg" 
                                    alt="Марина С." 
                                    class="w-full h-full object-cover"
                                />
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-eco-900">Марина С.</h4>
                            <p class="text-eco-600 text-sm">Эко-активист</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="section-padding bg-white">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="bg-eco-600 rounded-2xl p-8 md:p-12 overflow-hidden relative reveal">
                <div class="absolute inset-0 bg-[url('/img/dots-pattern.svg')] opacity-10"></div>
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="md:max-w-xl">
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                            Присоединяйтесь к эко-движению
                        </h2>
                        <p class="text-white/90 text-lg mb-6">
                            Начните свой путь к экологичному образу жизни вместе с нами. Выбирайте товары, которые не наносят вред планете.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('shop') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-white text-eco-700 font-medium hover:bg-eco-50 transition-colors">
                                В магазин
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('sustainability') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-full border border-white/30 text-white font-medium hover:bg-white/10 transition-colors">
                                Узнать об экологичности
                            </a>
                        </div>
                    </div>
                    <div class="relative w-full md:w-auto">
                        <img 
                            src="https://images.unsplash.com/photo-1551698618-1dfe5d97d256?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                            alt="Эко-движение" 
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