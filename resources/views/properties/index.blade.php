@extends('layouts.app', ['title' => 'Explorer les annonces'])

@section('content')
<div class="container">
    <h1 style="font-size:1.5rem;font-weight:700;margin-bottom:1rem;">Annonces</h1>

    {{-- Filters --}}
    <form method="GET" action="{{ route('properties.index') }}" class="glass" style="padding:1rem;margin-bottom:1.5rem;">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:0.75rem;align-items:end;">
            <div>
                <label class="label">Recherche</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Titre, ville..." class="input">
            </div>
            <div>
                <label class="label">Wilaya</label>
                <input type="text" name="wilaya" value="{{ request('wilaya') }}" placeholder="Ex: Alger" class="input">
            </div>
            <div>
                <label class="label">Type</label>
                <select name="type" class="input">
                    <option value="">Tous</option>
                    @foreach(['apartment' => 'Appartement', 'house' => 'Maison', 'room' => 'Chambre', 'studio' => 'Studio', 'villa' => 'Villa', 'other' => 'Autre'] as $k => $v)
                        <option value="{{ $k }}" {{ request('type') === $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Prix min (DA)</label>
                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0" class="input">
            </div>
            <div>
                <label class="label">Prix max (DA)</label>
                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="100000" class="input">
            </div>
            <div>
                <label class="label">Voyageurs</label>
                <input type="number" name="guests" value="{{ request('guests') }}" min="1" placeholder="1" class="input">
            </div>
            <div>
                <button type="submit" class="btn btn-primary" style="width:100%;">Filtrer</button>
            </div>
        </div>
    </form>

    @if($properties->count())
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:1rem;">
            @foreach($properties as $property)
                @include('properties._card', ['property' => $property])
            @endforeach
        </div>
        <div style="margin-top:1.5rem;text-align:center;">
            {{ $properties->withQueryString()->links() }}
        </div>
    @else
        <div class="glass" style="padding:3rem;text-align:center;">
            <p style="color:#64748b;font-size:1.125rem;">Aucune annonce trouvée.</p>
            <a href="{{ route('properties.index') }}" class="btn btn-ghost" style="margin-top:1rem;">Réinitialiser les filtres</a>
        </div>
    @endif
</div>
@endsection
