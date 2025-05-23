<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttributeRequest;
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
    public function store(AttributeRequest $request)
    {
        try {
            $attribute = Attribute::create($request->validated());

            return response()->json([
                'message' => 'Атрибут успешно создан',
                'redirect' => route('admin.attributes.index')
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'errors' => [
                    'name' => ['Атрибут с таким названием уже существует']
                ]
            ], 422);
        }
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
    public function update(AttributeRequest $request, Attribute $attribute)
    {
        try {
            $attribute->update($request->validated());

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Атрибут успешно обновлен',
                    'redirect' => route('admin.attributes.index')
                ]);
            }

            return redirect()->route('admin.attributes.index')
                ->with('success', 'Атрибут успешно обновлен.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'errors' => [
                        'name' => ['Атрибут с таким названием уже существует']
                    ]
                ], 422);
            }

            return back()->withErrors(['name' => 'Атрибут с таким названием уже существует'])->withInput();
        }
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
     * Show form to edit attribute value.
     */
    public function editValue(Attribute $attribute, $valueId)
    {
        $value = $attribute->values()->findOrFail($valueId);
        return view('admin.attributes.values.edit', compact('attribute', 'value'));
    }

    /**
     * Store attribute value.
     */
    public function storeValue(Request $request, Attribute $attribute)
    {
        $validationRules = [
            'value' => 'required|string|max:255',
            'display_order' => 'nullable|integer|min:0',
        ];

        if ($attribute->type === 'color') {
            $validationRules['hex_color'] = 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/';
        }

        $validated = $request->validate($validationRules);

        // Получаем максимальный display_order
        $maxOrder = $attribute->values()->max('display_order') ?? 0;

        $data = [
            'value' => $validated['value'],
            'display_order' => $validated['display_order'] ?? ($maxOrder + 1),
        ];

        if ($attribute->type === 'color' && isset($validated['hex_color'])) {
            $data['hex_color'] = $validated['hex_color'];
        }

        $attribute->values()->create($data);

        return redirect()->route('admin.attributes.values.index', $attribute)
            ->with('success', 'Значение успешно добавлено.');
    }

    /**
     * Update attribute value.
     */
    public function updateValue(Request $request, Attribute $attribute, $valueId)
    {
        $value = $attribute->values()->findOrFail($valueId);
        
        $validationRules = [
            'value' => 'required|string|max:255',
            'display_order' => 'nullable|integer|min:0',
        ];

        if ($attribute->type === 'color') {
            $validationRules['hex_color'] = 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/';
        }

        $validated = $request->validate($validationRules);

        $data = [
            'value' => $validated['value'],
            'display_order' => $validated['display_order'] ?? $value->display_order,
        ];

        if ($attribute->type === 'color' && isset($validated['hex_color'])) {
            $data['hex_color'] = $validated['hex_color'];
        }

        $value->update($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Значение атрибута успешно обновлено',
                'redirect' => route('admin.attributes.values.index', $attribute)
            ]);
        }

        return redirect()->route('admin.attributes.values.index', $attribute)
            ->with('success', 'Значение атрибута успешно обновлено.');
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