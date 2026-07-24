@extends('layouts.app', ['title' => 'Publier une annonce'])

@section('content')
<div class="container" style="max-width:720px;">
    <h1 style="font-size:1.5rem;font-weight:700;margin-bottom:1rem;">Publier une annonce</h1>

    <form method="POST" action="{{ route('properties.store') }}" enctype="multipart/form-data" class="glass" style="padding:1.5rem;">
        @csrf

        <div style="margin-bottom:1rem;">
            <label class="label">Titre *</label>
            <input type="text" name="title" class="input" value="{{ old('title') }}" required placeholder="Ex: Bel appartement centre-ville">
            @error('title') <span style="color:#fca5a5;font-size:0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom:1rem;">
            <label class="label">Description</label>
            <textarea name="description" class="input" rows="4" placeholder="Décrivez votre bien...">{{ old('description') }}</textarea>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div>
                <label class="label">Type *</label>
                <select name="type" class="input" required>
                    @foreach(['apartment' => 'Appartement', 'house' => 'Maison', 'room' => 'Chambre', 'studio' => 'Studio', 'villa' => 'Villa', 'other' => 'Autre'] as $k => $v)
                        <option value="{{ $k }}" {{ old('type') === $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Prix/nuit (DA) *</label>
                <input type="number" name="price_per_night" class="input" value="{{ old('price_per_night') }}" required min="0">
            </div>
        </div>

        <div style="margin-bottom:1rem;">
            <label class="label">Adresse *</label>
            <input type="text" name="address" class="input" value="{{ old('address') }}" required placeholder="Rue, quartier...">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div>
                <label class="label">Ville *</label>
                <input type="text" name="city" class="input" value="{{ old('city') }}" required>
            </div>
            <div>
                <label class="label">Wilaya *</label>
                <input type="text" name="wilaya" class="input" value="{{ old('wilaya') }}" required>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div>
                <label class="label">Max voyageurs *</label>
                <input type="number" name="max_guests" class="input" value="{{ old('max_guests', 1) }}" min="1" required>
            </div>
            <div>
                <label class="label">Chambres *</label>
                <input type="number" name="bedrooms" class="input" value="{{ old('bedrooms', 1) }}" min="0" required>
            </div>
            <div>
                <label class="label">SdB *</label>
                <input type="number" name="bathrooms" class="input" value="{{ old('bathrooms', 1) }}" min="0" required>
            </div>
        </div>

        <div style="margin-bottom:1rem;">
            <label class="label">Latitude (optionnel)</label>
            <input type="text" name="latitude" class="input" value="{{ old('latitude') }}" placeholder="36.7538">
        </div>
        <div style="margin-bottom:1rem;">
            <label class="label">Longitude (optionnel)</label>
            <input type="text" name="longitude" class="input" value="{{ old('longitude') }}" placeholder="3.0588">
        </div>

        <div style="margin-bottom:1rem;">
            <label class="label">Équipements</label>
            <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
                @foreach($amenities as $a)
                <label style="display:flex;align-items:center;gap:0.375rem;padding:0.375rem 0.75rem;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:6px;cursor:pointer;font-size:0.8125rem;">
                    <input type="checkbox" name="amenities[]" value="{{ $a->id }}" {{ in_array($a->id, old('amenities', [])) ? 'checked' : '' }} style="accent-color:#6366f1;">
                    {{ $a->name }}
                </label>
                @endforeach
            </div>
        </div>

        <div style="margin-bottom:1.5rem;">
            <label class="label">Images (max 10)</label>
            <input type="file" name="images[]" multiple accept="image/*" class="input" style="padding:0.5rem;">
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:0.75rem;">Publier l'annonce</button>
    </form>
</div>
@endsection
