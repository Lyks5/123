@props(['product', 'ecoFeatures'])

<div class="product-info">
    <div class="flex flex-wrap gap-2 items-baseline mb-1">
        @if($product->category)
            <span class="text-xs font-medium uppercase tracking-wide text-eco-500 bg-eco-100 px-2 py-1 rounded-md">
                {{ $product->category->name }}
            </span>
        @endif
        
        @if($ecoFeatures->isNotEmpty())
            @foreach($ecoFeatures->take(1) as $feature)
                <span class="text-xs font-medium uppercase tracking-wide text-green-800 bg-green-100 px-2 py-1 rounded-md">
                    {{ $feature->name }}
                </span>
            @endforeach
        @endif
        
        @if($product->is_new)
            <span class="text-xs font-medium uppercase tracking-wide text-white bg-eco-500 px-2 py-1 rounded-md">
                Новинка
            </span>
        @endif
    </div>

    <h1 class="text-3xl md:text-4xl font-semibold text-eco-900 mb-2">{{ $product->name }}</h1>
    
    <div class="flex items-center gap-4 mb-4">
        @if($product->sale_price && $product->sale_price < $product->price)
            <div class="flex items-center gap-2">
                <span class="text-2xl font-bold text-eco-900">
                    {{ number_format($product->sale_price, 0, ',', ' ') }} ₽
                </span>
                <span class="text-lg line-through text-eco-400">
                    {{ number_format($product->price, 0, ',', ' ') }} ₽
                </span>
                <span class="bg-red-100 text-red-500 rounded-md text-xs px-2 py-1 font-semibold">
                    -{{ round((1 - $product->sale_price / $product->price) * 100) }}%
                </span>
            </div>
        @else
            <span class="text-2xl font-bold text-eco-900">
                {{ number_format($product->price, 0, ',', ' ') }} ₽
            </span>
        @endif
        
        <span class="ml-auto text-sm {{ $product->stock_quantity > 0 ? 'text-green-600' : 'text-red-400' }}">
            @if($product->stock_quantity > 10)
                В наличии
            @elseif($product->stock_quantity > 0)
                Осталось: {{ $product->stock_quantity }} шт.
            @else
                Нет в наличии
            @endif
        </span>
    </div>

    @if($ecoFeatures->isNotEmpty())
        <div class="mb-6">
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($ecoFeatures->take(3) as $feature)
                    <div
                        class="group relative flex items-center p-3 bg-green-50 border border-green-100 rounded-xl hover:shadow-md transition-all duration-200"
                        x-data="{ showValue: false }"
                        @mouseenter="showValue = true"
                        @mouseleave="showValue = false"
                    >
                        @if($feature->icon)
                            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg bg-green-100 text-green-600 mr-3">
                                {!! $feature->icon !!}
                            </div>
                        @endif
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-green-900 truncate">{{ $feature->name }}</p>
                            <p
                                class="text-xs text-green-600 mt-0.5"
                                x-show="showValue"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                            >
                                {{ $feature->value ?? $feature->description }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="space-y-4 mb-6">
        <div class="prose prose-sm text-eco-700 max-w-none">
            <p>{{ $product->short_description ?? \Illuminate\Support\Str::limit($product->description, 200) }}</p>
        </div>

        @if($product->attributes && $product->attributes->isNotEmpty())
            <div class="grid grid-cols-2 gap-4 mt-4">
                @foreach($product->attributes->take(4) as $attribute)
                    <div class="flex items-center space-x-2 text-sm">
                        <span class="text-eco-500">{{ $attribute->name }}:</span>
                        <span class="font-medium text-eco-900">{{ $attribute->value }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>