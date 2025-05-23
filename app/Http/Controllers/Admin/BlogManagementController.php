<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogPostCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogManagementController extends Controller
{
    /**
     * Constructor
     */
   

    /**
     * Display a listing of blog posts
     */
    public function index(Request $request)
    {
        $query = BlogPost::with(['author', 'categories']);
        
        // Apply filters if any
        if ($request->has('category')) {
            $query->whereHas('categories', function($q) use($request) {
                $q->where('blog_categories.id', $request->category);
            });
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('author')) {
            $query->where('author_id', $request->author);
        }
        
        // Default sorting
        $sort = $request->sort ?? 'latest';
        
        switch($sort) {
            case 'title':
                $query->orderBy('title');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
        }
        
        $posts = $query->paginate(15)->withQueryString();
        $categories = BlogCategory::all();
        $authors = User::whereHas('blogPosts')->get();
        
        return view('admin.blog.posts.index', compact('posts', 'categories', 'authors'));
    }

    /**
     * Show form for creating a new blog post
     */
    public function create()
    {
        $categories = BlogCategory::all();
        return view('admin.blog.posts.create', compact('categories'));
    }

    /**
     * Store a newly created blog post
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'categories' => 'required|array',
            'categories.*' => 'exists:blog_categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'published_at' => 'nullable|date',
        ]);
        
        // Generate slug
        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);
        
        // Set author
        $validated['author_id'] = Auth::id();
        
        // Set published_at if status is published
        if ($validated['status'] == 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }
        
        // Create post
        $post = BlogPost::create($validated);
        
        // Attach categories
        $post->categories()->attach($request->categories);
        
        return redirect()->route('admin.blog.posts.index')
            ->with('success', 'Запись блога успешно создана.');
    }

    /**
     * Show form for editing blog post
     */
    public function edit(BlogPost $post)
    {
        $categories = BlogCategory::all();
        return view('admin.blog.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified blog post
     */
    public function update(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'categories' => 'required|array',
            'categories.*' => 'exists:blog_categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'published_at' => 'nullable|date',
        ]);
        
        // Set published_at if status changed to published
        if ($validated['status'] == 'published' && $post->status != 'published') {
            $validated['published_at'] = now();
        }
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }
        
        // Update post
        $post->update($validated);
        
        // Sync categories
        $post->categories()->sync($request->categories);
        
        return redirect()->route('admin.blog.posts.index')
            ->with('success', 'Запись блога успешно обновлена.');
    }

    /**
     * Delete the specified blog post
     */
    public function destroy(BlogPost $post)
    {
        // Delete featured image from storage
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        
        $post->delete();
        
        return redirect()->route('admin.blog.posts.index')
            ->with('success', 'Запись блога успешно удалена.');
    }

    /**
     * Show the blog categories management page.
     */
    public function blogCategories()
    {
        $categories = BlogCategory::withCount('posts')->paginate(15);
        return view('admin.blog.categories.index', compact('categories'));
    }
    
    /**
     * Show the form to create a new blog category.
     */
    public function createBlogCategory()
    {
        return view('admin.blog.categories.create');
    }
    
    /**
     * Store a new blog category.
     */
    public function storeBlogCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);
        
        BlogCategory::create($validated);
        
        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'Категория блога успешно создана.');
    }
    
    /**
     * Show the form to edit a blog category.
     */
    public function editBlogCategory(BlogCategory $category)
    {
        return view('admin.blog.categories.edit', compact('category'));
    }
    
    /**
     * Update a blog category.
     */
    public function updateBlogCategory(Request $request, BlogCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);
        
        $category->update($validated);
        
        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'Категория блога успешно обновлена.');
    }
    
    /**
     * Delete a blog category.
     */
    public function deleteBlogCategory(BlogCategory $category)
    {
        // Check if category has posts
        if ($category->posts()->count() > 0) {
            return back()->withErrors(['general' => 'Нельзя удалить категорию, содержащую записи.']);
        }
        
        $category->delete();
        
        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'Категория блога успешно удалена.');
    }
}