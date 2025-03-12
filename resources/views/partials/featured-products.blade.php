<!-- Featured Products -->
<section id="featured" class="py-16 bg-gradient-to-b from-white to-eco-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-16">
            <div class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                Экологичные товары
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-4">
                Популярные товары
            </h2>
            <p class="text-eco-700">
                Откройте для себя наши лучшие предложения, которые сочетают в себе производительность и экологичность.
            </p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            @foreach($featuredProducts as $product)
                <div class="opacity-0 animate-fade-in" style="animation-delay: {{ $loop->index * 0.1 + 0.2 }}s">
                    @include('partials.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
        
        <div class="mt-16 text-center">
            <a 
                href="/shop" 
                class="inline-flex items-center text-eco-800 font-medium hover:text-eco-600 transition-colors"
            >
                Смотреть все товары
                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </div>
</section>