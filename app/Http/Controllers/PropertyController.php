<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with(['user', 'images', 'amenities', 'reviews'])
            ->where('is_active', true);

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('wilaya')) {
            $query->where('wilaya', $request->wilaya);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('min_price')) {
            $query->where('price_per_night', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_night', '<=', $request->max_price);
        }

        if ($request->filled('guests')) {
            $query->where('max_guests', '>=', $request->guests);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $properties = $query->latest()->paginate(12);

        return view('properties.index', compact('properties'));
    }

    public function show(Property $property)
    {
        $property->load(['user', 'images', 'amenities', 'reviews.user', 'bookings' => function ($q) {
            $q->where('status', '!=', 'cancelled');
        }]);

        $related = Property::where('id', '!=', $property->id)
            ->where('city', $property->city)
            ->where('is_active', true)
            ->with(['images', 'reviews'])
            ->limit(3)
            ->get();

        $isFavorited = false;
        if (auth()->check()) {
            $isFavorited = $property->favorites()->where('user_id', auth()->id())->exists();
        }

        return view('properties.show', compact('property', 'related', 'isFavorited'));
    }

    public function create()
    {
        $amenities = Amenity::orderBy('name')->get();
        return view('properties.create', compact('amenities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:apartment,house,room,studio,villa,other',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'wilaya' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'price_per_night' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'amenities' => 'nullable|array',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $validated['user_id'] = auth()->id();

        $property = Property::create($validated);

        if (!empty($validated['amenities'])) {
            $property->amenities()->attach($validated['amenities']);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties', 'public');
                $property->images()->create([
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('properties.show', $property)
            ->with('success', 'Propriété publiée avec succès !');
    }

    public function edit(Property $property)
    {
        if ($property->user_id !== auth()->id()) {
            abort(403);
        }

        $property->load(['images', 'amenities']);
        $amenities = Amenity::orderBy('name')->get();

        return view('properties.edit', compact('property', 'amenities'));
    }

    public function update(Request $request, Property $property)
    {
        if ($property->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:apartment,house,room,studio,villa,other',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'wilaya' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'price_per_night' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'amenities' => 'nullable|array',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $property->update($validated);

        $property->amenities()->sync($validated['amenities'] ?? []);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties', 'public');
                $property->images()->create([
                    'image_path' => $path,
                    'is_primary' => $property->images()->count() === 0 && $index === 0,
                    'sort_order' => $property->images()->count() + $index,
                ]);
            }
        }

        return redirect()->route('properties.show', $property)
            ->with('success', 'Propriété mise à jour !');
    }

    public function destroy(Property $property)
    {
        if ($property->user_id !== auth()->id()) {
            abort(403);
        }

        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $property->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Propriété supprimée.');
    }

    public function destroyImage(Property $property, $imageId)
    {
        if ($property->user_id !== auth()->id()) {
            abort(403);
        }

        $image = $property->images()->findOrFail($imageId);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Image supprimée.');
    }

    public function toggleFavorite(Property $property)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $existing = $property->favorites()->where('user_id', auth()->id())->first();

        if ($existing) {
            $existing->delete();
            $favorited = false;
        } else {
            $property->favorites()->create(['user_id' => auth()->id()]);
            $favorited = true;
        }

        if (request()->ajax()) {
            return response()->json(['favorited' => $favorited]);
        }

        return back();
    }
}
