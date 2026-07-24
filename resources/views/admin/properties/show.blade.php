@extends('layouts.app', ['title' => 'Admin - ' . $property->title])

@section('content')
<div class="container" style="max-width:720px;">
    <a href="{{ route('admin.properties.index') }}" style="font-size:0.875rem;">← Annonces</a>

    <div class="glass" style="padding:1.5rem;margin-top:1rem;">
        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:1rem;">
            <div>
                <h1 style="font-size:1.25rem;font-weight:700;margin:0;">{{ $property->title }}</h1>
                <p style="color:#64748b;font-size:0.875rem;margin:0.25rem 0;">📍 {{ $property->address }}, {{ $property->city }}, {{ $property->wilaya }}</p>
            </div>
            <span class="badge {{ $property->is_active ? 'badge-confirmed' : 'badge-cancelled' }}">{{ $property->is_active ? 'Active' : 'Inactive' }}</span>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-bottom:1rem;">
            <div class="glass" style="padding:0.75rem;">
                <div style="color:#64748b;font-size:0.75rem;">Propriétaire</div>
                <a href="{{ route('admin.users.show', $property->user) }}" style="font-weight:500;">{{ $property->user->name }}</a>
            </div>
            <div class="glass" style="padding:0.75rem;">
                <div style="color:#64748b;font-size:0.75rem;">Type</div>
                <div style="font-weight:500;">{{ $property->propertyType->name ?? $property->type }}</div>
            </div>
            <div class="glass" style="padding:0.75rem;">
                <div style="color:#64748b;font-size:0.75rem;">Prix/nuit</div>
                <div style="font-weight:500;color:#818cf8;">{{ number_format($property->price_per_night, 0, ',', ' ') }} DA</div>
            </div>
            <div class="glass" style="padding:0.75rem;">
                <div style="color:#64748b;font-size:0.75rem;">Capacité</div>
                <div style="font-weight:500;">{{ $property->max_guests }} pers. · {{ $property->bedrooms }} ch. · {{ $property->bathrooms }} SdB</div>
            </div>
        </div>

        @if($property->description)
        <div style="margin-bottom:1rem;">
            <div style="font-weight:600;font-size:0.875rem;margin-bottom:0.25rem;">Description</div>
            <p style="color:#94a3b8;font-size:0.875rem;line-height:1.6;">{{ $property->description }}</p>
        </div>
        @endif

        @if($property->images->count())
        <div style="display:flex;gap:0.5rem;overflow-x:auto;margin-bottom:1rem;">
            @foreach($property->images as $img)
            <div style="width:120px;height:90px;border-radius:6px;overflow:hidden;flex-shrink:0;">
                <img src="{{ asset('storage/' . $img->image_path) }}" style="width:100%;height:100%;object-fit:cover;" alt="">
            </div>
            @endforeach
        </div>
        @endif

        @if($property->amenities->count())
        <div style="margin-bottom:1rem;">
            <div style="font-weight:600;font-size:0.875rem;margin-bottom:0.25rem;">Équipements</div>
            <div style="display:flex;flex-wrap:wrap;gap:0.375rem;">
                @foreach($property->amenities as $a)
                    <span class="glass" style="padding:0.25rem 0.625rem;font-size:0.8125rem;">{{ $a->name }}</span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Map --}}
        @if($property->latitude && $property->longitude)
        <div id="map" style="height:250px;border-radius:8px;overflow:hidden;border:1px solid rgba(255,255,255,0.1);margin-bottom:1rem;"></div>
        @endif

        {{-- Actions --}}
        <div style="display:flex;gap:0.75rem;margin-top:1rem;">
            <form method="POST" action="{{ route('admin.properties.toggle', $property) }}">
                @csrf
                <button type="submit" class="btn {{ $property->is_active ? 'btn-danger' : 'btn-primary' }}">{{ $property->is_active ? 'Désactiver' : 'Activer' }}</button>
            </form>
            <form method="POST" action="{{ route('admin.properties.destroy', $property) }}" onsubmit="return confirm('Supprimer cette annonce ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </div>
    </div>

    {{-- Bookings --}}
    @if($property->bookings->count())
    <h3 style="font-size:1rem;font-weight:600;margin:1.5rem 0 0.5rem;">Réservations ({{ $property->bookings->count() }})</h3>
    @foreach($property->bookings as $b)
    <div class="glass" style="padding:0.75rem;margin-bottom:0.5rem;display:flex;align-items:center;gap:0.75rem;">
        <div style="flex:1;">
            <div style="font-size:0.875rem;font-weight:500;">{{ $b->user->name }}</div>
            <div style="font-size:0.8125rem;color:#64748b;">{{ $b->check_in->format('d/m/Y') }} → {{ $b->check_out->format('d/m/Y') }} · {{ number_format($b->total_price, 0, ',', ' ') }} DA</div>
        </div>
        <span class="badge badge-{{ $b->status }}">{{ $b->status }}</span>
    </div>
    @endforeach
    @endif

    {{-- Reviews --}}
    @if($property->reviews->count())
    <h3 style="font-size:1rem;font-weight:600;margin:1.5rem 0 0.5rem;">Avis ({{ $property->reviews->count() }})</h3>
    @foreach($property->reviews as $r)
    <div class="glass" style="padding:0.75rem;margin-bottom:0.5rem;">
        <div style="display:flex;justify-content:space-between;margin-bottom:0.25rem;">
            <span style="font-weight:500;font-size:0.875rem;">{{ $r->user->name }}</span>
            <span class="stars" style="font-size:0.8125rem;">{{ str_repeat('★', $r->rating) }}{{ str_repeat('☆', 5 - $r->rating) }}</span>
        </div>
        @if($r->comment)
            <p style="color:#94a3b8;font-size:0.8125rem;margin:0;">{{ $r->comment }}</p>
        @endif
    </div>
    @endforeach
    @endif
</div>

@if($property->latitude && $property->longitude)
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('map').setView([{{ $property->latitude }}, {{ $property->longitude }}], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
    L.marker([{{ $property->latitude }}, {{ $property->longitude }}]).addTo(map).bindPopup('{{ addslashes($property->title) }}').openPopup();
});
</script>
@endif
@endsection
