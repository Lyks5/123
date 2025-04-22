@extends('layouts.app')

@section('title', 'Блог - EcoSport')

@section('content')
    <div class="min-h-screen bg-background">
        @include('components.navbar')
        
        <div class="container mx-auto px-4 py-8 pt-24">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-eco-900 mb-4">
                    Блог об экологичном спорте
                </h1>
                <p class="text-eco-700">
                    Полезные статьи, советы и новости о экологически чистых спортивных товарах, 
                    устойчивом образе жизни и том, как сделать ваши тренировки более экологичными.
                </p>
            </div>
            
            <!-- Featured post -->
            @if($featuredPost)
                <div class="mb-16">
                    <div class="bg-white rounded-xl overflow-hidden shadow-md">
                        <div class="grid grid-cols-1 lg:grid-cols-2">
                            <div class="aspect-video lg:aspect-auto">
                                <img 
                                    src="{{ $featuredPost['image'] }}"
                                    alt="{{ $featuredPost['title'] }}"
                                    class="w-full h-full object-cover"
                                />
                            </div>
                            <div class="p-6 md:p-8 flex flex-col justify-center">
                                <span class="border border-eco-200 text-eco-800 px-3 py-1 rounded-full text-sm mb-4 w-fit">
                                    Рекомендуемая статья
                                </span>
                                <h2 class="text-2xl md:text-3xl font-bold text-eco-900 mb-4">
                                    {{ $featuredPost['title'] }}
                                </h2>
                                <div class="flex items-center text-sm text-eco-600 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ $featuredPost['date'] }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $featuredPost['author'] }}</span>
                                </div>
                                <p class="text-eco-700 mb-6">
                                    {{ $featuredPost['excerpt'] }}
                                </p>
                                <a 
                                    href="{{ route('blog.show', $featuredPost['id']) }}"
                                    class="text-eco-600 font-medium hover:text-eco-800 inline-flex items-center"
                                >
                                    Читать далее
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Blog posts -->
                <div class="lg:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
@foreach($posts as $post)

                            <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                                <div class="aspect-video">
                                    <img 
                                        src="{{ $post['image'] }}"
                                        alt="{{ $post['title'] }}"
                                        class="w-full h-full object-cover"
                                    />
                                </div>
                                <div class="p-6">
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @foreach($post['categories'] as $category)
                                            <span class="bg-eco-50 text-eco-800 px-3 py-1 rounded-full text-sm">
                                                {{ $category }}
                                            </span>
                                        @endforeach
                                    </div>
                                    <h3 class="text-xl font-bold text-eco-900 mb-3">
                                        {{ $post['title'] }}
                                    </h3>
                                    <div class="flex items-center text-sm text-eco-600 mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ $post['date'] }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $post['author'] }}</span>
                                    </div>
                                    <p class="text-eco-700 mb-4 line-clamp-3">
                                        {{ $post['excerpt'] }}
                                    </p>
                                    <a 
                                        href="{{ route('blog.show', $post['id']) }}"
                                        class="text-eco-600 font-medium hover:text-eco-800 inline-flex items-center"
                                    >
                                        Читать далее
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="flex justify-center mt-12">
                        <nav class="flex items-center space-x-1">
                            <button class="px-3 py-1 border border-eco-200 text-eco-700 rounded-md">
                                &laquo;
                            </button>
                            <button class="px-3 py-1 bg-eco-100 text-eco-800 rounded-md">
                                1
                            </button>
                            <button class="px-3 py-1 border border-eco-200 text-eco-700 rounded-md">
                                2
                            </button>
                            <button class="px-3 py-1 border border-eco-200 text-eco-700 rounded-md">
                                3
                            </button>
                            <button class="px-3 py-1 border border-eco-200 text-eco-700 rounded-md">
                                &raquo;
                            </button>
                        </nav>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="space-y-8">
                    <!-- Search -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-eco-900 mb-4">Поиск</h3>
                        <form action="{{ route('blog.search') }}" method="GET">
                            <div class="relative">
                                <input
                                    type="text"
                                    name="query"
                                    placeholder="Искать в блоге..."
                                    class="w-full rounded-lg border-eco-300 focus:border-eco-500 focus:ring-eco-500 pr-10"
                                />
                                <button 
                                    type="submit" 
                                    class="absolute right-0 top-0 h-full px-3"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-eco-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Categories -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-eco-900 mb-4">Категории</h3>
                        <ul class="space-y-2">
                            @foreach($categories as $category)
                                <li>
                                    <a 
                                        href="{{ route('blog.category', $category['name']) }}"
                                        class="flex justify-between items-center text-eco-700 hover:text-eco-900 py-2 px-1 transition-colors"
                                    >
                                        <span>{{ $category['name'] }}</span>
                                        <span class="bg-eco-50 text-eco-700 text-xs px-2 py-1 rounded-full">
                                            {{ $category['count'] }}
                                        </span>
                                    </a>
                                    <div class="border-b border-eco-100"></div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <!-- Recent posts -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-eco-900 mb-4">Недавние посты</h3>
                        <div class="space-y-4">
@foreach($posts->take(3) as $post)

                                <div class="flex items-start space-x-3">
                                    <div class="w-16 h-16 flex-shrink-0 rounded-md overflow-hidden">
                                        <img 
                                            src="{{ $post['image'] }}"
                                            alt="{{ $post['title'] }}"
                                            class="w-full h-full object-cover"
                                        />
                                    </div>
                                    <div>
                                        <a 
                                            href="{{ route('blog.show', $post['id']) }}"
                                            class="text-sm font-medium text-eco-900 hover:text-eco-700 line-clamp-2"
                                        >
                                            {{ $post['title'] }}
                                        </a>
                                        <p class="text-xs text-eco-600 mt-1">{{ $post['date'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    
                </div>
            </div>
        </div>
        
    </div>
@endsection
