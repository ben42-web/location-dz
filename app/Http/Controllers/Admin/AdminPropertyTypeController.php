<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPropertyTypeController extends Controller
{
    public function index()
    {
        $types = PropertyType::withCount('properties')->latest()->get();

        return view('admin.types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:property_types,name',
        ]);

        PropertyType::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return back()->with('success', 'Type ajouté.');
    }

    public function update(Request $request, PropertyType $type)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:property_types,name,' . $type->id,
        ]);

        $type->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return back()->with('success', 'Type mis à jour.');
    }

    public function destroy(PropertyType $type)
    {
        if ($type->properties()->count() > 0) {
            return back()->withErrors(['error' => 'Ce type est utilisé par des annonces. Supprimez-les d\'abord.']);
        }

        $type->delete();

        return back()->with('success', 'Type supprimé.');
    }

    public function toggleActive(PropertyType $type)
    {
        $type->update(['is_active' => !$type->is_active]);

        return back()->with('success', $type->is_active ? 'Type activé.' : 'Type désactivé.');
    }
}
