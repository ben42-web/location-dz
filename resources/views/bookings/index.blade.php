@extends('layouts.app', ['title' => 'Mes réservations'])

@section('content')
<div class="container" style="max-width:720px;">
    <h1 style="font-size:1.5rem;font-weight:700;margin-bottom:1rem;">Mes réservations</h1>

    @forelse($bookings as $booking)
    <a href="{{ route('bookings.show', $booking) }}" style="text-decoration:none;color:inherit;">
        <div class="glass" style="padding:1rem;margin-bottom:0.75rem;display:flex;gap:1rem;align-items:center;" onmouseover="this.style.borderColor='rgba(99,102,241,0.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'">
            <div style="width:80px;height:80px;border-radius:8px;overflow:hidden;flex-shrink:0;background:linear-gradient(135deg,rgba(99,102,241,0.2),rgba(168,85,247,0.2));">
                @if($booking->property->images->first())
                    <img src="{{ asset('storage/' . $booking->property->images->first()->image_path) }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                @endif
            </div>
            <div style="flex:1;">
                <div style="font-weight:600;font-size:0.9375rem;">{{ $booking->property->title }}</div>
                <div style="font-size:0.8125rem;color:#64748b;">{{ $booking->check_in->format('d/m/Y') }} → {{ $booking->check_out->format('d/m/Y') }}</div>
                <div style="font-size:0.8125rem;color:#94a3b8;">{{ number_format($booking->total_price, 0, ',', ' ') }} DA</div>
            </div>
            <span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
        </div>
    </a>
    @empty
    <div class="glass" style="padding:3rem;text-align:center;">
        <p style="color:#64748b;">Aucune réservation.</p>
        <a href="{{ route('properties.index') }}" class="btn btn-primary" style="margin-top:1rem;">Explorer les annonces</a>
    </div>
    @endforelse

    <div style="margin-top:1rem;text-align:center;">{{ $bookings->links() }}</div>
</div>
@endsection
