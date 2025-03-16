<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\EcoFeature;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\Tag;
use App\Models\User;
use App\Models\Order;
use App\Models\EnvironmentalInitiative;
use App\Models\ContactRequest;
use App\Models\Review;
use App\Models\ProductImage;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('admin');
    }
    
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Basic stats
        $productCount = Product::count();
        $orderCount = Order::count();
        $userCount = User::count();
        $postCount = BlogPost::count();
        
        // Recent data for dashboard display
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $latestProducts = Product::latest()->take(5)->get();
        $latestUsers = User::latest()->take(5)->get();
        $pendingReviews = Review::where('is_approved', false)->count();
        
        // Get monthly sales data for last 6 months
        $monthlyData = $this->getMonthlyStatistics();
        
        // Low stock alert (products with less than 5 items)
        $lowStockProducts = Product::where('stock_quantity', '<', 5)
                                  ->where('stock_quantity', '>', 0)
                                  ->take(5)
                                  ->get();
                                  
        // Recent contact requests
        $recentContacts = ContactRequest::where('status', 'new')->latest()->take(5)->get();
        
        return view('admin.dashboard', compact(
            'productCount', 'orderCount', 'userCount', 'postCount',
            'recentOrders', 'latestProducts', 'latestUsers', 'pendingReviews',
            'monthlyData', 'lowStockProducts', 'recentContacts'
        ));
    }
    
    /**
     * Get monthly statistics for dashboard.
     */
    private function getMonthlyStatistics()
    {
        $months = [];
        $monthlyRevenue = [];
        $monthlySalesCount = [];
        $monthlyUserRegistration = [];
        
        // Get data for the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            // Get orders for this month
            $ordersInMonth = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                                  ->where('status', 'completed')
                                  ->get();
            
            // Get users registered this month
            $usersRegistered = User::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                                   ->count();
            
            // Calculate totals
            $monthlyRevenue[] = $ordersInMonth->sum('total');
            $monthlySalesCount[] = $ordersInMonth->count();
            $monthlyUserRegistration[] = $usersRegistered;
        }
        
        return [
            'months' => $months,
            'revenue' => $monthlyRevenue,
            'orders' => $monthlySalesCount,
            'users' => $monthlyUserRegistration
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Product Management
    |--------------------------------------------------------------------------
    */
    
    /**
     * Show the product management page.
     */
    public function products(Request $request)
    {
        $query = Product::with(['categories', 'ecoFeatures']);
        
        // Apply filters if any
        if ($request->has('category')) {
            $query->whereHas('categories', function($q) use($request) {
                $q->where('categories.id', $request->category);
            });
        }
        
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        if ($request->has('stock')) {
            if ($request->stock === 'in') {
                $query->where('stock_quantity', '>', 0);
            } elseif ($request->stock === 'out') {
                $query->where('stock_quantity', 0);
            } elseif ($request->stock === 'low') {
                $query->where('stock_quantity', '>', 0)
                      ->where('stock_quantity', '<', 5);
            }
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Default sorting
        $sort = $request->sort ?? 'latest';
        
        switch($sort) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'price_low':
                $query->orderBy('price');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'stock_low':
                $query->orderBy('stock_quantity');
                break;
            case 'stock_high':
                $query->orderBy('stock_quantity', 'desc');
                break;
            default:
                $query->latest();
        }
        
        $products = $query->paginate(15)->withQueryString();
        $categories = Category::all();
        
        return view('admin.products.index', compact('products', 'categories'));
    }
    
    /**
     * Show the form to create a new product.
     */
    public function createProduct()
    {
        $categories = Category::all();
        $ecoFeatures = EcoFeature::all();
        return view('admin.products.create', compact('categories', 'ecoFeatures'));
    }
    
    /**
     * Store a new product.
     */
    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|max:100|unique:products',
            'stock_quantity' => 'required|integer|min:0',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'eco_features' => 'nullable|array',
            'eco_features.*' => 'exists:eco_features,id',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_image' => 'nullable|integer',
        ]);
        
        // Generate slug
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        
        // Set boolean values
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['is_new'] = $request->has('is_new');
        
        // Create product
        $product = Product::create($validated);
        
        // Attach categories
        $product->categories()->attach($request->categories);
        
        // Attach eco features
        if ($request->has('eco_features')) {
            foreach ($request->eco_features as $featureId => $value) {
                $product->ecoFeatures()->attach($featureId, ['value' => $value]);
            }
        }
        
        // Handle images
        if ($request->hasFile('images')) {
            $primaryImageIndex = $request->input('primary_image', 0);
            
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                
                $product->images()->create([
                    'image_path' => $path,
                    'alt_text' => $product->name,
                    'is_primary' => $index == $primaryImageIndex,
                    'sort_order' => $index,
                ]);
            }
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно создан.');
    }
    
    /**
     * Show the form to edit a product.
     */
    public function editProduct(Product $product)
    {
        $categories = Category::all();
        $ecoFeatures = EcoFeature::all();
        $productEcoFeatures = $product->ecoFeatures->pluck('pivot.value', 'id')->toArray();
        
        return view('admin.products.edit', compact('product', 'categories', 'ecoFeatures', 'productEcoFeatures'));
    }
    
    /**
     * Update a product.
     */
    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'stock_quantity' => 'required|integer|min:0',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'eco_features' => 'nullable|array',
            'eco_features.*' => 'exists:eco_features,id',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_image' => 'nullable|integer',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer|exists:product_images,id',
        ]);
        
        // Set boolean values
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['is_new'] = $request->has('is_new');
        
        // Update product
        $product->update($validated);
        
        // Sync categories
        $product->categories()->sync($request->categories);
        
        // Sync eco features
        $product->ecoFeatures()->detach();
        if ($request->has('eco_features')) {
            foreach ($request->eco_features as $featureId => $value) {
                $product->ecoFeatures()->attach($featureId, ['value' => $value]);
            }
        }
        
        // Handle image deletion
        if ($request->has('delete_images')) {
            $imagesToDelete = $product->images()->whereIn('id', $request->delete_images)->get();
            
            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }
        
        // Handle new images
        if ($request->hasFile('images')) {
            $primaryImageIndex = $request->input('primary_image', 0);
            $currentCount = $product->images()->count();
            
            // If setting a new primary image, unset the current one
            if ($primaryImageIndex !== null) {
                $product->images()->update(['is_primary' => false]);
            }
            
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                
                $product->images()->create([
                    'image_path' => $path,
                    'alt_text' => $product->name,
                    'is_primary' => $index == $primaryImageIndex,
                    'sort_order' => $currentCount + $index,
                ]);
            }
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно обновлен.');
    }
    
    /**
     * Delete a product.
     */
    public function deleteProduct(Product $product)
    {
        // Delete associated images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно удален.');
    }
    
    /*
    |--------------------------------------------------------------------------
    | Category Management
    |--------------------------------------------------------------------------
    */
    
    /**
     * Show the category management page.
     */
    public function categories()
    {
        $categories = Category::with('parent')->withCount('products')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }
    
    /**
     * Show the form to create a new category.
     */
    public function createCategory()
    {
        $categories = Category::all();
        return view('admin.categories.create', compact('categories'));
    }
    
    /**
     * Store a new category.
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);
        
        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);
        
        // Set boolean values
        $validated['is_active'] = $request->has('is_active');
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }
        
        Category::create($validated);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно создана.');
    }
    
    /**
     * Show the form to edit a category.
     */
    public function editCategory(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }
    
    /**
     * Update a category.
     */
    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);
        
        // Check for circular dependency
        if ($validated['parent_id'] == $category->id) {
            return back()->withErrors(['parent_id' => 'Категория не может быть родителем самой себя.']);
        }
        
        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);
        
        // Set boolean values
        $validated['is_active'] = $request->has('is_active');
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }
        
        $category->update($validated);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно обновлена.');
    }
    
    /**
     * Delete a category.
     */
    public function deleteCategory(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return back()->withErrors(['general' => 'Нельзя удалить категорию, содержащую товары.']);
        }
        
        // Check if category has children
        if ($category->children()->count() > 0) {
            return back()->withErrors(['general' => 'Нельзя удалить категорию, содержащую подкатегории.']);
        }
        
        // Delete image from storage
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно удалена.');
    }
    
    /*
    |--------------------------------------------------------------------------
    | Blog Management
    |--------------------------------------------------------------------------
    */
    
    /**
     * Show the blog posts management page.
     */
    public function blogPosts(Request $request)
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
     * Show the form to create a new blog post.
     */
    public function createBlogPost()
    {
        $categories = BlogCategory::all();
        $tags = Tag::all();
        return view('admin.blog.posts.create', compact('categories', 'tags'));
    }
    
    /**
     * Store a new blog post.
     */
    public function storeBlogPost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'categories' => 'required|array',
            'categories.*' => 'exists:blog_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
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
        
        // Handle tags
        if ($request->has('tags')) {
            $tagIds = [];
            
            foreach ($request->tags as $tagName) {
                $tag = Tag::firstOrCreate([
                    'name' => $tagName,
                    'slug' => Str::slug($tagName),
                ]);
                
                $tagIds[] = $tag->id;
            }
            
            $post->tags()->attach($tagIds);
        }
        
        return redirect()->route('admin.blog.posts.index')
            ->with('success', 'Запись блога успешно создана.');
    }
    
    /**
     * Show the form to edit a blog post.
     */
    public function editBlogPost(BlogPost $post)
    {
        $categories = BlogCategory::all();
        $tags = Tag::all();
        $postTags = $post->tags->pluck('name')->toArray();
        
        return view('admin.blog.posts.edit', compact('post', 'categories', 'tags', 'postTags'));
    }
    
    /**
     * Update a blog post.
     */
    public function updateBlogPost(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'categories' => 'required|array',
            'categories.*' => 'exists:blog_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
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
        
        // Handle tags
        if ($request->has('tags')) {
            $tagIds = [];
            
            foreach ($request->tags as $tagName) {
                $tag = Tag::firstOrCreate([
                    'name' => $tagName,
                    'slug' => Str::slug($tagName),
                ]);
                
                $tagIds[] = $tag->id;
            }
            
            $post->tags()->sync($tagIds);
        } else {
            $post->tags()->detach();
        }
        
        return redirect()->route('admin.blog.posts.index')
            ->with('success', 'Запись блога успешно обновлена.');
    }
    
    /**
     * Delete a blog post.
     */
    public function deleteBlogPost(BlogPost $post)
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
    
    /**
     * Show blog tags management page.
     */
    public function blogTags(Request $request)
    {
        $query = Tag::withCount('blogPosts');
        
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        
        $tags = $query->orderBy('name')->paginate(20);
        
        return view('admin.blog.tags.index', compact('tags'));
    }
    
    /**
     * Show the form to create a new tag.
     */
    public function createBlogTag()
    {
        return view('admin.blog.tags.create');
    }
    
    /**
     * Store a new tag.
     */
    public function storeBlogTag(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);
        
        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);
        
        Tag::create($validated);
        
        return redirect()->route('admin.blog.tags.index')
            ->with('success', 'Тег успешно создан.');
    }
    
    /**
     * Show the form to edit a tag.
     */
    public function editBlogTag(Tag $tag)
    {
        return view('admin.blog.tags.edit', compact('tag'));
    }
    
    /**
     * Update a tag.
     */
    public function updateBlogTag(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
        ]);
        
        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);
        
        $tag->update($validated);
        
        return redirect()->route('admin.blog.tags.index')
            ->with('success', 'Тег успешно обновлен.');
    }
    
    /**
     * Delete a tag.
     */
    public function deleteBlogTag(Tag $tag)
    {
        $tag->delete();
        
        return redirect()->route('admin.blog.tags.index')
            ->with('success', 'Тег успешно удален.');
    }
    
    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */
    
    /**
     * Show the users management page.
     */
    public function users(Request $request)
    {
        $query = User::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('role')) {
            if ($request->role === 'admin') {
                $query->where('is_admin', true);
            } elseif ($request->role === 'user') {
                $query->where('is_admin', false);
            }
        }
        
        if ($request->has('sort')) {
            if ($request->sort === 'name') {
                $query->orderBy('name');
            } elseif ($request->sort === 'email') {
                $query->orderBy('email');
            } elseif ($request->sort === 'oldest') {
                $query->oldest();
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }
        
        $users = $query->paginate(15)->withQueryString();
        
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Show the form to edit a user.
     */
    public function editUser(User $user)
    {
        $orders = $user->orders()->latest()->take(5)->get();
        return view('admin.users.edit', compact('user', 'orders'));
    }
    
    /**
     * Update a user.
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'is_admin' => 'nullable|boolean',
        ]);
        
        // Set boolean values
        $validated['is_admin'] = $request->has('is_admin');
        
        $user->update($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь успешно обновлен.');
    }
    
    /*
    |--------------------------------------------------------------------------
    | Order Management
    |--------------------------------------------------------------------------
    */
    
    /**
     * Show the orders management page.
     */
    public function orders(Request $request)
    {
        $query = Order::with('user');
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->has('sort')) {
            if ($request->sort === 'id_asc') {
                $query->orderBy('id');
            } elseif ($request->sort === 'id_desc') {
                $query->orderBy('id', 'desc');
            } elseif ($request->sort === 'total_asc') {
                $query->orderBy('total');
            } elseif ($request->sort === 'total_desc') {
                $query->orderBy('total', 'desc');
            } elseif ($request->sort === 'oldest') {
                $query->oldest();
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }
        
        $orders = $query->paginate(15)->withQueryString();
        
        return view('admin.orders.index', compact('orders'));
    }
    
    /**
     * Show an order.
     */
    public function showOrder(Order $order)
    {
        $order->load(['items.product', 'user', 'address']);
        return view('admin.orders.show', compact('order'));
    }
    
    /**
     * Update an order status.
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,completed,cancelled',
            'admin_notes' => 'nullable|string',
        ]);
        
        // Update order status and notes
        $order->update($validated);
        
        // If updating to cancelled or completed, adjust inventory if configured to do so
        if (in_array($validated['status'], ['cancelled', 'completed']) && 
            $validated['status'] !== $order->getOriginal('status')) {
            
            // For cancelled orders, restore the stock
            if ($validated['status'] === 'cancelled') {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    $product->stock_quantity += $item->quantity;
                    $product->save();
                }
            }
        }
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Статус заказа успешно обновлен.');
    }
    
    /*
    |--------------------------------------------------------------------------
    | Environmental Initiatives Management
    |--------------------------------------------------------------------------
    */

    /**
     * Show the environmental initiatives management page.
     */
    public function initiatives(Request $request)
    {
        $query = EnvironmentalInitiative::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('end_date', '>=', now()->toDateString())
                      ->orWhereNull('end_date');
            } elseif ($request->status === 'ended') {
                $query->where('end_date', '<', now()->toDateString())
                      ->whereNotNull('end_date');
            }
        }
        
        if ($request->has('sort')) {
            if ($request->sort === 'title') {
                $query->orderBy('title');
            } elseif ($request->sort === 'start_date') {
                $query->orderBy('start_date');
            } elseif ($request->sort === 'end_date') {
                $query->orderBy('end_date');
            } elseif ($request->sort === 'oldest') {
                $query->oldest();
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }
        
        $initiatives = $query->paginate(15)->withQueryString();
        
        return view('admin.initiatives.index', compact('initiatives'));
    }
    
    /**
     * Show the form to create a new initiative.
     */
    public function createInitiative()
    {
        return view('admin.initiatives.create');
    }
    
    /**
     * Store a new initiative.
     */
    public function storeInitiative(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'impact_metric' => 'nullable|string|max:255',
            'impact_value' => 'nullable|numeric',
        ]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('initiatives', 'public');
        }
        
        EnvironmentalInitiative::create($validated);
        
        return redirect()->route('admin.initiatives.index')
            ->with('success', 'Экологическая инициатива успешно создана.');
    }
    
    /**
     * Show the form to edit an initiative.
     */
    public function editInitiative(EnvironmentalInitiative $initiative)
    {
        return view('admin.initiatives.edit', compact('initiative'));
    }
    
    /**
     * Update an initiative.
     */
    public function updateInitiative(Request $request, EnvironmentalInitiative $initiative)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'impact_metric' => 'nullable|string|max:255',
            'impact_value' => 'nullable|numeric',
        ]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($initiative->image) {
                Storage::disk('public')->delete($initiative->image);
            }
            
            $validated['image'] = $request->file('image')->store('initiatives', 'public');
        }
        
        $initiative->update($validated);
        
        return redirect()->route('admin.initiatives.index')
            ->with('success', 'Экологическая инициатива успешно обновлена.');
    }
    
    /**
     * Delete an initiative.
     */
    public function deleteInitiative(EnvironmentalInitiative $initiative)
    {
        // Delete image from storage
        if ($initiative->image) {
            Storage::disk('public')->delete($initiative->image);
        }
        
        $initiative->delete();
        
        return redirect()->route('admin.initiatives.index')
            ->with('success', 'Экологическая инициатива успешно удалена.');
    }
    
    /*
    |--------------------------------------------------------------------------
    | Contact Requests Management
    |--------------------------------------------------------------------------
    */

    /**
     * Show contact requests management page.
     */
    public function contactRequests(Request $request)
    {
        $query = ContactRequest::query();
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $contactRequests = $query->latest()->paginate(15)->withQueryString();
        
        return view('admin.contacts.index', compact('contactRequests'));
    }
    
    /**
     * Show a specific contact request.
     */
    public function showContactRequest(ContactRequest $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }
    
    /**
     * Update contact request status.
     */
    public function updateContactStatus(Request $request, ContactRequest $contact)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,processing,resolved',
            'admin_notes' => 'nullable|string',
        ]);
        
        $contact->update($validated);
        
        return redirect()->route('admin.contacts.show', $contact)
            ->with('success', 'Статус обращения успешно обновлен.');
    }
    
    /**
     * Delete a contact request.
     */
    public function deleteContactRequest(ContactRequest $contact)
    {
        $contact->delete();
        
        return redirect()->route('admin.contacts.index')
            ->with('success', 'Обращение успешно удалено.');
    }
    
    /*
    |--------------------------------------------------------------------------
    | Product Reviews Management
    |--------------------------------------------------------------------------
    */
    
    /**
     * Show reviews management page.
     */
    public function reviews(Request $request)
    {
        $query = Review::with(['product', 'user']);
        
        if ($request->has('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }
        
        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('product', function($q) use($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function($q) use($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $reviews = $query->latest()->paginate(15)->withQueryString();
        
        return view('admin.reviews.index', compact('reviews'));
    }
    
    /**
     * Show a specific review.
     */
    public function showReview(Review $review)
    {
        $review->load(['product', 'user']);
        return view('admin.reviews.show', compact('review'));
    }
    
    /**
     * Update review approval status.
     */
    public function updateReviewStatus(Request $request, Review $review)
    {
        $validated = $request->validate([
            'is_approved' => 'required|boolean',
            'admin_response' => 'nullable|string',
        ]);
        
        $review->update($validated);
        
        return redirect()->route('admin.reviews.index')
            ->with('success', 'Статус отзыва успешно обновлен.');
    }
    
    /**
     * Delete a review.
     */
    public function deleteReview(Review $review)
    {
        $review->delete();
        
        return redirect()->route('admin.reviews.index')
            ->with('success', 'Отзыв успешно удален.');
    }
    
    /*
    |--------------------------------------------------------------------------
    | Settings Management
    |--------------------------------------------------------------------------
    */
    
    /**
     * Show settings page.
     */
    public function settings()
    {
        return view('admin.settings.index');
    }
    
    /**
     * Update settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:1024',
        ]);
        
        foreach ($validated as $key => $value) {
            if ($key !== 'logo' && $key !== 'favicon') {
                // Store each setting in the database
                setting([$key => $value]);
            }
        }
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('settings', 'public');
            setting(['logo' => $logoPath]);
        }
        
        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            setting(['favicon' => $faviconPath]);
        }
        
        return redirect()->route('admin.settings')
            ->with('success', 'Настройки успешно обновлены.');
    }
}