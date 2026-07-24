@extends('layouts.app', ['title' => 'Admin - Réservation #' . $booking->id])

@section('content')
<div class="container" style="max-width:720px;">
    <a href="{{ route('admin.bookings.index') }}" style="font-size:0.875rem;">← Réservations</a>

    <div class="glass" style="padding:1.5rem;margin-top:1rem;">
        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:1rem;">
            <div>
                <h1 style="font-size:1.25rem;font-weight:700;margin:0;">Réservation #{{ $booking->id }}</h1>
                <p style="color:#64748b;font-size:0.875rem;margin:0.25rem 0;">
                    <a href="{{ route('admin.properties.show', $booking->property) }}">{{ $booking->property->title ?? 'N/A' }}</a>
                </p>
            </div>
            <span class="badge badge-{{ $booking->status }}" style="font-size:0.875rem;">{{ ucfirst($booking->status) }}</span>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-bottom:1rem;">
            <div class="glass" style="padding:0.75rem;">
                <div style="color:#64748b;font-size:0.75rem;">Locataire</div>
                <a href="{{ route('admin.users.show', $booking->user) }}" style="font-weight:500;">{{ $booking->user->name }}</a>
                <div style="font-size:0.75rem;color:#64748b;">{{ $booking->user->email }}</div>
            </div>
            <div class="glass" style="padding:0.75rem;">
                <div style="color:#64748b;font-size:0.75rem;">Propriétaire</div>
                <div style="font-weight:500;">{{ $booking->property->user->name ?? 'N/A' }}</div>
            </div>
            <div class="glass" style="padding:0.75rem;">
                <div style="color:#64748b;font-size:0.75rem;">Arrivée → Départ</div>
                <div style="font-weight:500;">{{ $booking->check_in->format('d/m/Y') }} → {{ $booking->check_out->format('d/m/Y') }}</div>
            </div>
            <div class="glass" style="padding:0.75rem;">
                <div style="color:#64748b;font-size:0.75rem;">Total</div>
                <div style="font-weight:500;color:#818cf8;">{{ number_format($booking->total_price, 0, ',', ' ') }} DA</div>
            </div>
        </div>

        {{-- Actions --}}
        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-top:1rem;">
            @if(!in_array($booking->status, ['confirmed', 'cancelled']))
                <form method="POST" action="{{ route('admin.bookings.status', [$booking, 'confirmed']) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Confirmer</button>
                </form>
            @endif
            @if($booking->status !== 'cancelled')
                <form method="POST" action="{{ route('admin.bookings.status', [$booking, 'cancelled']) }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">Annuler</button>
                </form>
            @endif
            @if($booking->status !== 'completed')
                <form method="POST" action="{{ route('admin.bookings.status', [$booking, 'completed']) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Terminer</button>
                </form>
            @endif
            @if($booking->status !== 'pending')
                <form method="POST" action="{{ route('admin.bookings.status', [$booking, 'pending']) }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost">Remettre en attente</button>
                </form>
            @endif
        </div>
    </div>

    @if($booking->review)
    <div class="glass" style="padding:1.25rem;margin-top:1rem;">
        <h3 style="font-size:1rem;font-weight:600;margin-bottom:0.5rem;">Avis du locataire</h3>
        <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;">
            <span style="font-weight:500;">{{ $booking->review->user->name }}</span>
            <span class="stars">{{ str_repeat('★', $booking->review->rating) }}{{ str_repeat('☆', 5 - $booking->review->rating) }}</span>
        </div>
        @if($booking->review->comment)
            <p style="color:#94a3b8;font-size:0.875rem;">{{ $booking->review->comment }}</p>
        @endif
    </div>
    @endif
</div>
@endsection
