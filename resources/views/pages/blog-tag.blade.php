
@extends('layouts.app')

@section('title', 'Тег: ' . $tag->name . ' - Блог EcoSport')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-eco-50 py-20 lg:py-24 overflow-hidden">
        <div class="container-width relative px-4 sm:px-6 lg:px-8 pt-16">
            <div class="max-w-3xl">
                <h1 class="text-4xl sm:text-5xl font-bold text-eco-900 mb-6">
                    Тег: {{ $tag->name }}
                </h1>
                <p class="text-xl text-eco-800 mb-8 max-w-2xl">
                    Статьи и новости, связанные с тегом "{{ $tag->name }}".
                </p>
            </div>
        </div>
    </section>
    
    <!-- Blog Posts -->
    <section class="section-padding bg-white">
        <div class="container-width px-4 sm:px-6 lg:px-8">
            <!-- Posts Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
    </section>
    
    <!-- Newsletter Section -->
    @include('components.newsletter')
@endsection