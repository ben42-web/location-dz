@extends('layouts.app', ['title' => 'Modifier l\'annonce'])

@section('content')
<div class="container" style="max-width:720px;">
    <h1 style="font-size:1.5rem;font-weight:700;margin-bottom:1rem;">Modifier l'annonce</h1>

    <form method="POST" action="{{ route('properties.update', $property) }}" enctype="multipart/form-data" class="glass" style="padding:1.5rem;">
        @csrf
        @method('PUT')

        <div style="margin-bottom:1rem;">
            <label class="label">Titre *</label>
            <input type="text" name="title" class="input" value="{{ old('title', $property->title) }}" required>
            @error('title') <span style="color:#fca5a5;font-size:0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom:1rem;">
            <label class="label">Description</label>
            <textarea name="description" class="input" rows="4">{{ old('description', $property->description) }}</textarea>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div>
                <label class="label">Type *</label>
                <select name="type" class="input" required>
                    @foreach(['apartment' => 'Appartement', 'house' => 'Maison', 'room' => 'Chambre', 'studio' => 'Studio', 'villa' => 'Villa', 'other' => 'Autre'] as $k => $v)
                        <option value="{{ $k }}" {{ old('type', $property->type) === $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Prix/nuit (DA) *</label>
                <input type="number" name="price_per_night" class="input" value="{{ old('price_per_night', $property->price_per_night) }}" required min="0">
            </div>
        </div>

        <div style="margin-bottom:1rem;">
            <label class="label">Adresse *</label>
            <input type="text" name="address" class="input" value="{{ old('address', $property->address) }}" required>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div>
                <label class="label">Ville *</label>
                <input type="text" name="city" class="input" value="{{ old('city', $property->city) }}" required>
            </div>
            <div>
                <label class="label">Wilaya *</label>
                <input type="text" name="wilaya" class="input" value="{{ old('wilaya', $property->wilaya) }}" required>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div>
                <label class="label">Max voyageurs *</label>
                <input type="number" name="max_guests" class="input" value="{{ old('max_guests', $property->max_guests) }}" min="1" required>
            </div>
            <div>
                <label class="label">Chambres *</label>
                <input type="number" name="bedrooms" class="input" value="{{ old('bedrooms', $property->bedrooms) }}" min="0" required>
            </div>
            <div>
                <label class="label">SdB *</label>
                <input type="number" name="bathrooms" class="input" value="{{ old('bathrooms', $property->bathrooms) }}" min="0" required>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div>
                <label class="label">Latitude</label>
                <input type="text" name="latitude" class="input" value="{{ old('latitude', $property->latitude) }}">
            </div>
            <div>
                <label class="label">Longitude</label>
                <input type="text" name="longitude" class="input" value="{{ old('longitude', $property->longitude) }}">
            </div>
        </div>

        <div style="margin-bottom:1rem;">
            <label class="label">Équipements</label>
            <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
                @foreach($amenities as $a)
                <label style="display:flex;align-items:center;gap:0.375rem;padding:0.375rem 0.75rem;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:6px;cursor:pointer;font-size:0.8125rem;">
                    <input type="checkbox" name="amenities[]" value="{{ $a->id }}" {{ $property->amenities->pluck('id')->contains($a->id) ? 'checked' : '' }} style="accent-color:#6366f1;">
                    {{ $a->name }}
                </label>
                @endforeach
            </div>
        </div>

        {{-- Current images --}}
        @if($property->images->count())
        <div style="margin-bottom:1rem;">
            <label class="label">Images actuelles</label>
            <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
                @foreach($property->images as $img)
                <div style="position:relative;width:100px;height:100px;">
                    <img src="{{ asset('storage/' . $img->image_path) }}" style="width:100%;height:100%;object-fit:cover;border-radius:6px;" alt="">
                    @if($img->is_primary)
                    <span style="position:absolute;bottom:2px;left:2px;background:rgba(0,0,0,0.7);padding:0.125rem 0.375rem;border-radius:4px;font-size:0.625rem;color:#4ade80;">Principal</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div style="margin-bottom:1.5rem;">
            <label class="label">Ajouter des images</label>
            <input type="file" name="images[]" multiple accept="image/*" class="input" style="padding:0.5rem;">
        </div>

        <div style="display:flex;gap:0.75rem;">
            <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;padding:0.75rem;">Mettre à jour</button>
            <a href="{{ route('properties.show', $property) }}" class="btn btn-ghost" style="padding:0.75rem;">Annuler</a>
        </div>
    </form>

    {{-- Delete --}}
    <form method="POST" action="{{ route('properties.destroy', $property) }}" style="margin-top:1rem;" onsubmit="return confirm('Supprimer cette annonce ?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;">Supprimer l'annonce</button>
    </form>
</div>
@endsection
