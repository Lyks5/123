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
        $posts = BlogPost::with(['author', 'categories'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(8);
            
        $featuredPost = BlogPost::where('is_featured', true)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->with(['author', 'categories'])
            ->latest('published_at')
            ->first();
            
        $categories = BlogCategory::withCount('posts')->get();
        
        $recentPosts = BlogPost::where('status', 'published')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take(3)
            ->get();
        
        $tags = Tag::has('blogPosts')->take(10)->get();
        
        return view('pages.blog', compact('posts', 'featuredPost', 'categories', 'recentPosts', 'tags'));
    }
    
    /**
     * Display the specified blog post.
     */
    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->firstOrFail();
            
        $post->load(['author', 'categories', 'tags']);
        
        // Increment view count
        $post->increment('views');
        
        $relatedPosts = BlogPost::whereHas('categories', function ($query) use ($post) {
            $query->whereIn('id', $post->categories->pluck('id'));
        })
        ->where('id', '!=', $post->id)
        ->where('status', 'published')
        ->where('published_at', '<=', now())
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
        $category = BlogCategory::where('slug', $slug)->firstOrFail();
        
        $posts = BlogPost::whereHas('categories', function($query) use ($category) {
            $query->where('blog_categories.id', $category->id);
        })
        ->where('status', 'published')
        ->where('published_at', '<=', now())
        ->latest('published_at')
        ->paginate(8);
            
        return view('pages.blog-category', [
            'category' => $category,
            'posts' => $posts
        ]);
    }
    
    /**
     * Display posts by tag.
     */
    public function tag($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        
        $posts = $tag->blogPosts()
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->with(['author', 'categories'])
            ->latest('published_at')
            ->paginate(8);
            
        return view('pages.blog-tag', [
            'tag' => $tag,
            'posts' => $posts
        ]);
    }
    
    /**
     * Print invoice for an order.
     */
    public function printInvoice($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.print-invoice', compact('order'));
    }
    
    /**
     * Print packing slip for an order.
     */
    public function printPackingSlip($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.print-packing-slip', compact('order'));
    }
}