<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeValueController extends Controller
{
    /**
     * Create a new controller instance.
     */
    
    
    /**
     * Display a listing of the attribute values.
     */
    public function index(Attribute $attribute)
    {
        $values = $attribute->values()->paginate(15);
        return view('admin.attributes.values.index', compact('attribute', 'values'));
    }
    
    /**
     * Show the form for creating a new attribute value.
     */
    public function create(Attribute $attribute)
    {
        return view('admin.attributes.values.create', compact('attribute'));
    }
    
    /**
     * Store a newly created attribute value.
     */
    public function store(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255',
        ]);
        
        $attribute->values()->create($validated);
        
        return redirect()->route('admin.attributes.values.index', $attribute->id)
            ->with('success', 'Значение атрибута успешно создано.');
    }
    
    /**
     * Show the form for editing the specified attribute value.
     */
    public function edit(Attribute $attribute, AttributeValue $value)
    {
        return view('admin.attributes.values.edit', compact('attribute', 'value'));
    }
    
    /**
     * Update the specified attribute value.
     */
    public function update(Request $request, Attribute $attribute, AttributeValue $value)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255',
        ]);
        
        $value->update($validated);
        
        return redirect()->route('admin.attributes.values.index', $attribute->id)
            ->with('success', 'Значение атрибута успешно обновлено.');
    }
    
    /**
     * Remove the specified attribute value.
     */
    public function destroy(Attribute $attribute, AttributeValue $value)
    {
        // Check if value is used in any variants
        if ($value->variants()->count() > 0) {
            return back()->withErrors(['error' => 'Нельзя удалить значение атрибута, которое используется в вариантах товаров.']);
        }
        
        $value->delete();
        
        return redirect()->route('admin.attributes.values.index', $attribute->id)
            ->with('success', 'Значение атрибута успешно удалено.');
    }
    
    /**
     * Get the values of an attribute (AJAX endpoint).
     */
    public function getValues(Attribute $attribute)
    {
        return $attribute->values;
    }
}