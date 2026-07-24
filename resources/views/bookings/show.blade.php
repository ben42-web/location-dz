@extends('layouts.app', ['title' => 'Réservation #' . $booking->id])

@section('content')
<div class="container" style="max-width:720px;">
    <a href="{{ route('bookings.index') }}" style="font-size:0.875rem;">← Mes réservations</a>

    <div class="glass" style="padding:1.5rem;margin-top:1rem;">
        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:1rem;">
            <div>
                <h1 style="font-size:1.25rem;font-weight:700;margin:0;">{{ $booking->property->title }}</h1>
                <p style="color:#64748b;font-size:0.875rem;margin:0.25rem 0;">📍 {{ $booking->property->city }}, {{ $booking->property->wilaya }}</p>
            </div>
            <span class="badge badge-{{ $booking->status }}" style="font-size:0.875rem;">{{ ucfirst($booking->status) }}</span>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div class="glass" style="padding:1rem;">
                <div style="color:#64748b;font-size:0.75rem;text-transform:uppercase;">Arrivée</div>
                <div style="font-weight:600;">{{ $booking->check_in->format('d/m/Y') }}</div>
            </div>
            <div class="glass" style="padding:1rem;">
                <div style="color:#64748b;font-size:0.75rem;text-transform:uppercase;">Départ</div>
                <div style="font-weight:600;">{{ $booking->check_out->format('d/m/Y') }}</div>
            </div>
            <div class="glass" style="padding:1rem;">
                <div style="color:#64748b;font-size:0.75rem;text-transform:uppercase;">Voyageurs</div>
                <div style="font-weight:600;">{{ $booking->guests }}</div>
            </div>
            <div class="glass" style="padding:1rem;">
                <div style="color:#64748b;font-size:0.75rem;text-transform:uppercase;">Total</div>
                <div style="font-weight:600;color:#818cf8;">{{ number_format($booking->total_price, 0, ',', ' ') }} DA</div>
            </div>
        </div>

        @if($booking->notes)
        <div style="margin-bottom:1rem;">
            <span style="font-size:0.8125rem;color:#64748b;">Notes : </span>
            <span style="font-size:0.875rem;">{{ $booking->notes }}</span>
        </div>
        @endif

        @if($booking->property->images->first())
        <div style="border-radius:8px;overflow:hidden;margin-bottom:1rem;">
            <img src="{{ asset('storage/' . $booking->property->images->first()->image_path) }}" style="width:100%;height:250px;object-fit:cover;" alt="">
        </div>
        @endif

        {{-- Actions --}}
        <div style="display:flex;gap:0.75rem;margin-top:1rem;">
            @if(in_array($booking->status, ['pending', 'confirmed']) && $booking->user_id === auth()->id())
                <form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('Annuler cette réservation ?')">
                    @csrf
                    <button type="submit" class="btn btn-danger">Annuler</button>
                </form>
            @endif

            @if($booking->status === 'pending' && $booking->property->user_id === auth()->id())
                <form method="POST" action="{{ route('bookings.confirm', $booking) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Confirmer</button>
                </form>
            @endif

            @if($booking->status === 'confirmed' && $booking->property->user_id === auth()->id())
                <form method="POST" action="{{ route('bookings.complete', $booking) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Marquer terminé</button>
                </form>
            @endif

            @if($booking->user_id === auth()->id())
                <a href="{{ route('messages.show', $booking->property->user_id) }}" class="btn btn-ghost">Contacter l'hôte</a>
            @elseif($booking->property->user_id === auth()->id())
                <a href="{{ route('messages.show', $booking->user_id) }}" class="btn btn-ghost">Contacter le locataire</a>
            @endif
        </div>
    </div>

    {{-- Review --}}
    @if($booking->status === 'completed' && $booking->user_id === auth()->id())
    <div class="glass" style="padding:1.5rem;margin-top:1rem;">
        <h3 style="font-size:1.125rem;font-weight:600;margin-bottom:1rem;">Laisser un avis</h3>
        @if($booking->review)
            <div style="margin-bottom:0.5rem;">
                <span class="stars">{{ str_repeat('★', $booking->review->rating) }}{{ str_repeat('☆', 5 - $booking->review->rating) }}</span>
            </div>
            @if($booking->review->comment)
                <p style="color:#94a3b8;font-size:0.875rem;">{{ $booking->review->comment }}</p>
            @endif
        @else
        <form method="POST" action="{{ route('reviews.store') }}">
            @csrf
            <input type="hidden" name="property_id" value="{{ $booking->property_id }}">
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
            <div style="margin-bottom:0.75rem;">
                <label class="label">Note</label>
                <select name="rating" class="input" style="width:auto;">
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }} ★</option>
                    @endfor
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label class="label">Commentaire</label>
                <textarea name="comment" class="input" rows="3" placeholder="Partagez votre expérience...">{{ old('comment') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer l'avis</button>
        </form>
        @endif
    </div>
    @endif
</div>
@endsection
