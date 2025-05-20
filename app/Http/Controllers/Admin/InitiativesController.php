<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EnvironmentalInitiative;
use Illuminate\Http\Request;

class InitiativesController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of environmental initiatives
     */
    public function index()
    {
        $initiatives = EnvironmentalInitiative::paginate(10);
        return view('admin.initiatives.index', compact('initiatives'));
    }

    /**
     * Show form for creating a new initiative
     */
    public function create()
    {
        return view('admin.initiatives.create');
    }

    /**
     * Store a newly created initiative
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|string|in:planned,in_progress,completed,cancelled',
            'impact_description' => 'required|string',
            'target_metric' => 'nullable|string|max:255',
            'target_value' => 'nullable|numeric',
            'current_value' => 'nullable|numeric',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('initiatives', 'public');
        }

        EnvironmentalInitiative::create($validated);

        return redirect()->route('admin.initiatives.index')->with('success', 'Initiative created successfully.');
    }

    /**
     * Show form for editing initiative
     */
    public function edit(EnvironmentalInitiative $initiative)
    {
        return view('admin.initiatives.edit', compact('initiative'));
    }

    /**
     * Update the specified initiative
     */
    public function update(Request $request, EnvironmentalInitiative $initiative)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|string|in:planned,in_progress,completed,cancelled',
            'impact_description' => 'required|string',
            'target_metric' => 'nullable|string|max:255',
            'target_value' => 'nullable|numeric',
            'current_value' => 'nullable|numeric',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($initiative->image) {
                \Storage::disk('public')->delete($initiative->image);
            }
            $validated['image'] = $request->file('image')->store('initiatives', 'public');
        }

        $initiative->update($validated);

        return redirect()->route('admin.initiatives.index')->with('success', 'Initiative updated successfully.');
    }

    /**
     * Delete the specified initiative
     */
    public function destroy(EnvironmentalInitiative $initiative)
    {
        if ($initiative->image) {
            \Storage::disk('public')->delete($initiative->image);
        }
        
        $initiative->delete();
        return redirect()->route('admin.initiatives.index')->with('success', 'Initiative deleted successfully.');
    }

    /**
     * Update initiative status
     */
    public function updateStatus(Request $request, EnvironmentalInitiative $initiative)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:planned,in_progress,completed,cancelled'
        ]);

        $initiative->update($validated);

        return back()->with('success', 'Initiative status updated successfully.');
    }
}