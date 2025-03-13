<section class="section-padding bg-eco-800 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
            <defs>
                <pattern id="leaf-pattern" patternUnits="userSpaceOnUse" width="60" height="60" patternTransform="rotate(45)">
                    <path d="M10,10 Q20,5 30,15 T40,20 T10,30 T0,10 Z" fill="none" stroke="currentColor" stroke-width="1" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#leaf-pattern)" />
        </svg>
    </div>
    
    <div class="container-width relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-6 text-eco-300" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
                Присоединяйтесь к нашему эко-сообществу
            </h2>
            <p class="text-eco-200 mb-8 max-w-xl mx-auto">
                Подпишитесь, чтобы получать информацию об экологичных товарах, новостях об окружающей среде и эксклюзивных предложениях.
            </p>
            
            <form action="#" method="POST" class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                @csrf
                <div class="flex-1">
                    <input
                        type="email"
                        name="email"
                        placeholder="Введите ваш email"
                        class="w-full px-4 py-3 rounded-full border-2 border-eco-600 bg-eco-900/50 text-white placeholder:text-eco-400 focus:outline-none focus:ring-2 focus:ring-eco-500"
                        required
                    />
                </div>
                <button
                    type="submit"
                    class="px-6 py-3 bg-eco-500 hover:bg-eco-400 rounded-full text-white font-medium transition-colors flex items-center justify-center whitespace-nowrap"
                >
                    Подписаться
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>
            </form>
            
            <p class="text-eco-400 text-sm mt-4">
                Мы уважаем вашу приватность. Вы можете отписаться в любое время.
            </p>
        </div>
    </div>
</section>