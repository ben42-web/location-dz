<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class AdminPropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with(['user', 'images', 'propertyType']);

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $properties = $query->latest()->paginate(15);

        return view('admin.properties.index', compact('properties'));
    }

    public function show(Property $property)
    {
        $property->load(['user', 'images', 'amenities', 'propertyType', 'reviews.user', 'bookings.user']);

        return view('admin.properties.show', compact('property'));
    }

    public function toggleActive(Property $property)
    {
        $property->update(['is_active' => !$property->is_active]);

        $status = $property->is_active ? 'activée' : 'désactivée';

        return back()->with('success', "Annonce {$status}.");
    }

    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()->route('admin.properties.index')
            ->with('success', 'Annonce supprimée.');
    }
}
