@extends('layouts.app')

@section('title', 'Блог - EcoSport')

@section('content')
    <!-- Hero Section -->
    <section class="relative py-20 lg:py-24 overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-r from-eco-50/90 to-eco-50/70"></div>
            <img 
                src="https://images.unsplash.com/photo-1583468982228-19f19164aee1?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" 
                alt="Блог EcoSport" 
                class="w-full h-full object-cover"
            />
        </div>
        
        <div class="container-width relative px-4 sm:px-6 lg:px-8 pt-16">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl sm:text-5xl font-bold text-eco-900 mb-6 reveal">
                    Наш блог
                </h1>
                <p class="text-xl text-eco-800 mb-8 reveal">
                    Делимся знаниями об экологичном образе жизни, устойчивом развитии и новостях в мире спорта.
                </p>
            </div>
        </div>
    </section>
    
    <!-- Featured Post Section -->
    @if($featuredPost)
    <section class="section-padding bg-white pt-0">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal">
                <span class="inline-block bg-eco-100 text-eco-800 px-4 py-1 rounded-full text-sm font-medium mb-4">
                    Избранная статья
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-eco-900 mb-6">
                    Не пропустите
                </h2>
            </div>
            
            <div class="reveal">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-center bg-eco-50/50 rounded-2xl overflow-hidden">
                    <div class="lg:col-span-3 h-full">
                        <a href="{{ route('blog.show', $featuredPost->slug) }}" class="block h-full">
                            <img 
                                src="{{ $featuredPost->featured_image }}" 
                                alt="{{ $featuredPost->title }}" 
                                class="w-full h-full object-cover lg:rounded-l-2xl max-h-96 lg:max-h-none"
                            />
                        </a>
                    </div>
                    <div class="lg:col-span-2 p-6 lg:p-10">
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($featuredPost->categories as $category)
                            <a href="{{ route('blog.category', $category->slug) }}" class="bg-eco-100 text-eco-800 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $category->name }}
                            </a>
                            @endforeach
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-bold text-eco-900 mb-4">
                            <a href="{{ route('blog.show', $featuredPost->slug) }}" class="hover:text-eco-700 transition-colors">
                                {{ $featuredPost->title }}
                            </a>
                        </h3>
                        <p class="text-eco-700 mb-6">
                            {{ Str::limit($featuredPost->excerpt, 180) }}
                        </p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full overflow-hidden mr-3">
                                    <img 
                                        src="{{ $featuredPost->author->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode($featuredPost->author->name) }}" 
                                        alt="{{ $featuredPost->author->name }}" 
                                        class="w-full h-full object-cover"
                                    />
                                </div>
                                <div>
                                    <p class="font-medium text-eco-900">{{ $featuredPost->author->name }}</p>
                                    <p class="text-sm text-eco-600">{{ $featuredPost->published_at->format('d.m.Y') }}</p>
                                </div>
                            </div>
                            <a href="{{ route('blog.show', $featuredPost->slug) }}" class="inline-flex items-center text-eco-700 hover:text-eco-600 font-medium">
                                Читать
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    
    <!-- Blog Posts Section -->
    <section class="section-padding bg-white">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
                <!-- Posts -->
                <div class="lg:col-span-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-6 sm:gap-8">
                        @foreach($posts as $post)
                        <div class="bg-white rounded-xl border border-gray-100 hover:border-eco-100 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 reveal">
                            <a href="{{ route('blog.show', $post->slug) }}" class="block aspect-video overflow-hidden">
                                <img 
                                    src="{{ $post->featured_image }}" 
                                    alt="{{ $post->title }}" 
                                    class="w-full h-full object-cover transition-transform hover:scale-105 duration-700"
                                />
                            </a>
                            <div class="p-6">
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($post->categories as $category)
                                    <a href="{{ route('blog.category', $category->slug) }}" class="bg-eco-50 text-eco-800 px-3 py-1 rounded-full text-xs font-medium hover:bg-eco-100 transition-colors">
                                        {{ $category->name }}
                                    </a>
                                    @endforeach
                                </div>
                                <h3 class="text-xl font-bold text-eco-900 mb-3 line-clamp-2">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-eco-700 transition-colors">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                <p class="text-eco-700 mb-4 line-clamp-2">
                                    {{ Str::limit($post->excerpt, 120) }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full overflow-hidden mr-2">
                                            <img 
                                                src="{{ $post->author->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode($post->author->name) }}" 
                                                alt="{{ $post->author->name }}" 
                                                class="w-full h-full object-cover"
                                            />
                                        </div>
                                        <p class="text-sm text-eco-600">{{ $post->published_at->format('d.m.Y') }}</p>
                                    </div>
                                    <a href="{{ route('blog.show', $post->slug) }}" class="text-eco-700 hover:text-eco-600 font-medium text-sm">
                                        Читать
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-12 flex justify-center reveal">
                        {{ $posts->links() }}
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Categories -->
                    <div class="bg-eco-50 rounded-xl p-6 mb-8 reveal">
                        <h3 class="text-lg font-semibold text-eco-900 mb-4">Категории</h3>
                        <ul class="space-y-3">
                            @foreach($categories as $category)
                            <li>
                                <a href="{{ route('blog.category', $category->slug) }}" class="flex items-center justify-between group">
                                    <span class="text-eco-700 group-hover:text-eco-800 transition-colors">
                                        {{ $category->name }}
                                    </span>
                                    <span class="bg-white text-eco-700 px-2 py-1 rounded-full text-xs">
                                        {{ $category->posts_count }}
                                    </span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <!-- Recent Posts -->
                    <div class="bg-eco-50 rounded-xl p-6 mb-8 reveal">
                        <h3 class="text-lg font-semibold text-eco-900 mb-4">Недавние статьи</h3>
                        <ul class="space-y-4">
                            @foreach($recentPosts as $recentPost)
                            <li class="flex items-start">
                                <div class="w-16 h-16 rounded-md overflow-hidden flex-shrink-0 mr-3">
                                    <a href="{{ route('blog.show', $recentPost->slug) }}">
                                        <img 
                                            src="{{ $recentPost->featured_image }}" 
                                            alt="{{ $recentPost->title }}" 
                                            class="w-full h-full object-cover"
                                        />
                                    </a>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-eco-900 line-clamp-2">
                                        <a href="{{ route('blog.show', $recentPost->slug) }}" class="hover:text-eco-700 transition-colors">
                                            {{ $recentPost->title }}
                                        </a>
                                    </h4>
                                    <p class="text-xs text-eco-600 mt-1">
                                        {{ $recentPost->published_at->format('d.m.Y') }}
                                    </p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    
                </div>
            </div>
        </div>
    </section>
    
    <!-- Newsletter Section -->
    @include('components.newsletter')
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation for elements with .reveal class when they enter viewport
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });
        
        document.querySelectorAll('.reveal').forEach(el => {
            observer.observe(el);
        });
    });
</script>
@endpush