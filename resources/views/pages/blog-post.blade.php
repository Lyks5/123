
@extends('layouts.app')

@section('title', $post->title . ' - EcoSport')

@section('content')
    <!-- Blog Post Hero -->
    <section class="relative py-16 lg:py-20 overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-b from-eco-50/80 to-eco-50/50"></div>
            <img 
                src="{{ $post->image }}" 
                alt="{{ $post->title }}" 
                class="w-full h-full object-cover"
            />
        </div>
        
        <div class="container-width relative px-4 sm:px-6 lg:px-8 pt-12">
            <div class="max-w-4xl mx-auto">
                <div class="mb-6 flex flex-wrap gap-2">
                    @foreach($post->categories as $category)
                    <a 
                        href="{{ route('blog.category', $category->slug) }}" 
                        class="inline-block px-3 py-1 rounded-full bg-eco-100 text-eco-800 text-sm font-medium hover:bg-eco-200 transition-colors"
                    >
                        {{ $category->name }}
                    </a>
                    @endforeach
                </div>
                
                <h1 class="text-3xl lg:text-5xl font-bold text-eco-900 mb-6">
                    {{ $post->title }}
                </h1>
                
                <div class="flex items-center gap-6 mb-10">
                    <div class="flex items-center">
                        <img 
                            src="{{ $post->author->avatar }}" 
                            alt="{{ $post->author->name }}" 
                            class="w-10 h-10 rounded-full object-cover mr-3"
                        />
                        <div>
                            <p class="font-medium text-eco-900">{{ $post->author->name }}</p>
                            <p class="text-xs text-eco-600">{{ $post->author->position }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-eco-600">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                            {{ $post->created_at->format('d.m.Y') }}
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            {{ $post->readTime }} мин. чтения
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Blog Post Content -->
    <section class="py-10 lg:py-16 bg-white">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <article class="prose prose-lg prose-eco max-w-none">
                        {!! $post->content !!}
                    </article>
                    
                    <!-- Tags -->
                    <div class="mt-12 pt-8 border-t border-eco-100">
                        <h3 class="text-lg font-semibold text-eco-900 mb-4">Теги</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($post->tags as $tag)
                            <a 
                                href="{{ route('blog.tag', $tag->slug) }}" 
                                class="inline-block px-3 py-1 rounded-full bg-eco-100 text-eco-800 text-sm hover:bg-eco-200 transition-colors"
                            >
                                {{ $tag->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Share -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-eco-900 mb-4">Поделиться</h3>
                        <div class="flex gap-3">
                            <a href="#" class="flex items-center justify-center h-10 w-10 rounded-full bg-eco-50 text-eco-700 hover:bg-eco-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                            </a>
                            <a href="#" class="flex items-center justify-center h-10 w-10 rounded-full bg-eco-50 text-eco-700 hover:bg-eco-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                            </a>
                            <a href="#" class="flex items-center justify-center h-10 w-10 rounded-full bg-eco-50 text-eco-700 hover:bg-eco-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Related Posts -->
                    @if($relatedPosts->count() > 0)
                    <div class="mt-16">
                        <h3 class="text-2xl font-bold text-eco-900 mb-6">Связанные статьи</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($relatedPosts as $relatedPost)
                            <div class="rounded-xl overflow-hidden shadow-md bg-white">
                                <div class="relative">
                                    <img 
                                        src="{{ $relatedPost->image }}" 
                                        alt="{{ $relatedPost->title }}" 
                                        class="w-full h-40 object-cover"
                                    >
                                </div>
                                <div class="p-4">
                                    <p class="text-sm text-eco-600 mb-2">{{ $relatedPost->created_at->format('d.m.Y') }}</p>
                                    <h3 class="text-lg font-semibold text-eco-900 mb-3 line-clamp-2">
                                        <a href="{{ route('blog.show', $relatedPost->slug) }}" class="hover:text-eco-600 transition-colors">
                                            {{ $relatedPost->title }}
                                        </a>
                                    </h3>
                                    <a 
                                        href="{{ route('blog.show', $relatedPost->slug) }}" 
                                        class="inline-flex items-center text-eco-600 hover:text-eco-700 transition-colors font-medium text-sm"
                                    >
                                        Читать далее
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24 space-y-8">
                        <!-- Recent Posts -->
                        <div class="bg-eco-50 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-eco-900 mb-4">Последние статьи</h3>
                            <div class="space-y-4">
                                @foreach($recentPosts as $recentPost)
                                <a href="{{ route('blog.show', $recentPost->slug) }}" class="flex items-start group">
                                    <img 
                                        src="{{ $recentPost->image }}" 
                                        alt="{{ $recentPost->title }}" 
                                        class="w-16 h-16 rounded-lg object-cover mr-3 flex-shrink-0"
                                    >
                                    <div>
                                        <h4 class="text-eco-800 group-hover:text-eco-600 transition-colors font-medium line-clamp-2">
                                            {{ $recentPost->title }}
                                        </h4>
                                        <p class="text-xs text-eco-600">{{ $recentPost->created_at->format('d.m.Y') }}</p>
                                    </div>
                                </a>
                                @endforeach
                            </div>
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
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Newsletter Section -->
    @include('components.newsletter')
@endsection
