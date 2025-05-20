<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    /**
     * Display the product attributes.
     */
    public function index()
    {
        $attributes = Attribute::paginate(10);
        return view('admin.attributes.index', compact('attributes'));
    }

    /**
     * Show the form to create a new attribute.
     */
    public function create()
    {
        return view('admin.attributes.create');
    }

    /**
     * Store a new attribute.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:select,radio,checkbox,color'
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
            'type' => 'required|in:select,radio,checkbox,color'
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
        $attribute->delete();
        return redirect()->route('admin.attributes.index')
            ->with('success', 'Атрибут успешно удален.');
    }

    /**
     * Display attribute values.
     */
    public function values(Attribute $attribute)
    {
        $values = $attribute->values()->paginate(10);
        return view('admin.attributes.values.index', compact('attribute', 'values'));
    }

    /**
     * Show form to create attribute value.
     */
    public function createValue(Attribute $attribute)
    {
        return view('admin.attributes.values.create', compact('attribute'));
    }

    /**
     * Store attribute value.
     */
    public function storeValue(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255',
        ]);

        $attribute->values()->create([
            'value' => $validated['value'],
            'type' => $attribute->type,
        ]);

        return redirect()->route('admin.attributes.values.index', $attribute)
            ->with('success', 'Значение успешно добавлено.');
    }

    /**
     * Delete attribute value.
     */
    public function deleteValue(Attribute $attribute, $valueId)
    {
        $value = $attribute->values()->findOrFail($valueId);
        $value->delete();

        return redirect()->route('admin.attributes.values.index', $attribute)
            ->with('success', 'Значение атрибута успешно удалено.');
    }
}