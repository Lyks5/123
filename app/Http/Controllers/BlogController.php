<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\Tag;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index()
{
    // Получаем посты блога с новой структурой связей
    $posts = BlogPost::with(['author', 'category']) // Заменяем categories на category (singular)
        ->where('status', 'published')
        ->where('published_at', '<=', now())
        ->latest('published_at')
        ->paginate(8);

    // Избранный пост (обновляем связь)
    $featuredPost = BlogPost::where('is_featured', true)
        ->where('status', 'published')
        ->where('published_at', '<=', now())
        ->with(['author', 'category']) // Меняем на единственную категорию
        ->latest('published_at')
        ->first();

    // Категории с подсчётом постов (используем новую связь)
    $categories = BlogCategory::withCount(['posts' => function($query) {
            $query->where('status', 'published')
                  ->where('published_at', '<=', now());
        }])->get();

    // Последние посты (без изменений)
    $recentPosts = BlogPost::where('status', 'published')
        ->where('published_at', '<=', now())
        ->latest('published_at')
        ->take(3)
        ->get();

    return view('pages.blog', compact('posts', 'featuredPost', 'categories', 'recentPosts'));
}
    
    /**
     * Display the specified blog post.
     */
    public function show($slug)
{
    // Получаем пост с обновлённой связью
    $post = BlogPost::where('slug', $slug)
        ->where('status', 'published')
        ->where('published_at', '<=', now())
        ->with(['author', 'category']) // Меняем на единственную категорию
        ->firstOrFail();

    // Увеличиваем счётчик просмотров (если колонка существует)
    if (Schema::hasColumn('blog_posts', 'views')) {
        $post->increment('views');
    }

    // Похожие посты через новую связь
    $relatedPosts = BlogPost::where('category_id', $post->category_id)
        ->where('id', '!=', $post->id)
        ->where('status', 'published')
        ->where('published_at', '<=', now())
        ->with('author') // Опционально, если нужно
        ->latest('published_at')
        ->take(3)
        ->get();

    return view('pages.blog-post-detail', compact('post', 'relatedPosts'));
}
    
    /**
     * Display posts by category.
     */
    public function category($slug)
{
    // Получаем категорию по slug
    $category = BlogCategory::where('slug', $slug)->firstOrFail();

    // Получаем посты через прямую связь (без промежуточной таблицы)
    $posts = BlogPost::where('category_id', $category->id)
        ->where('status', 'published')
        ->where('published_at', '<=', now())
        ->with('author') // Добавляем автора если нужно
        ->latest('published_at')
        ->paginate(8);

    return view('pages.blog-category', [
        'category' => $category,
        'posts' => $posts
    ]);
}  
}