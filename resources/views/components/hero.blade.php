<div class="relative min-h-screen flex items-center overflow-hidden pt-16">
    <div class="absolute inset-0 -z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-eco-50/90 via-eco-100/70 to-transparent"></div>
        <img 
            src="https://images.unsplash.com/photo-1551698618-1dfe5d97d256?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" 
            alt="Eco-friendly sports" 
            class="w-full h-full object-cover"
        />
    </div>
    
    <div class="container-width relative z-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl opacity-0 animate-fade-in" style="animation-delay: 0.2s;">
            <div class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-6">
                Экологичные спортивные товары
            </div>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold leading-tight text-eco-900 mb-6">
                Товары для спорта, <br /> 
                <span class="gradient-text">безопасные для планеты</span>
            </h1>
            <p class="text-lg text-eco-800 mb-8 max-w-lg">
                Наше экологичное спортивное оборудование сочетает высокую производительность с заботой об окружающей среде. Сделано из переработанных материалов с учетом экологических стандартов.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('shop') }}" class="eco-btn inline-flex items-center justify-center">
                    В магазин
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
                <a href="#about" class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-white/80 backdrop-blur-sm border border-eco-200 text-eco-800 font-medium transition-all hover:bg-white focus:ring-2 focus:ring-eco-200 focus:outline-none">
                    Наша миссия
                </a>
            </div>
        </div>
    </div>
    
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <a href="#featured-products" class="flex flex-col items-center text-eco-800 opacity-80 hover:opacity-100 transition-opacity">
            <span class="text-sm mb-2">Подробнее</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down"><path d="m6 9 6 6 6-6"/></svg>
        </a>
    </div>
</div>

<!-- Eco Features Banner -->
<div class="relative -mt-16 z-10 px-4">
    <div class="container-width mx-auto">
        <div class="bg-gradient-to-r from-eco-50 to-earth-50 rounded-xl shadow-lg overflow-hidden border border-eco-100">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 divide-x divide-eco-100">
                <div class="p-6 flex flex-col items-center text-center hover:bg-white/50 transition-colors duration-300 opacity-0 animate-fade-in" style="animation-delay: 0.1s;">
                    <div class="mb-3 text-eco-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-leaf"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                    </div>
                    <h3 class="text-sm font-medium text-eco-800">Экологичные материалы</h3>
                </div>
                <div class="p-6 flex flex-col items-center text-center hover:bg-white/50 transition-colors duration-300 opacity-0 animate-fade-in" style="animation-delay: 0.2s;">
                    <div class="mb-3 text-eco-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-recycle"><path d="M7 19H4.815a1.83 1.83 0 0 1-1.57-.881 1.785 1.785 0 0 1-.004-1.784L7.196 9.5"/><path d="M11 19h8.203a1.83 1.83 0 0 0 1.556-.89 1.784 1.784 0 0 0 0-1.775l-1.226-2.12"/><path d="m14 16-3 3 3 3"/><path d="M8.293 13.596 4.5 9.828a1.83 1.83 0 0 1-.198-2.557 1.839 1.839 0 0 1 1.347-.578H10.5"/><path d="m7 10 3-3-3-3"/><path d="M12.734 9.27 16.295 5.9a1.83 1.83 0 0 1 2.516-.201 1.83 1.83 0 0 1 .578 1.347v3.548"/><path d="m17 7 3 3 3-3"/></svg>
                    </div>
                    <h3 class="text-sm font-medium text-eco-800">Переработанные материалы</h3>
                </div>
                <div class="p-6 flex flex-col items-center text-center hover:bg-white/50 transition-colors duration-300 opacity-0 animate-fade-in" style="animation-delay: 0.3s;">
                    <div class="mb-3 text-eco-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sprout"><path d="M7 20h10"/><path d="M10 20c5.5-2.5.8-10.5 4-10"/><path d="M9.5 9.4c1.1.8 1.8 2.2 2.3 3.7-2 .4-3.5.4-4.8-.3-1.2-.6-2-1.9-2-4.2V4"/><path d="M14.1 19.1C15 19.1 15 19 15.7 17.7c.7-1.4 1.5-3.5 1.5-5.8 0-4.3-2.2-7-5.5-8.8-2.1-1.1-5-.9-5 1.9v4.2"/></svg>
                    </div>
                    <h3 class="text-sm font-medium text-eco-800">Биоразлагаемые</h3>
                </div>
                <div class="p-6 flex flex-col items-center text-center hover:bg-white/50 transition-colors duration-300 opacity-0 animate-fade-in" style="animation-delay: 0.4s;">
                    <div class="mb-3 text-eco-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>
                    <h3 class="text-sm font-medium text-eco-800">Этичное производство</h3>
                </div>
                <div class="p-6 flex flex-col items-center text-center hover:bg-white/50 transition-colors duration-300 opacity-0 animate-fade-in" style="animation-delay: 0.5s;">
                    <div class="mb-3 text-eco-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sun"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
                    </div>
                    <h3 class="text-sm font-medium text-eco-800">Энергоэффективные</h3>
                </div>
                <div class="p-6 flex flex-col items-center text-center hover:bg-white/50 transition-colors duration-300 opacity-0 animate-fade-in" style="animation-delay: 0.6s;">
                    <div class="mb-3 text-eco-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg>
                    </div>
                    <h3 class="text-sm font-medium text-eco-800">Перерабатываемые</h3>
                </div>
            </div>
        </div>
    </div>
</div>