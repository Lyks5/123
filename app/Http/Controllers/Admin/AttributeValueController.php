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
            ->orderBy('display_order')
            ->paginate(15);
            
        return view('admin.attributes.values.index', compact('attribute', 'values'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create(Attribute $attribute)
    {
        return view('admin.attributes.values.create', compact('attribute'));
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255',
        ]);
        
        // If this is a color attribute, store the color value as hex_color too
        $data = $validated;
        if ($attribute->type === 'color') {
            $data['hex_color'] = $validated['value'];
        }
        
        // Find the highest display_order and add 1
        $maxOrder = $attribute->values()->max('display_order') ?? 0;
        $data['display_order'] = $maxOrder + 1;
        
        $attribute->values()->create($data);
        
        return redirect()->route('admin.attributes.values.index', $attribute->id)
            ->with('success', 'Значение атрибута успешно добавлено.');
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attribute $attribute, AttributeValue $value)
    {
        $value->loadCount('variants');
        return view('admin.attributes.values.edit', compact('attribute', 'value'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attribute $attribute, AttributeValue $value)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255',
            'display_order' => 'nullable|integer|min:0',
        ]);
        
        // If this is a color attribute, update the hex_color too
        $data = $validated;
        if ($attribute->type === 'color') {
            $data['hex_color'] = $validated['value'];
        }
        
        // If display_order is not provided, keep the existing one
        if (!isset($data['display_order'])) {
            $data['display_order'] = $value->display_order;
        }
        
        $value->update($data);
        
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
    
    /**
     * Get values for the specified attribute
     * Used for AJAX requests
     */
    public function getValues(Attribute $attribute)
    {
        $values = $attribute->values()->orderBy('display_order')->get();
        return response()->json($values);
    }
    
    /**
     * Update display order of values
     */
    public function updateOrder(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'values' => 'required|array',
            'values.*.id' => 'required|exists:attribute_values,id',
            'values.*.order' => 'required|integer|min:0',
        ]);
        
        foreach ($validated['values'] as $item) {
            AttributeValue::where('id', $item['id'])
                ->where('attribute_id', $attribute->id)
                ->update(['display_order' => $item['order']]);
        }
        
        return response()->json(['success' => true]);
    }
}