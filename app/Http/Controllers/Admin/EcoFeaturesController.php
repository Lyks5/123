<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EcoFeature;
use App\Models\EcoFeatureProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EcoFeaturesController extends Controller
{
    /**
     * Constructor
     */
  

    /**
     * Display a listing of eco features
     */
    public function index()
    {
        $ecoFeatures = EcoFeature::paginate(10);
        return view('admin.eco-features.index', compact('ecoFeatures'));
    }

    /**
     * Show form for creating a new eco feature
     */
    public function create()
    {
        $products = Product::all();
        return view('admin.eco-features.create', compact('products'));
    }

    /**
     * Store a newly created eco feature
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:50',
            'impact_score' => 'required|integer|min:1|max:100',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id'
        ]);

        // Генерация slug
        $validated['slug'] = Str::slug($validated['name']);
        
        // Проверка уникальности slug
        $count = 1;
        $originalSlug = $validated['slug'];
        
        while (EcoFeature::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count++;
        }

        $ecoFeature = EcoFeature::create($validated);

        if ($request->has('products')) {
            $ecoFeature->products()->sync($request->products);
        }

        return redirect()->route('admin.eco-features.index')->with('success', 'Эко-характеристика успешно создана.');
    }

    /**
     * Show form for editing eco feature
     */
    public function edit(EcoFeature $ecoFeature)
    {
        $products = Product::all();
        return view('admin.eco-features.edit', compact('ecoFeature', 'products'));
    }

    /**
     * Update the specified eco feature
     */
    public function update(Request $request, EcoFeature $ecoFeature)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:50',
            'impact_score' => 'required|integer|min:1|max:100',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id'
        ]);

        // Обновление slug только если изменилось название
        if ($ecoFeature->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // Проверка уникальности slug
            $count = 1;
            $originalSlug = $validated['slug'];
            
            while (EcoFeature::where('slug', $validated['slug'])
                ->where('id', '!=', $ecoFeature->id)
                ->exists()) {
                $validated['slug'] = $originalSlug . '-' . $count++;
            }
        }

        $ecoFeature->update($validated);

        if ($request->has('products')) {
            $ecoFeature->products()->sync($request->products);
        }

        return redirect()->route('admin.eco-features.index')->with('success', 'Эко-характеристика успешно обновлена.');
    }

    /**
     * Delete the specified eco feature
     */
    public function destroy(EcoFeature $ecoFeature)
    {
        // Проверка наличия связанных продуктов
        if ($ecoFeature->products()->count() > 0) {
            return back()->withErrors(['general' => 'Нельзя удалить эко-характеристику, связанную с товарами.']);
        }

        $ecoFeature->products()->detach();
        $ecoFeature->delete();
        
        return redirect()->route('admin.eco-features.index')->with('success', 'Эко-характеристика успешно удалена.');
    }

    /**
     * Assign eco feature to product
     */
    public function assignToProduct(Request $request, EcoFeature $ecoFeature)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $ecoFeature->products()->syncWithoutDetaching([$validated['product_id']]);

        return back()->with('success', 'Eco feature assigned to product successfully.');
    }

    /**
     * Remove eco feature from product
     */
    public function removeFromProduct(Request $request, EcoFeature $ecoFeature)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $ecoFeature->products()->detach($validated['product_id']);

        return back()->with('success', 'Eco feature removed from product successfully.');
    }
}