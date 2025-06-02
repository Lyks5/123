@props(['images', 'mainImage', 'product'])

<div class="product-gallery">
    <div class="relative aspect-[4/3] rounded-2xl overflow-hidden bg-gradient-to-tr from-eco-50 to-eco-200 shadow-inner border border-eco-100">
        @if($mainImage)
            <div class="relative w-full h-full">
                <img src="{{ $mainImage }}"
                    alt="{{ $product->name ?? 'Изображение продукта' }}"
                    class="w-full h-full object-contain transition-all duration-300 hover:scale-105">
            </div>
        @else
            <div class="w-full h-full flex items-center justify-center text-eco-100">
                <svg class="h-16 w-16" fill="none" stroke="currentColor">
                    <rect x="3" y="3" width="18" height="18" rx="2" />
                    <circle cx="8.5" cy="8.5" r="1.5" />
                    <polyline points="21 15 16 10 5 21" />
                </svg>
            </div>
        @endif
    </div>
</div>