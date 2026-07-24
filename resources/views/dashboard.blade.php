@extends('layouts.app', ['title' => 'Tableau de bord'])

@section('content')
<div class="container">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
        <h1 style="font-size:1.5rem;font-weight:700;">Tableau de bord</h1>
        @if(auth()->user()->role !== 'guest')
        <a href="{{ route('properties.create') }}" class="btn btn-primary">+ Nouvelle annonce</a>
        @endif
    </div>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;margin-bottom:2rem;">
        @if(auth()->user()->role !== 'guest')
        <div class="glass" style="padding:1.25rem;text-align:center;">
            <div style="font-size:1.75rem;font-weight:700;color:#818cf8;">{{ $myProperties->count() }}</div>
            <div style="color:#64748b;font-size:0.8125rem;">Mes annonces</div>
        </div>
        <div class="glass" style="padding:1.25rem;text-align:center;">
            <div style="font-size:1.75rem;font-weight:700;color:#facc15;">{{ $receivedBookings->count() }}</div>
            <div style="color:#64748b;font-size:0.8125rem;">Réservations reçues</div>
        </div>
        <div class="glass" style="padding:1.25rem;text-align:center;">
            <div style="font-size:1.75rem;font-weight:700;color:#f87171;">{{ $pendingBookings }}</div>
            <div style="color:#64748b;font-size:0.8125rem;">En attente</div>
        </div>
        @else
        <div class="glass" style="padding:1.25rem;text-align:center;">
            <div style="font-size:1.75rem;font-weight:700;color:#818cf8;">{{ $myBookings->count() }}</div>
            <div style="color:#64748b;font-size:0.8125rem;">Mes réservations</div>
        </div>
        @endif
        <div class="glass" style="padding:1.25rem;text-align:center;">
            <div style="font-size:1.75rem;font-weight:700;color:#ef4444;">{{ $favorites->count() }}</div>
            <div style="color:#64748b;font-size:0.8125rem;">Favoris</div>
        </div>
    </div>

    {{-- My Properties (host only) --}}
    @if(auth()->user()->role !== 'guest')
    <h2 style="font-size:1.125rem;font-weight:600;margin-bottom:0.75rem;">Mes annonces</h2>
    @forelse($myProperties as $prop)
    <div class="glass" style="padding:1rem;margin-bottom:0.75rem;display:flex;gap:1rem;align-items:center;">
        <div style="width:80px;height:80px;border-radius:8px;overflow:hidden;flex-shrink:0;background:linear-gradient(135deg,rgba(99,102,241,0.2),rgba(168,85,247,0.2));">
            @if($prop->images->first())
                <img src="{{ asset('storage/' . $prop->images->first()->image_path) }}" style="width:100%;height:100%;object-fit:cover;" alt="">
            @endif
        </div>
        <div style="flex:1;">
            <div style="font-weight:600;">{{ $prop->title }}</div>
            <div style="font-size:0.8125rem;color:#64748b;">{{ $prop->city }} · {{ number_format($prop->price_per_night, 0, ',', ' ') }} DA/nuit · {{ $prop->bookings_count }} réservation(s)</div>
        </div>
        <a href="{{ route('properties.show', $prop) }}" class="btn btn-ghost" style="font-size:0.8125rem;">Voir</a>
        <a href="{{ route('properties.edit', $prop) }}" class="btn btn-ghost" style="font-size:0.8125rem;">Modifier</a>
    </div>
    @empty
    <p style="color:#64748b;margin-bottom:1.5rem;">Vous n'avez pas encore d'annonces.</p>
    @endforelse

    {{-- Réservations reçues (hôte) --}}
    <h2 style="font-size:1.125rem;font-weight:600;margin:1.5rem 0 0.75rem;">Réservations reçues</h2>
    @forelse($receivedBookings as $booking)
    <div class="glass" style="padding:1rem;margin-bottom:0.75rem;display:flex;gap:1rem;align-items:center;">
        <div style="flex:1;">
            <div style="font-weight:600;">{{ $booking->property->title }}</div>
            <div style="font-size:0.8125rem;color:#64748b;">{{ $booking->user->name }} · {{ $booking->check_in->format('d/m') }} → {{ $booking->check_out->format('d/m') }} · {{ number_format($booking->total_price, 0, ',', ' ') }} DA</div>
        </div>
        <span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-ghost" style="font-size:0.8125rem;">Détail</a>
    </div>
    @empty
    <p style="color:#64748b;">Aucune réservation reçue.</p>
    @endforelse
    @endif

    {{-- Mes réservations (guest) --}}
    @if(auth()->user()->role === 'guest')
    <h2 style="font-size:1.125rem;font-weight:600;margin-bottom:0.75rem;">Mes réservations</h2>
    @forelse($myBookings as $booking)
    <a href="{{ route('bookings.show', $booking) }}" style="text-decoration:none;color:inherit;">
        <div class="glass" style="padding:1rem;margin-bottom:0.75rem;display:flex;gap:1rem;align-items:center;" onmouseover="this.style.borderColor='rgba(99,102,241,0.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'">
            <div style="width:80px;height:80px;border-radius:8px;overflow:hidden;flex-shrink:0;background:linear-gradient(135deg,rgba(99,102,241,0.2),rgba(168,85,247,0.2));">
                @if($booking->property->images->first())
                    <img src="{{ asset('storage/' . $booking->property->images->first()->image_path) }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                @endif
            </div>
            <div style="flex:1;">
                <div style="font-weight:600;">{{ $booking->property->title }}</div>
                <div style="font-size:0.8125rem;color:#64748b;">{{ $booking->check_in->format('d/m/Y') }} → {{ $booking->check_out->format('d/m/Y') }} · {{ number_format($booking->total_price, 0, ',', ' ') }} DA</div>
            </div>
            <span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
        </div>
    </a>
    @empty
    <p style="color:#64748b;">Aucune réservation. <a href="{{ route('properties.index') }}">Explorer les annonces</a></p>
    @endforelse
    @endif

    {{-- Favorites --}}
    @if($favorites->count())
    <h2 style="font-size:1.125rem;font-weight:600;margin:1.5rem 0 0.75rem;">Mes favoris</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem;">
        @foreach($favorites as $fav)
            @include('properties._card', ['property' => $fav->property])
        @endforeach
    </div>
    @endif
</div>
@endsection
