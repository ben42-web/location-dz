@extends('layouts.app', ['title' => $property->title])

@section('content')
<div class="container" style="max-width:960px;">
    <a href="{{ route('properties.index') }}" style="font-size:0.875rem;display:inline-flex;align-items:center;gap:0.25rem;margin-bottom:1rem;">← Retour aux annonces</a>

    {{-- Images --}}
    @if($property->images->count())
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:0.5rem;border-radius:12px;overflow:hidden;margin-bottom:1.5rem;max-height:400px;">
        <div style="background:linear-gradient(135deg,rgba(99,102,241,0.2),rgba(168,85,247,0.2));">
            <img src="{{ asset('storage/' . $property->images->first()->image_path) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
        </div>
        @if($property->images->count() > 1)
        <div style="display:grid;grid-template-rows:1fr 1fr;gap:0.5rem;">
            @foreach($property->images->slice(1, 2) as $img)
            <div style="background:linear-gradient(135deg,rgba(99,102,241,0.2),rgba(168,85,247,0.2));">
                <img src="{{ asset('storage/' . $img->image_path) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @endif

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem;">
        {{-- Left --}}
        <div>
            <div style="display:flex;align-items:start;justify-content:space-between;margin-bottom:0.75rem;">
                <div>
                    <h1 style="font-size:1.5rem;font-weight:700;margin:0;">{{ $property->title }}</h1>
                    <p style="color:#64748b;font-size:0.875rem;margin:0.25rem 0 0;">
                        📍 {{ $property->address }}, {{ $property->city }}, {{ $property->wilaya }}
                    </p>
                </div>
                @auth
                <form method="POST" action="{{ route('properties.favorite', $property) }}">
                    @csrf
                    <button type="submit" style="background:none;border:none;cursor:pointer;font-size:1.5rem;color:{{ $isFavorited ? '#ef4444' : '#64748b' }};">{{ $isFavorited ? '♥' : '♡' }}</button>
                </form>
                @endauth
            </div>

            <div style="display:flex;gap:1.5rem;color:#94a3b8;font-size:0.875rem;margin-bottom:1rem;">
                <span>👤 {{ $property->max_guests }} voyageurs</span>
                <span>🛏️ {{ $property->bedrooms }} chambre{{ $property->bedrooms > 1 ? 's' : '' }}</span>
                <span>🚿 {{ $property->bathrooms }} SdB</span>
                <span class="badge badge-confirmed">{{ $property->type }}</span>
            </div>

            <hr style="border:none;border-top:1px solid rgba(255,255,255,0.08);margin:1rem 0;">

            <h3 style="font-size:1rem;font-weight:600;margin-bottom:0.5rem;">Description</h3>
            <p style="color:#94a3b8;line-height:1.7;white-space:pre-wrap;">{{ $property->description ?: 'Pas de description.' }}</p>

            {{-- Amenities --}}
            @if($property->amenities->count())
            <h3 style="font-size:1rem;font-weight:600;margin:1.5rem 0 0.5rem;">Équipements</h3>
            <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
                @foreach($property->amenities as $a)
                    <span class="glass" style="padding:0.375rem 0.75rem;font-size:0.8125rem;">{{ $a->name }}</span>
                @endforeach
            </div>
            @endif

            {{-- Map --}}
            @if($property->latitude && $property->longitude)
            <h3 style="font-size:1rem;font-weight:600;margin:1.5rem 0 0.5rem;">Localisation</h3>
            <div id="map" style="height:300px;border-radius:12px;overflow:hidden;border:1px solid rgba(255,255,255,0.1);"></div>
            @endif

            {{-- Reviews --}}
            <h3 style="font-size:1rem;font-weight:600;margin:1.5rem 0 0.5rem;">
                Avis ({{ $property->review_count }})
                @if($property->average_rating)
                    <span class="stars">★ {{ $property->average_rating }}</span>
                @endif
            </h3>
            @forelse($property->reviews as $review)
            <div class="glass" style="padding:1rem;margin-bottom:0.75rem;">
                <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;">
                    <strong style="font-size:0.875rem;">{{ $review->user->name }}</strong>
                    <span class="stars" style="font-size:0.8125rem;">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                </div>
                @if($review->comment)
                    <p style="color:#94a3b8;font-size:0.8125rem;margin:0;">{{ $review->comment }}</p>
                @endif
            </div>
            @empty
            <p style="color:#64748b;font-size:0.875rem;">Aucun avis pour le moment.</p>
            @endforelse

            {{-- Related --}}
            @if($related->count())
            <h3 style="font-size:1rem;font-weight:600;margin:1.5rem 0 0.5rem;">Annonces similaires à {{ $property->city }}</h3>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem;">
                @foreach($related as $r)
                    @include('properties._card', ['property' => $r])
                @endforeach
            </div>
            @endif
        </div>

        {{-- Right: Booking Card --}}
        <div>
            <div class="glass" style="padding:1.5rem;position:sticky;top:5rem;">
                <div style="display:flex;align-items:baseline;gap:0.5rem;margin-bottom:1rem;">
                    <span style="font-size:1.5rem;font-weight:700;color:#818cf8;">{{ number_format($property->price_per_night, 0, ',', ' ') }} DA</span>
                    <span style="color:#64748b;font-size:0.875rem;">/ nuit</span>
                </div>

                @auth
                <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm">
                    @csrf
                    <input type="hidden" name="property_id" value="{{ $property->id }}">
                    <div style="margin-bottom:0.75rem;">
                        <label class="label">Arrivée</label>
                        <input type="date" name="check_in" id="checkIn" class="input" min="{{ date('Y-m-d') }}" required value="{{ old('check_in') }}">
                        @error('check_in') <span style="color:#fca5a5;font-size:0.75rem;">{{ $message }}</span> @enderror
                    </div>
                    <div style="margin-bottom:0.75rem;">
                        <label class="label">Départ</label>
                        <input type="date" name="check_out" id="checkOut" class="input" required value="{{ old('check_out') }}">
                        @error('check_out') <span style="color:#fca5a5;font-size:0.75rem;">{{ $message }}</span> @enderror
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label class="label">Voyageurs</label>
                        <input type="number" name="guests" class="input" min="1" max="{{ $property->max_guests }}" value="{{ old('guests', 1) }}">
                    </div>
                    @if(count($bookedDates))
                    <div style="margin-bottom:0.75rem;padding:0.5rem 0.75rem;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);border-radius:6px;">
                        <div style="font-size:0.75rem;color:#fca5a5;font-weight:600;margin-bottom:0.25rem;">Dates indisponibles :</div>
                        <div style="font-size:0.6875rem;color:#94a3b8;" id="bookedRanges"></div>
                    </div>
                    @endif
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:0.75rem;">Réserver</button>
                </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary" style="width:100%;justify-content:center;">Connectez-vous pour réserver</a>
                @endauth

                <div style="text-align:center;margin-top:0.75rem;color:#64748b;font-size:0.8125rem;">
                    Proposé par <strong style="color:#e2e8f0;">{{ $property->user->name }}</strong>
                    @auth
                        <a href="{{ route('messages.show', $property->user_id) }}" style="display:block;margin-top:0.5rem;font-size:0.8125rem;">Envoyer un message</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

@if($property->latitude && $property->longitude)
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('map').setView([{{ $property->latitude }}, {{ $property->longitude }}], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);
    L.marker([{{ $property->latitude }}, {{ $property->longitude }}]).addTo(map)
        .bindPopup('{{ addslashes($property->title) }}').openPopup();
});
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    var bookedDates = @json($bookedDates);
    var checkIn = document.getElementById('checkIn');
    var checkOut = document.getElementById('checkOut');
    var form = document.getElementById('bookingForm');
    var rangesEl = document.getElementById('bookedRanges');

    if (!checkIn || !checkOut) return;

    // Afficher les périodes indisponibles
    if (rangesEl && bookedDates.length > 0) {
        var ranges = [];
        var sorted = bookedDates.slice().sort();
        var start = sorted[0], prev = sorted[0];
        for (var i = 1; i < sorted.length; i++) {
            var prevDate = new Date(prev);
            var currDate = new Date(sorted[i]);
            var diff = (currDate - prevDate) / (1000 * 60 * 60 * 24);
            if (diff === 1) {
                prev = sorted[i];
            } else {
                ranges.push(formatRange(start, prev));
                start = sorted[i];
                prev = sorted[i];
            }
        }
        ranges.push(formatRange(start, prev));
        rangesEl.textContent = ranges.join(' · ');
    }

    function formatRange(a, b) {
        var da = new Date(a + 'T00:00:00');
        var db = new Date(b + 'T00:00:00');
        var opts = { day: '2-digit', month: '2-digit' };
        if (a === b) return da.toLocaleDateString('fr-FR', opts);
        return da.toLocaleDateString('fr-FR', opts) + ' → ' + db.toLocaleDateString('fr-FR', opts);
    }

    function isBooked(dateStr) {
        return bookedDates.indexOf(dateStr) !== -1;
    }

    function isInRange(start, end) {
        var s = new Date(start + 'T00:00:00');
        var e = new Date(end + 'T00:00:00');
        var d = new Date(s);
        while (d <= e) {
            var ds = d.toISOString().slice(0, 10);
            if (isBooked(ds)) return true;
            d.setDate(d.getDate() + 1);
        }
        return false;
    }

    // Bloquer check_in si date prise
    checkIn.addEventListener('change', function() {
        if (isBooked(this.value)) {
            alert('Cette date n\'est pas disponible.');
            this.value = '';
            return;
        }
        if (checkOut.value && this.value >= checkOut.value) {
            checkOut.value = '';
        }
        updateCheckOutMin();
    });

    // Bloquer check_out si date prise ou si avant check_in
    checkOut.addEventListener('change', function() {
        if (isBooked(this.value)) {
            alert('Cette date n\'est pas disponible.');
            this.value = '';
            return;
        }
        if (checkIn.value && this.value <= checkIn.value) {
            alert('La date de départ doit être après la date d\'arrivée.');
            this.value = '';
            return;
        }
        if (checkIn.value && isInRange(checkIn.value, this.value)) {
            alert('La période sélectionnée chevauche des dates indisponibles.');
            this.value = '';
            return;
        }
    });

    function updateCheckOutMin() {
        if (checkIn.value) {
            var next = new Date(checkIn.value + 'T00:00:00');
            next.setDate(next.getDate() + 1);
            checkOut.min = next.toISOString().slice(0, 10);
        }
    }

    // Vérifier au submit
    if (form) {
        form.addEventListener('submit', function(e) {
            if (isBooked(checkIn.value)) {
                e.preventDefault();
                alert('La date d\'arrivée n\'est pas disponible.');
                return;
            }
            if (isBooked(checkOut.value)) {
                e.preventDefault();
                alert('La date de départ n\'est pas disponible.');
                return;
            }
            if (checkIn.value && checkOut.value && isInRange(checkIn.value, checkOut.value)) {
                e.preventDefault();
                alert('La période sélectionnée contient des dates indisponibles.');
                return;
            }
        });
    }
});
</script>
@endsection
