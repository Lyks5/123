@foreach($products as $product)
    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
        <a href="{{ route('product.show', ['product' => $product->sku]) }}" class="block relative pb-[100%]">
            @if($product->images->isNotEmpty() && $product->images->first()->url)
                <img 
                    src="{{ $product->images->first()->url }}" 
                    alt="{{ $product->name }}"
                    class="absolute inset-0 w-full h-full object-cover"
                    loading="lazy"
                >
            @else
                <div class="absolute inset-0 bg-eco-100 flex items-center justify-center">
                    <span class="text-eco-600">Нет изображения</span>
                </div>
            @endif
            
            <!-- Eco Score Badge -->
            @if($product->eco_score)
                <div class="absolute top-2 right-2 bg-eco-100 text-eco-800 px-2 py-1 rounded-full text-sm font-medium">
                    {{ $product->eco_score }}
                </div>
            @endif
        </a>

        <div class="p-4">
            <!-- Category -->
            <div class="mb-2">
                <a href="{{ route('shop.category', $product->category) }}" class="text-sm text-eco-600 hover:text-eco-700">
                    {{ $product->category->name }}
                </a>
            </div>

            <!-- Title -->
            <h3 class="text-lg font-semibold text-eco-900 mb-2">
                <a href="{{ route('product.show', ['product' => $product->sku]) }}" class="hover:text-eco-600">
                    {{ $product->name }}
                </a>
            </h3>

            <!-- Price -->
            <div class="flex items-center justify-between mb-3">
                <span class="text-xl font-bold text-eco-900">{{ number_format($product->price, 0, '.', ' ') }} ₽</span>
            </div>

            <!-- Eco Features -->
            @if($product->ecoFeatures->isNotEmpty())
                <div class="flex flex-wrap gap-1 mb-3">
                    @foreach($product->ecoFeatures->take(2) as $feature)
                        <span class="inline-block bg-eco-50 text-eco-700 px-2 py-0.5 rounded-full text-xs">
                            {{ $feature->name }}
                        </span>
                    @endforeach
                    @if($product->ecoFeatures->count() > 2)
                        <span class="inline-block bg-eco-50 text-eco-700 px-2 py-0.5 rounded-full text-xs">
                            +{{ $product->ecoFeatures->count() - 2 }}
                        </span>
                    @endif
                </div>
            @endif

            <!-- Actions -->
            <div class="flex items-center gap-2">
                <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" 
                        class="w-full bg-eco-600 hover:bg-eco-700 text-white py-2 px-4 rounded-lg transition-colors"
                    >
                        В корзину
                    </button>
                </form>
                <form action="{{ route('wishlist.toggle') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" 
                        class="p-2 text-eco-600 hover:text-eco-700 border border-eco-200 rounded-lg hover:bg-eco-50 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endforeach

@if($products->isEmpty())
    <div class="col-span-full text-center py-12">
        <svg class="mx-auto h-12 w-12 text-eco-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-eco-900">Товары не найдены</h3>
        <p class="mt-1 text-sm text-eco-500">Попробуйте изменить параметры фильтрации</p>
    </div>
@endif