@props(['ecoFeatures'])

<div class="eco-features" x-data="{ selectedFeature: null }">
    <div class="eco-features-header relative overflow-hidden">
        <div class="flex items-center justify-between p-6 bg-gradient-to-r from-green-50 to-eco-50 rounded-t-2xl border border-eco-100">
            <h3 class="flex items-center gap-3 text-xl font-semibold text-green-900 dark:text-green-100">
                <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                </svg>
                Экологические характеристики
            </h3>
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium text-green-700 dark:text-green-300">Эко-рейтинг</span>
                <div class="relative h-2.5 w-28 bg-eco-100 rounded-full overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-eco-400 to-eco-600 rounded-full transition-all duration-1000 ease-out"
                        style="width: {{ $ecoFeatures->avg('value') }}%; transform-origin: left"
                        x-init="setTimeout(() => $el.style.transform = 'scaleX(1)', 100)"
                        x-style="transform: 'scaleX(0)'"
                    ></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="p-6 bg-white dark:bg-gray-900 rounded-b-2xl border-x border-b border-eco-100">
        <div
            class="grid grid-cols-1 sm:grid-cols-2 gap-4"
            x-data="{ animationDelay: 0 }"
        >
            @forelse($ecoFeatures as $index => $feature)
                <div
                    class="eco-feature-card group"
                    x-data="{ showDetails: false }"
                    @mouseenter="showDetails = true; selectedFeature = $el"
                    @mouseleave="showDetails = false; selectedFeature = null"
                    x-init="$el.style.opacity = 0; $el.style.transform = 'translateY(20px)';
                            setTimeout(() => {
                                $el.style.opacity = 1;
                                $el.style.transform = 'translateY(0)';
                            }, 50 + ({{ $index }} * 100))"
                    style="transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1)"
                >
                    <div class="relative flex items-start gap-4 p-4 rounded-xl bg-gradient-to-br from-eco-50/50 to-white border border-eco-100 hover:shadow-lg hover:shadow-eco-100/20 transition-all duration-300">
                        <div class="eco-feature-icon shrink-0">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-eco-100 to-eco-50 border border-eco-200 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                                @switch($feature->name)
                                    @case('Переработка')
                                        <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    @break
                                    @case('Углеродный след')
                                        <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15a4.5 4.5 0 004.5 4.5H18a3.75 3.75 0 001.332-7.257 3 3 0 00-3.758-3.848 5.25 5.25 0 00-10.233 2.33A4.502 4.502 0 002.25 15z" />
                                        </svg>
                                    @break
                                    @case('Экономия воды')
                                        <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                        </svg>
                                    @break
                                    @case('Био-разлагаемость')
                                        <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 01-1.125-1.125v-3.75zM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-8.25zM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-2.25z" />
                                        </svg>
                                    @break
                                    @default
                                        <svg class="w-6 h-6 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                                        </svg>
                                @endswitch
                            </div>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-eco-900 mb-1 group-hover:text-eco-700 transition-colors duration-300">
                                {{ $feature->name }}
                            </h4>
                            <div class="text-eco-600 text-sm leading-relaxed">
                                {{ $feature->value ?? \Illuminate\Support\Str::limit($feature->description, 50) }}
                            </div>
                            
                            @if($feature->description)
                                <div
                                    class="mt-2 text-xs text-eco-500 overflow-hidden transition-all duration-300"
                                    style="max-height: 0;"
                                    x-ref="details"
                                    x-bind:style="showDetails ? 'max-height: 200px' : ''"
                                >
                                    {{ $feature->description }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2">
                    <div class="flex flex-col items-center justify-center gap-4 p-8 rounded-xl bg-eco-50/50 border-2 border-dashed border-eco-200">
                        <svg class="w-12 h-12 text-eco-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <p class="text-eco-600 text-sm font-medium">
                            Экологические характеристики не указаны
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($ecoFeatures->isNotEmpty())
            <div
                class="mt-6 p-4 rounded-xl bg-gradient-to-br from-eco-50/80 to-white border border-eco-100"
                x-data="{ show: false }"
                x-intersect:enter="show = true"
            >
                
                <ul class="space-y-3" x-data="{ animationDelay: 0 }">
                    @foreach($ecoFeatures as $feature)
                        @if($feature->description)
                            <li
                                class="flex items-start gap-3 text-sm text-eco-700"
                                x-show="show"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform translate-y-4"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                style="transition-delay: calc({{ $loop->index }} * 150ms)"
                            >
                                <div class="flex-shrink-0 w-5 h-5 rounded-full bg-eco-100 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-eco-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="leading-tight">{{ $feature->description }}</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>