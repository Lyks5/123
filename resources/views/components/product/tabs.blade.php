@props(['product', 'productAttributes', 'reviews', 'ecoFeatures'])

<div class="product-tabs-container rounded-2xl bg-white shadow-lg border border-eco-100 overflow-hidden"
     x-data="{ activeTab: 'description' }">
    <div class="product-tabs border-b border-eco-100" role="tablist">
        <div class="flex items-center justify-start gap-8 px-8 relative">
            <button type="button"
                    @click="activeTab = 'description'"
                    role="tab"
                    :class="{ 'text-eco-900 after:bg-eco-600': activeTab === 'description',
                             'text-eco-600 hover:text-eco-700': activeTab !== 'description' }"
                    class="relative py-4 text-base font-medium transition-colors duration-200 after:absolute after:bottom-0 after:left-0 after:right-0 after:h-0.5 after:transition-colors after:duration-200"
                    :aria-selected="activeTab === 'description'"
                    aria-controls="description-panel">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Описание
                </span>
            </button>

            <button type="button"
                    @click="activeTab = 'specifications'"
                    role="tab"
                    :class="{ 'text-eco-900 after:bg-eco-600': activeTab === 'specifications',
                             'text-eco-600 hover:text-eco-700': activeTab !== 'specifications' }"
                    class="relative py-4 text-base font-medium transition-colors duration-200 after:absolute after:bottom-0 after:left-0 after:right-0 after:h-0.5 after:transition-colors after:duration-200"
                    :aria-selected="activeTab === 'specifications'"
                    aria-controls="specifications-panel">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Характеристики
                </span>
            </button>

            <button type="button"
                    @click="activeTab = 'reviews'"
                    role="tab"
                    :class="{ 'text-eco-900 after:bg-eco-600': activeTab === 'reviews',
                             'text-eco-600 hover:text-eco-700': activeTab !== 'reviews' }"
                    class="relative py-4 text-base font-medium transition-colors duration-200 after:absolute after:bottom-0 after:left-0 after:right-0 after:h-0.5 after:transition-colors after:duration-200"
                    :aria-selected="activeTab === 'reviews'"
                    aria-controls="reviews-panel">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Отзывы
                    @if($reviews->count() > 0)
                        <span class="bg-eco-100 text-eco-600 px-2 py-0.5 rounded-full text-xs">
                            {{ $reviews->count() }}
                        </span>
                    @endif
                </span>
            </button>
        </div>
    </div>

    <div class="p-8">
        <!-- Описание -->
        <div x-show="activeTab === 'description'"
             role="tabpanel"
             class="prose text-eco-800 max-w-none">
            <h3 class="text-2xl font-semibold mb-4 text-eco-900">О продукте</h3>
            <div>{!! $product->description !!}</div>
            
            @if($ecoFeatures->isNotEmpty())
                <div class="mt-8">
                    <h4 class="text-lg font-semibold text-eco-900 mt-6 mb-3">Преимущества</h4>
                    <ul class="space-y-2">
                        @foreach($ecoFeatures as $feature)
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mt-1 mr-2" fill="none" stroke="currentColor">
                                    <polyline points="9 11 12 14 22 4"></polyline>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                </svg>
                                <span>{{ $feature->description }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Характеристики -->
        <div x-show="activeTab === 'specifications'"
             role="tabpanel"
             id="specifications-panel"
             class="product-tab-content"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0">
            <h3 class="text-2xl font-semibold mb-6 text-eco-900">Технические характеристики</h3>
            <div class="specifications-grid">
                @if(isset($productAttributes) && count($productAttributes) > 0)
                    @foreach($productAttributes as $group => $attributes)
                        <div class="specification-group">
                            <h4 class="specification-title capitalize">{{ $group }}</h4>
                            <div class="specification-list">
                                @foreach($attributes as $attribute)
                                    <div class="specification-item">
                                        <span class="text-eco-700">{{ $attribute->name }}</span>
                                        <span class="text-eco-900 font-medium">
                                            @if(isset($attribute->values))
                                                @if($group == 'color')
                                                    <div class="flex items-center gap-1.5">
                                                        @foreach($attribute->values as $val)
                                                            <div class="w-5 h-5 rounded-full border-2 border-white shadow-sm hover:scale-110 transition-transform"
                                                                 style="background-color: {{ $val->hex_color ?: '#eee' }};"
                                                                 title="{{ $val->value }}">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    {{ $attribute->values->pluck('value')->implode(', ') }}
                                                @endif
                                            @elseif(isset($attribute->value))
                                                {{ $attribute->value }}
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-2">
                        <div class="bg-eco-50/30 rounded-xl p-6 text-center">
                            <p class="text-eco-600">Характеристики не указаны для данного товара.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Отзывы -->
        <div x-show="activeTab === 'reviews'"
             role="tabpanel"
             id="reviews-panel"
             class="product-tab-content"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-semibold text-eco-900">Отзывы клиентов</h3>
                <button class="bg-eco-500 hover:bg-eco-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Написать отзыв
                </button>
            </div>
            @if($reviews->count() > 0)
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach($reviews as $review)
                        <div class="review-card">
                            <div class="review-header">
                                <div class="review-avatar">
                                    {{ substr($review->user->name ?? 'A', 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium text-eco-900">
                                        {{ $review->user->name ?? 'Анонимный пользователь' }}
                                    </div>
                                    <div class="text-sm text-eco-600">
                                        {{ $review->created_at->format('d.m.Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="review-rating" x-data="{ rating: {{ $review->rating }} }">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="rating-star"
                                         :class="{ 'text-yellow-400': {{ $i }} <= rating, 'text-gray-300': {{ $i }} > rating }"
                                         viewBox="0 0 20 20"
                                         fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <h4 class="text-lg font-semibold text-eco-900">{{ $review->title }}</h4>
                            <p class="text-eco-700 leading-relaxed">{{ $review->comment }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">{{ $reviews->links() }}</div>
            @else
                <div class="bg-eco-50/30 rounded-xl p-8 text-center">
                    <svg class="w-16 h-16 text-eco-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p class="text-eco-700 mb-4">
                        Отзывы пока отсутствуют. Будьте первым, кто оставит отзыв!
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>