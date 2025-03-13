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
            ->latest()
            ->paginate(8);
            
        $featuredPost = BlogPost::where('is_featured', true)
            ->with(['author', 'categories'])
            ->latest()
            ->first();
            
        $categories = BlogCategory::withCount('posts')->get();
        
        $recentPosts = BlogPost::latest()->take(3)->get();
        
        $tags = Tag::has('blogPosts')->take(10)->get();
        
        return view('pages.blog', compact('posts', 'featuredPost', 'categories', 'recentPosts', 'tags'));
    }
    
    /**
     * Display the specified blog post.
     */
    public function show(BlogPost $post)
    {
        $post->load(['author', 'categories', 'tags']);
        
        // Increment view count
        $post->increment('views');
        
        $relatedPosts = BlogPost::whereHas('categories', function ($query) use ($post) {
            $query->whereIn('id', $post->categories->pluck('id'));
        })
        ->where('id', '!=', $post->id)
        ->latest()
        ->take(3)
        ->get();
        
        return view('pages.blog-post', compact('post', 'relatedPosts'));
    }
    
    /**
     * Display posts by category.
     */
    public function category(BlogCategory $category)
    {
        $posts = $category->posts()
            ->with(['author', 'categories'])
            ->latest()
            ->paginate(8);
            
        return view('pages.blog-category', [
            'category' => $category,
            'posts' => $posts
        ]);
    }
    
    /**
     * Display posts by tag.
     */
    public function tag(Tag $tag)
    {
        $posts = $tag->blogPosts()
            ->with(['author', 'categories'])
            ->latest()
            ->paginate(8);
            
        return view('pages.blog-tag', [
            'tag' => $tag,
            'posts' => $posts
        ]);
    }
}