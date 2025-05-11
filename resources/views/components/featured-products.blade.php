<section id="featured-products" class="section-padding bg-gradient-to-b from-white to-eco-50">
    <div class="container-width">
        <div class="text-center max-w-xl mx-auto mb-16">
            <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                Эко-сертифицированные товары
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-4">
                Избранные экологичные товары
            </h2>
            <p class="text-eco-700">
                Откройте для себя наши лучшие товары, которые сочетают высокую производительность с экологичными материалами.
            </p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            @foreach ($featuredProducts as $index => $product)
                <div 
                    class="product-item opacity-0 animate-fade-in" 
                    style="animation-delay: {{ $index * 0.1 + 0.2 }}s;"
                >
                    @include('components.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
        
        <div class="mt-16 text-center">
            <a 
                href="{{ route('shop') }}" 
                class="eco-btn inline-flex items-center"
            >
                Все товары
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- Nature Inspiration Section -->
<section class="section-padding bg-gradient-to-b from-eco-50/30 to-white">
    <div class="container-width">
        <div class="text-center mb-16">
            <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                Наше вдохновение
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-4">
                Дизайн, вдохновлённый природой
            </h2>
            <p class="text-eco-700 max-w-2xl mx-auto">
                Наши продукты черпают вдохновение в идеальном балансе природы между функциональностью и красотой. 
                Мы изучаем природные системы, чтобы создавать экологичные решения, которые работают наилучшим образом.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl overflow-hidden border border-eco-100 shadow-md hover:shadow-xl transition-all hover:-translate-y-1 opacity-0 animate-fade-in" style="animation-delay: 0.2s;">
                <div class="h-48 overflow-hidden">
                    <img 
                        src="https://images.unsplash.com/photo-1472396961693-142e6e269027?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                        alt="Биомимикрия дизайн" 
                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                    />
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-eco-800 mb-2">Биомимикрия дизайн</h3>
                    <p class="text-eco-600">Наши продукты используют проверенные временем природные образцы и стратегии для создания экологичных решений.</p>
                </div>
            </div>

            <div class="bg-white rounded-xl overflow-hidden border border-eco-100 shadow-md hover:shadow-xl transition-all hover:-translate-y-1 opacity-0 animate-fade-in" style="animation-delay: 0.4s;">
                <div class="h-48 overflow-hidden">
                    <img 
                        src="https://images.unsplash.com/photo-1518005020951-eccb494ad742?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                        alt="Охрана океана" 
                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                    />
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-eco-800 mb-2">Охрана океана</h3>
                    <p class="text-eco-600">Мы сотрудничаем с инициативами по очистке океана и используем в нашей продукции переработанный океанический пластик.</p>
                </div>
            </div>

            <div class="bg-white rounded-xl overflow-hidden border border-eco-100 shadow-md hover:shadow-xl transition-all hover:-translate-y-1 opacity-0 animate-fade-in" style="animation-delay: 0.6s;">
                <div class="h-48 overflow-hidden">
                    <img 
                        src="https://images.unsplash.com/photo-1501854140801-50d01698950b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                        alt="Охрана лесов" 
                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                    />
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-eco-800 mb-2">Охрана лесов</h3>
                    <p class="text-eco-600">За каждый проданный продукт мы вносим вклад в проекты по восстановлению лесов по всему миру.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- New Arrivals Section -->
<section class="section-padding bg-white">
    <div class="container-width">
        <div class="flex flex-col md:flex-row justify-between items-center mb-12">
            <div>
                <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                    Новые поступления
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-eco-900">
                    Недавно добавленные товары
                </h2>
            </div>
            <a href="{{ route('shop') }}?sort=newest" class="mt-4 md:mt-0 inline-flex items-center text-eco-800 hover:text-eco-600 font-medium">
                Посмотреть все
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($newProducts->take(4) as $index => $product)
                <div class="opacity-0 animate-fade-in" style="animation-delay: {{ $index * 0.1 + 0.2 }}s;">
                    @include('components.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </div>
</section>