<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryManagementController extends Controller
{
    /**
     * Constructor
     */
 

    /**
     * Display a listing of categories
     */
    public function index(Request $request)
    {
        $query = Category::with('parent')->withCount('products');
        
        // Фильтр по поиску
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Фильтр по статусу
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        // Фильтр по родительской категории
        if ($request->filled('parent')) {
            if ($request->parent === 'root') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $request->parent);
            }
        }
        
        $categories = $query->paginate(15)->withQueryString();
        $parentCategories = Category::whereNull('parent_id')->get();
        
        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    /**
     * Show form for creating a new category
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'parent_id' => 'nullable|exists:categories,id',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'boolean',
            ]);
            
            // Set boolean values
            $validated['is_active'] = $request->boolean('is_active');
            
            // Handle image upload
            if ($request->hasFile('image')) {
                try {
                    $validated['image'] = $request->file('image')->store('categories', 'public');
                } catch (\Exception $e) {
                    return back()
                        ->withInput()
                        ->withErrors(['image' => 'Ошибка при загрузке изображения. Пожалуйста, попробуйте другой файл.']);
                }
            }
        
            Category::create($validated);
            
            return redirect()->route('admin.categories.index')
                ->with('success', 'Категория успешно создана.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['general' => 'Произошла ошибка при создании категории.']);
        }
    }

    /**
     * Show form for editing category
     */
    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'parent_id' => 'nullable|exists:categories,id',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);
            
            // Check for circular dependency
            if ($validated['parent_id'] == $category->id) {
                return back()->withErrors(['parent_id' => 'Категория не может быть родителем самой себя.']);
            }
            
            // Set boolean values
            $validated['is_active'] = $request->boolean('is_active');
            
            $category->update($validated);
            
            return redirect()->route('admin.categories.index')
                ->with('success', 'Категория успешно обновлена.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['general' => 'Произошла ошибка при обновлении категории.']);
        }
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно обновлена.');
    }

    /**
     * Delete the specified category
     */
    public function destroy(Category $category)
    {
        // Check if category has children
        if ($category->children()->count() > 0) {
            return back()->withErrors(['general' => 'Нельзя удалить категорию, содержащую подкатегории.']);
        }
        
        // Check if category has products
        if ($category->products()->count() > 0) {
            return back()->withErrors(['general' => 'Нельзя удалить категорию, содержащую товары.']);
        }
        
        // Delete image from storage
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно удалена.');
    }
}