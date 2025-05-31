@props(['relatedProducts'])

<div class="related-products my-16 overflow-hidden">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold text-eco-900 flex items-center gap-3">
            <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            Похожие товары
        </h2>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 relative">
        @foreach($relatedProducts as $product)
            <div class="product-card group transform transition-all duration-300 hover:-translate-y-1">
                <div class="relative rounded-2xl overflow-hidden bg-gradient-to-br from-white to-eco-50/30 border border-eco-100 shadow-lg hover:shadow-xl hover:shadow-eco-100/20 hover:border-eco-200 transition-all duration-300">
                    <!-- Изображение -->
                    <a href="{{ route('product.show', ['product' => $product->sku]) }}" class="block aspect-[4/3] relative group overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10"></div>
                        @if($product->primary_image)
                            <img src="{{ asset('storage/' . $product->primary_image->image_path) }}"
                                 alt="{{ $product->primary_image->alt_text }}"
                                 class="w-full h-full object-cover transition duration-500 group-hover:scale-110"
                                 loading="lazy" />
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-eco-50/50 to-eco-100/50">
                                <svg class="h-16 w-16 text-eco-200 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif

                        <div class="absolute top-3 left-3 flex gap-2 z-20">
                            @if($product->is_new)
                                <span class="text-xs font-medium uppercase tracking-wide text-white bg-gradient-to-r from-eco-600 to-eco-500 px-3 py-1 rounded-full shadow-lg backdrop-blur-sm">
                                    Новинка
                                </span>
                            @endif
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="text-xs font-medium uppercase tracking-wide text-white bg-red-500 px-3 py-1 rounded-full shadow-lg backdrop-blur-sm">
                                    -{{ round((1 - $product->sale_price / $product->price) * 100) }}%
                                </span>
                            @endif
                        </div>
                    </a>

                    <!-- Информация -->
                    <div class="p-5">
                        @if($product->category)
                            <div class="text-sm text-eco-600 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                {{ $product->category->name }}
                            </div>
                        @endif
                        
                        <a href="{{ route('product.show', ['product' => $product->sku]) }}" class="group/title">
                            <h3 class="text-lg font-medium text-eco-900 mb-3 group-hover/title:text-eco-600 transition-colors">
                                {{ $product->name }}
                            </h3>
                        </a>

                        <div class="flex flex-col gap-3">
                            <div class="flex items-baseline gap-3">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <span class="text-xl font-bold text-eco-900">
                                        {{ number_format($product->sale_price, 0, ',', ' ') }} ₽
                                    </span>
                                    <span class="text-sm line-through text-eco-400">
                                        {{ number_format($product->price, 0, ',', ' ') }} ₽
                                    </span>
                                @else
                                    <span class="text-xl font-bold text-eco-900">
                                        {{ number_format($product->price, 0, ',', ' ') }} ₽
                                    </span>
                                @endif
                            </div>
                            
                            <div class="flex items-center gap-2">
                                @if($product->stock_quantity > 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $product->stock_quantity > 10 ? 'bg-gradient-to-r from-green-100 to-green-50 text-green-700' : 'bg-gradient-to-r from-yellow-100 to-yellow-50 text-yellow-700' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $product->stock_quantity > 10 ? 'bg-green-500' : 'bg-yellow-500' }}"></span>
                                        {{ $product->stock_quantity > 10 ? 'В наличии' : 'Мало' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gradient-to-r from-red-100 to-red-50 text-red-700 text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Нет в наличии
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="absolute inset-x-0 bottom-0 p-5 pt-0 opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                        <button class="w-full bg-gradient-to-r from-eco-600 to-eco-500 hover:from-eco-700 hover:to-eco-600 text-white py-2 px-4 rounded-lg shadow-lg shadow-eco-500/20 hover:shadow-eco-600/30 transform hover:scale-[1.02] transition-all duration-300 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            В корзину
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>