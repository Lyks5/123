<div class="group relative overflow-hidden rounded-2xl bg-white shadow-md transition-all duration-300 hover:shadow-lg">
    <div class="relative overflow-hidden aspect-[3/4]">
        <img 
            src="{{ $product->image }}" 
            alt="{{ $product->name }}" 
            class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105"
        />
        
        @if($product->isNew)
            <div class="absolute top-4 left-4 bg-eco-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                Новинка
            </div>
        @endif
        
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent flex items-end justify-center p-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <button class="w-full bg-eco-600 hover:bg-eco-700 text-white py-3 rounded-full transition-all">
                Добавить в корзину
            </button>
        </div>
    </div>
    
    <div class="p-4">
        <div class="text-xs text-gray-500 mb-1">{{ $product->category }}</div>
        <h3 class="font-medium text-gray-900 mb-2">{{ $product->name }}</h3>
        <div class="flex items-center justify-between">
            <p class="text-gray-900 font-semibold">{{ $product->price }} ₽</p>
        </div>
    </div>
</div>