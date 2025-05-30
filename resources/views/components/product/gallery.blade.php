@props(['images', 'mainImage'])

@php
\Log::debug("Gallery component received images:", [
    'mainImage' => $mainImage,
    'images' => $images ? $images->pluck('url')->toArray() : []
]);
@endphp
<div class="product-gallery" x-data="{
    activeImage: @json($mainImage),
    zoom: false,
    zoomX: 0,
    zoomY: 0,
    isLoading: true,
    isMobile: window.innerWidth < 768,
    isTablet: window.innerWidth >= 768 && window.innerWidth < 1024,
    
    handleImageError($event) {
        console.error('[Gallery] Image failed to load:', $event.target.src);
        $event.target.src = '/images/placeholder-product.jpg';
    },
    
    handleTouchMove($event) {
        if (this.zoom && this.isMobile) {
            const touch = $event.touches[0];
            const rect = $event.target.getBoundingClientRect();
            this.zoomX = (touch.clientX - rect.left) / rect.width * 100;
            this.zoomY = (touch.clientY - rect.top) / rect.height * 100;
        }
    },
    
    generateSrcSet(imagePath) {
        const sizes = [400, 800, 1200];
        return sizes.map(function(size) {
            return imagePath.replace('.', '-' + size + '.') + ' ' + size + 'w';
        }).join(', ');
    }
}" x-init="window.addEventListener('resize', () => {
    isMobile = window.innerWidth < 768;
    isTablet = window.innerWidth >= 768 && window.innerWidth < 1024;
    console.log('[Gallery] Screen size changed:', {
        width: window.innerWidth,
        isMobile,
        isTablet
    });
}); console.log('[Gallery] Initial load:', {
    mainImage: activeImage,
    isMobile,
    isTablet
});" @mousemove="if (zoom) {
    zoomX = ($event.clientX - $el.getBoundingClientRect().left) / $el.offsetWidth * 100;
    zoomY = ($event.clientY - $el.getBoundingClientRect().top) / $el.offsetHeight * 100;
}">
    <div class="relative aspect-[4/3] rounded-2xl overflow-hidden bg-gradient-to-tr from-eco-50 to-eco-200 shadow-inner border border-eco-100">
@if($mainImage)
        <div class="relative w-full h-full">
            <div x-show="isLoading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 z-10">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-eco-400 border-t-transparent"></div>
            </div>
            <img :src="activeImage"
                alt="{{ $product->name ?? 'Изображение продукта' }}"
                class="w-full h-full object-cover transition-all duration-300"
                x-init="isLoading = true"
                @load="isLoading = false; console.log('[Gallery] Image loaded:', $event.target.src)"
                @error="isLoading = false; handleImageError($event)"
                :class="{
                    'scale-150 cursor-zoom-out': zoom && !isMobile,
                    'hover:scale-105 cursor-zoom-in': !zoom && !isMobile,
                    'cursor-move': zoom && isMobile
                }"
                :style="zoom ? 'transform-origin: ' + zoomX + '% ' + zoomY + '%' : ''"
                @click="if (!isMobile) zoom = !zoom"
                @touchmove.prevent="handleTouchMove($event)"
                loading="lazy"
                sizes="(max-width: 768px) 100vw, (max-width: 1024px) 75vw, 50vw"
                :srcset="generateSrcSet(activeImage)">
        </div>
@else
        <div class="w-full h-full flex items-center justify-center text-eco-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" stroke="currentColor">
                <rect x="3" y="3" width="18" height="18" rx="2" />
                <circle cx="8.5" cy="8.5" r="1.5" />
                <polyline points="21 15 16 10 5 21" />
            </svg>
        </div>
@endif
    </div>

@if($images && $images->count() > 1)
    <div class="product-gallery-thumbs flex space-x-2 mt-4 overflow-x-auto pb-2">
@foreach($images as $image)
        <button type="button"
            @click="activeImage = '{{ $image->url }}'; zoom = false"
            class="flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden border-2 transition-colors duration-200"
            :class="activeImage === '{{ $image->url }}' ? 'border-eco-400' : 'border-transparent hover:border-eco-200'">
            <img src="{{ $image->url }}"
                alt="{{ $image->alt_text }}"
                class="w-full h-full object-cover"
                loading="lazy"
                sizes="150px"
                srcset="{{ $image->url }} 150w, {{ str_replace('storage/', 'storage/thumbnails/', $image->url) }} 75w">
        </button>
@endforeach
    </div>
@endif
</div>
@endif