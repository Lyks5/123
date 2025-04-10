<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Attribute $attribute)
    {
        $values = $attribute->values()
            ->withCount('variants')
            ->paginate(15);
            
        return view('admin.attributes.values.index', compact('attribute', 'values'));
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255',
        ]);
        
        $attribute->values()->create($validated);
        
        return redirect()->route('admin.attributes.values.index', $attribute->id)
            ->with('success', 'Значение атрибута успешно добавлено.');
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attribute $attribute, AttributeValue $value)
    {
        return view('admin.attributes.values.edit', compact('attribute', 'value'));
    }
    
    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute, AttributeValue $value)
    {
        // Check if the value is used in any variants
        $inUse = $value->variants()->exists();
        
        if ($inUse) {
            return back()->withErrors(['error' => 'Нельзя удалить значение, которое используется в вариантах товаров.']);
        }
        
        $value->delete();
        
        return redirect()->route('admin.attributes.values.index', $attribute->id)
            ->with('success', 'Значение атрибута успешно удалено.');
    }
}