@props(['product', 'productAttributes', 'reviews', 'ecoFeatures'])

<div class="product-tabs-container rounded-2xl bg-white shadow-lg border border-eco-100 overflow-hidden"
     x-data="{ activeTab: 'specifications' }">
    {{-- Табы --}}
    <div class="product-tabs border-b border-eco-100" role="tablist">
        <div class="flex items-center justify-start gap-8 px-8 relative">
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

    {{-- Контент --}}
    <div class="p-8">
        {{-- Характеристики --}}
        <div x-show="activeTab === 'specifications'"
             role="tabpanel"
             class="specifications-content">
            @if(isset($productAttributes) && count($productAttributes) > 0)
                <div class="space-y-6">
                    @foreach($productAttributes as $group => $attributes)
                        <div class="bg-eco-50/30 rounded-xl p-6">
                            <h4 class="text-lg font-medium text-eco-800 mb-4 capitalize">{{ $group }}</h4>
                            <div class="space-y-4">
                                @foreach($attributes as $attribute)
                                    <div class="flex items-center justify-between">
                                        <span class="text-eco-600">{{ $attribute->name }}</span>
                                        <div class="flex items-center gap-2">
                                            @foreach($attribute->values as $value)
                                                @if($group === 'color')
                                                    <div class="w-6 h-6 rounded-full border-2 border-white shadow-sm hover:scale-110 transition-transform"
                                                         style="background-color: {{ $value->hex_color ?: '#eee' }};"
                                                         title="{{ $value->value }}">
                                                    </div>
                                                @else
                                                    <span class="font-medium text-eco-900">{{ $value->value }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-eco-50/30 rounded-xl p-8 text-center">
                    <svg class="w-16 h-16 text-eco-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-eco-600">Характеристики не указаны для данного товара.</p>
                </div>
            @endif
        </div>

        {{-- Описание --}}
        <div x-show="activeTab === 'description'"
             role="tabpanel"
             class="prose prose-lg text-eco-800 max-w-none">
            <div class="text-lg leading-relaxed">{!! $product->description !!}</div>
            
            @if($ecoFeatures->isNotEmpty())
                <div class="mt-8">
                    <h4 class="text-xl font-semibold text-eco-900 mb-4">Эко-преимущества</h4>
                    <ul class="space-y-3">
                        @foreach($ecoFeatures as $feature)
                            <li class="flex items-start bg-eco-50/50 p-4 rounded-lg">
                                <svg class="h-6 w-6 text-green-600 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-eco-800">{{ $feature->description }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Отзывы --}}
        <div x-show="activeTab === 'reviews'"
             role="tabpanel"
             class="reviews-content">
            <div class="space-y-6">
                @if($reviews->count() > 0)
                    @foreach($reviews as $review)
                        <div class="bg-eco-50/30 rounded-xl p-6">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-eco-200 flex items-center justify-center text-eco-600 font-medium flex-shrink-0">
                                    {{ substr($review->user->name ?? 'A', 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <h4 class="font-medium text-eco-900">{{ $review->user->name ?? 'Анонимный пользователь' }}</h4>
                                            <p class="text-sm text-eco-600">{{ $review->created_at->format('d.m.Y') }}</p>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                     viewBox="0 0 20 20"
                                                     fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <h5 class="text-lg font-medium text-eco-900 mb-2">{{ $review->title }}</h5>
                                    <p class="text-eco-700">{{ $review->comment }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="mt-6">
                        {{ $reviews->links() }}
                    </div>
                @else
                    <div class="bg-eco-50/30 rounded-xl p-8 text-center">
                        <svg class="w-16 h-16 text-eco-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p class="text-eco-700 mb-4">Отзывы пока отсутствуют. Будьте первым, кто оставит отзыв!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>