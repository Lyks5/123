@extends('layouts.app')

@section('title', $post->meta_title ?? $post->title)

@section('meta')
    <meta name="description" content="{{ $post->meta_description ?? Str::limit(strip_tags($post->excerpt), 160) }}">
    <meta property="og:title" content="{{ $post->meta_title ?? $post->title }}">
    <meta property="og:description" content="{{ $post->meta_description ?? Str::limit(strip_tags($post->excerpt), 160) }}">
    @if($post->image)
        <meta property="og:image" content="{{ $post->image }}">
    @endif
    <meta property="og:type" content="article">
    <meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">
    <meta property="article:author" content="{{ $post->author->name }}">
    @foreach($post->categories as $category)
        <meta property="article:section" content="{{ $category->name }}">
    @endforeach
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="container mx-auto px-4">
        <!-- Breadcrumbs -->
        <div class="text-sm text-gray-500 mb-6">
            <a href="{{ route('home') }}" class="hover:underline">Главная</a> &raquo; 
            <a href="{{ route('blog') }}" class="hover:underline">Блог</a> &raquo; 
            <span class="text-gray-700">{{ $post->title }}</span>
        </div>
        
        <article class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Featured Image -->
            @if($post->image)
                <div class="w-full h-96 relative">
                    <img src="{{ $post->image }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                </div>
            @endif
            
            <div class="p-6 md:p-8 {{ $post->image ? '-mt-20 relative z-10 bg-white rounded-t-3xl' : '' }}">
                <!-- Categories -->
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($post->categories as $category)
                        <a href="{{ route('blog.category', $category->slug) }}" class="px-3 py-1 bg-eco-50 text-eco-700 rounded-full text-sm hover:bg-eco-100 transition">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
                
                <!-- Title -->
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>
                
                <!-- Meta Information -->
                <div class="flex items-center text-gray-500 text-sm mb-6">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ $post->author->name }}
                    </div>
                    <span class="mx-2">&bull;</span>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $post->published_at->format('d.m.Y') }}
                    </div>
                    <span class="mx-2">&bull;</span>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $post->reading_time ?? '5 мин чтения' }}
                    </div>
                </div>
                
                <!-- Excerpt -->
                <div class="text-lg text-gray-700 mb-6 font-medium">
                    {{ $post->excerpt }}
                </div>
                
                <!-- Content -->
                <div class="prose prose-lg max-w-none">
                    {!! $post->content !!}
                </div>
                
                
                
                <!-- Share -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Поделиться</h3>
                    <div class="flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-full">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" class="bg-blue-400 hover:bg-blue-500 text-white px-3 py-2 rounded-full">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                            </svg>
                        </a>
                        <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-full">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-1.28-.67-2-1.08-3.23-1.73-.86-.57-.3-1.12.19-1.4.13-.07 2.38-2.17 2.43-2.35.01-.03.01-.16-.05-.23-.07-.07-.2-.05-.28-.03-.13.03-2.18 1.38-3.17 2.04-.29.21-.78.47-1.12.45-.37-.02-1.06-.22-1.57-.4-.63-.22-1.12-.36-1.08-.93.02-.3.3-.61.58-.82 2.45-1.69 4.9-3.37 7.37-5.07.23-.16.67-.37 1.08-.23.2.05.23.24.27.45z" />
                            </svg>
                        </a>
                        <a href="https://vk.com/share.php?url={{ urlencode(url()->current()) }}" target="_blank" class="bg-blue-700 hover:bg-blue-800 text-white px-3 py-2 rounded-full">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12.785 16.241s.288-.032.436-.194c.136-.148.132-.427.132-.427s-.02-1.304.587-1.496c.6-.19 1.37 1.26 2.185 1.818.605.422 1.064.33 1.064.33l2.137-.03s1.117-.07.587-.948c-.043-.073-.308-.65-1.588-1.838-1.34-1.242-1.16-1.041.453-3.187.981-1.31 1.374-2.11 1.251-2.451-.117-.323-.84-.24-.84-.24l-2.406.015s-.179-.025-.31.055c-.13.079-.213.263-.213.263s-.382 1.015-.89 1.878c-1.072 1.82-1.502 1.915-1.679 1.8-.409-.266-.307-1.07-.307-1.638 0-1.783.27-2.525-.527-2.718-.266-.065-.462-.107-1.142-.115-.873-.01-1.614.003-2.034.208-.279.135-.495.437-.363.454.162.022.53.099.725.363.252.341.242 1.107.242 1.107s.145 2.112-.337 2.373c-.33.18-.783-.187-1.755-1.837-.497-.859-.873-1.807-.873-1.807s-.073-.177-.204-.273c-.158-.115-.38-.152-.38-.152l-2.286.015s-.344.01-.47.159c-.112.134-.01.41-.01.41s1.79 4.187 3.819 6.293c1.858 1.926 3.967 1.8 3.967 1.8l1.2-.02z" />
                            </svg>
                        </a>
                        <button onclick="navigator.clipboard.writeText(window.location.href); alert('Ссылка скопирована');" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </article>
        
        <!-- Related Posts -->
        @if($relatedPosts->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Похожие статьи</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($relatedPosts as $relatedPost)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden transition-transform hover:shadow-md hover:-translate-y-1">
                            <a href="{{ route('blog.show', $relatedPost->slug) }}" class="block">
                                @if($relatedPost->image)
                                    <img src="{{ $relatedPost->image }}" alt="{{ $relatedPost->title }}" class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500">Нет изображения</span>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <div class="text-xs text-gray-500 mb-2">
                                        {{ $relatedPost->published_at->format('d.m.Y') }}
                                        @if($relatedPost->categories->count() > 0)
                                            &bull; {{ $relatedPost->categories->first()->name }}
                                        @endif
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">{{ $relatedPost->title }}</h3>
                                    <p class="text-gray-700 line-clamp-3">{{ $relatedPost->excerpt }}</p>
                                    <div class="mt-3 text-eco-600 font-medium">Читать далее &rarr;</div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection