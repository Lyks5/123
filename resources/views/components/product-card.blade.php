<div class="group relative overflow-hidden rounded-2xl bg-white shadow-md transition-all duration-300 hover:shadow-lg block">
    <div class="relative overflow-hidden aspect-[3/4]">
        <img 
            src="{{ $product->image }}" 
            alt="{{ $product->name }}" 
            class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105"
        />
        @if ($product->is_new)
            <div class="absolute top-4 left-4 bg-eco-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                Новинка
            </div>
        @endif
        <div class="absolute top-4 right-4 bg-white/80 backdrop-blur-sm text-eco-900 text-xs font-semibold px-2 py-1 rounded-full">
            {{ $product->eco_feature }}
        </div>
        
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent flex items-end justify-center p-6 transition-opacity duration-300 opacity-0 group-hover:opacity-100">
            <form action="{{ route('cart.add') }}" method="POST" class="w-full">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button 
                    type="submit"
                    class="w-full bg-eco-600 hover:bg-eco-700 text-white py-3 rounded-full transition-all transform duration-300 flex items-center justify-center space-x-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" 
                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/>
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    <span>Добавить в корзину</span>
                </button>
            </form>
        </div>
    </div>
    
    <div class="p-4">
        <div class="text-xs text-muted-foreground mb-1">{{ $product->category->name }}</div>
        <a href="{{ route('product.show', ['product' => $product->sku]) }}" class="hover:text-eco-600 transition-colors">
            <h3 class="font-medium text-foreground mb-2 line-clamp-1">{{ $product->name }}</h3>
        </a>
        <div class="flex items-center justify-between">
            <p class="text-foreground font-semibold">{{ number_format($product->price, 2, ',', ' ') }} ₽</p>
        </div>
    </div>
</div>