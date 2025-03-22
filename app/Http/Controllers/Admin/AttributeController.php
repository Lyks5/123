<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;

class AttributeController extends Controller
{


    
    /**
     * Display a listing of the attributes.
     */
    public function index()
    {
        $attributes = Attribute::withCount('values')->paginate(15);
        return view('admin.attributes.index', compact('attributes'));
    }
    
    /**
     * Show the form for creating a new attribute.
     */
    public function create()
    {
        return view('admin.attributes.create');
    }
    
    /**
     * Store a newly created attribute.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:select,radio,checkbox,color',
        ]);
        
        Attribute::create($validated);
        
        return redirect()->route('admin.attributes.index')
            ->with('success', 'Атрибут успешно создан.');
    }
    
    /**
     * Show the form for editing the specified attribute.
     */
    public function edit(Attribute $attribute)
    {
        return view('admin.attributes.edit', compact('attribute'));
    }
    
    /**
     * Update the specified attribute.
     */
    public function update(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:select,radio,checkbox,color',
        ]);
        
        $attribute->update($validated);
        
        return redirect()->route('admin.attributes.index')
            ->with('success', 'Атрибут успешно обновлен.');
    }
    
    /**
     * Remove the specified attribute.
     */
    public function destroy(Attribute $attribute)
    {
        // Check if attribute is used in any variants
        $inUse = $attribute->values()->whereHas('variants')->exists();
        
        if ($inUse) {
            return back()->withErrors(['error' => 'Нельзя удалить атрибут, который используется в вариантах товаров.']);
        }
        
        $attribute->values()->delete();
        $attribute->delete();
        
        return redirect()->route('admin.attributes.index')
            ->with('success', 'Атрибут успешно удален.');
    }
}