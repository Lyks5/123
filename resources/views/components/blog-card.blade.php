<div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
    <a href="{{ route('blog.show', $post->slug) }}" class="block">
        @if($post->featured_image)
            <div class="aspect-video">
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
            </div>
        @else
            <div class="w-full aspect-video bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                <span class="text-gray-500 dark:text-gray-400">Нет изображения</span>
            </div>
        @endif
        <div class="p-6">
            <div class="flex flex-wrap gap-2 mb-3">
                @foreach($post->categories as $category)
                    <span class="px-3 py-1 bg-eco-50 dark:bg-eco-900 text-eco-800 dark:text-eco-100 rounded-full text-xs">
                        {{ $category->name }}
                    </span>
                @endforeach
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ $post->title }}</h3>
            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-3">
                <span>{{ $post->published_at->format('d.m.Y') }}</span>
                <span class="mx-2">&bull;</span>
                <span>{{ $post->author->name }}</span>
            </div>
            <p class="text-gray-700 dark:text-gray-300 mb-4 line-clamp-3">
                {{ $post->excerpt }}
            </p>
            <div class="text-eco-600 dark:text-eco-400 font-medium">Читать далее &rarr;</div>
        </div>
    </a>
</div>