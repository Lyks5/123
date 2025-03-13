<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\Tag;

class BlogController extends Controller
{
    /**
     * Search for blog posts based on the query.
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:255',
        ]);

        $query = $request->input('query');
        $posts = BlogPost::with(['author', 'categories'])
            ->where('title', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->latest()
            ->paginate(8);

        return view('pages.blog', [
            'posts' => $posts,
            'featuredPost' => null, // You can modify this if you want to show a featured post
            'categories' => BlogCategory::withCount('posts')->get(),
            'recentPosts' => BlogPost::latest()->take(3)->get(),
            'tags' => Tag::has('blogPosts')->take(10)->get(),
        ]);
    }

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
        $posts = $tag->posts() 
            ->with(['author', 'categories'])
            ->latest()
            ->paginate(8);
            
        return view('pages.blog-tag', [
            'tag' => $tag,
            'posts' => $posts
        ]);
    }
}
