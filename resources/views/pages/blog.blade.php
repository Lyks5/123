
@extends('layouts.app')

@section('title', 'Блог - EcoSport')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-eco-50 py-20 lg:py-24 overflow-hidden">
        <div class="container-width relative px-4 sm:px-6 lg:px-8 pt-16">
            <div class="max-w-3xl">
                <h1 class="text-4xl sm:text-5xl font-bold text-eco-900 mb-6">
                    Наш эко-блог
                </h1>
                <p class="text-xl text-eco-800 mb-8 max-w-2xl">
                    Последние новости, советы и информация об экологичном спорте, устойчивом образе жизни и наших инициативах.
                </p>
            </div>
        </div>
    </section>
    
    <!-- Blog Section -->
    <section class="section-padding bg-white">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24 space-y-8">
                        <!-- Search -->
                        <div class="bg-eco-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-eco-900 mb-4">Поиск</h3>
                            <form action="#" method="GET">
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        name="search" 
                                        placeholder="Искать статьи..." 
                                        class="w-full rounded-lg border-eco-300 focus:border-eco-500 focus:ring-eco-500 pl-10"
                                    >
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-eco-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Categories -->
                        <div class="bg-eco-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-eco-900 mb-4">Категории</h3>
                            <ul class="space-y-2">
                                @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('blog.category', $category->slug) }}" class="flex items-center justify-between text-eco-800 hover:text-eco-600 transition-colors">
                                        <span>{{ $category->name }}</span>
                                        <span class="bg-eco-100 text-eco-800 text-xs rounded-full px-2 py-1">{{ $category->posts_count }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        
                        <!-- Recent Posts -->
                        <div class="bg-eco-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-eco-900 mb-4">Последние статьи</h3>
                            <div class="space-y-4">
                                @foreach($recentPosts as $post)
                                <a href="{{ route('blog.show', $post->slug) }}" class="flex items-start group">
                                    <img 
                                        src="{{ $post->image }}" 
                                        alt="{{ $post->title }}" 
                                        class="w-16 h-16 rounded-lg object-cover mr-3 flex-shrink-0"
                                    >
                                    <div>
                                        <h4 class="text-eco-800 group-hover:text-eco-600 transition-colors font-medium line-clamp-2">
                                            {{ $post->title }}
                                        </h4>
                                        <p class="text-xs text-eco-600">{{ $post->created_at->format('d.m.Y') }}</p>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Tags -->
                        <div class="bg-eco-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-eco-900 mb-4">Теги</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags as $tag)
                                <a 
                                    href="{{ route('blog.tag', $tag->slug) }}" 
                                    class="bg-eco-100 hover:bg-eco-200 transition-colors text-eco-800 text-xs rounded-full px-3 py-1"
                                >
                                    {{ $tag->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Blog Posts -->
                <div class="lg:col-span-3">
                    <!-- Featured Post -->
                    @if($featuredPost)
                    <div class="mb-12">
                        <div class="rounded-2xl overflow-hidden shadow-lg bg-white">
                            <div class="relative">
                                <img 
                                    src="{{ $featuredPost->image }}" 
                                    alt="{{ $featuredPost->title }}" 
                                    class="w-full h-64 md:h-80 object-cover"
                                >
                                <div class="absolute top-4 left-4">
                                    <span class="inline-block bg-eco-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                        Рекомендуемая статья
                                    </span>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center mb-4">
                                    <span class="text-sm text-eco-600">{{ $featuredPost->created_at->format('d.m.Y') }}</span>
                                    <span class="mx-2 text-eco-300">•</span>
                                    <span class="text-sm text-eco-600">{{ $featuredPost->readTime }} мин. чтения</span>
                                </div>
                                <h2 class="text-2xl font-bold text-eco-900 mb-3">
                                    <a href="{{ route('blog.show', $featuredPost->slug) }}" class="hover:text-eco-600 transition-colors">
                                        {{ $featuredPost->title }}
                                    </a>
                                </h2>
                                <p class="text-eco-700 mb-4">
                                    {{ $featuredPost->excerpt }}
                                </p>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($featuredPost->categories as $category)
                                    <a 
                                        href="{{ route('blog.category', $category->slug) }}" 
                                        class="text-eco-800 hover:text-eco-600 transition-colors text-sm"
                                    >
                                        #{{ $category->name }}
                                    </a>
                                    @endforeach
                                </div>
                                <div class="flex items-center">
                                    <img 
                                        src="{{ $featuredPost->author->avatar }}" 
                                        alt="{{ $featuredPost->author->name }}" 
                                        class="w-10 h-10 rounded-full mr-3"
                                    >
                                    <div>
                                        <p class="text-eco-900 font-medium">{{ $featuredPost->author->name }}</p>
                                        <p class="text-xs text-eco-600">{{ $featuredPost->author->position }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Posts Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($posts as $post)
                        <div class="rounded-xl overflow-hidden shadow-md bg-white">
                            <div class="relative">
                                <img 
                                    src="{{ $post->image }}" 
                                    alt="{{ $post->title }}" 
                                    class="w-full h-48 object-cover"
                                >
                            </div>
                            <div class="p-6">
                                <div class="flex items-center mb-3">
                                    <span class="text-sm text-eco-600">{{ $post->created_at->format('d.m.Y') }}</span>
                                    <span class="mx-2 text-eco-300">•</span>
                                    <span class="text-sm text-eco-600">{{ $post->readTime }} мин. чтения</span>
                                </div>
                                <h3 class="text-xl font-semibold text-eco-900 mb-3">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-eco-600 transition-colors">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                <p class="text-eco-700 mb-4 line-clamp-2">
                                    {{ $post->excerpt }}
                                </p>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($post->categories as $category)
                                    <a 
                                        href="{{ route('blog.category', $category->slug) }}" 
                                        class="text-eco-800 hover:text-eco-600 transition-colors text-sm"
                                    >
                                        #{{ $category->name }}
                                    </a>
                                    @endforeach
                                </div>
                                <a 
                                    href="{{ route('blog.show', $post->slug) }}" 
                                    class="inline-flex items-center text-eco-600 hover:text-eco-700 transition-colors font-medium"
                                >
                                    Читать далее
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Newsletter Section -->
    @include('components.newsletter')
@endsection
