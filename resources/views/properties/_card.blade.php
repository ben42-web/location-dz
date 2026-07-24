@php
    $img = $property->images->first();
    $avg = $property->average_rating;
    $count = $property->review_count;
@endphp
<a href="{{ route('properties.show', $property) }}" style="text-decoration:none;color:inherit;">
    <div class="glass" style="overflow:hidden;transition:all 0.2s;" onmouseover="this.style.borderColor='rgba(99,102,241,0.4)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)';this.style.transform='none'">
        <div style="aspect-ratio:16/10;background:linear-gradient(135deg,rgba(99,102,241,0.2),rgba(168,85,247,0.2));position:relative;overflow:hidden;">
            @if($img)
                <img src="{{ asset('storage/' . $img->image_path) }}" alt="{{ $property->title }}" style="width:100%;height:100%;object-fit:cover;">
            @else
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.3);">
                    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3m10-11l2 2v8a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2"/></svg>
                </div>
            @endif
            <div style="position:absolute;top:0.75rem;right:0.75rem;background:rgba(0,0,0,0.6);padding:0.25rem 0.5rem;border-radius:6px;font-size:0.75rem;font-weight:600;backdrop-filter:blur(4px);">
                {{ $property->type }}
            </div>
        </div>
        <div style="padding:1rem;">
            <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:0.5rem;">
                <h3 style="font-size:1rem;font-weight:600;margin:0;color:#e2e8f0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $property->title }}</h3>
                @if($avg)
                    <span style="display:flex;align-items:center;gap:0.25rem;font-size:0.8125rem;font-weight:600;color:#facc15;white-space:nowrap;margin-left:0.5rem;">
                        ★ {{ $avg }}
                    </span>
                @endif
            </div>
            <div style="font-size:0.8125rem;color:#64748b;margin-bottom:0.5rem;">
                📍 {{ $property->city }}, {{ $property->wilaya }}
            </div>
            <div style="font-size:0.8125rem;color:#94a3b8;margin-bottom:0.75rem;">
                {{ $property->max_guests }} pers. · {{ $property->bedrooms }} ch. · {{ $property->bathrooms }} SdB
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid rgba(255,255,255,0.08);padding-top:0.75rem;">
                <span style="font-size:1.125rem;font-weight:700;color:#818cf8;">{{ number_format($property->price_per_night, 0, ',', ' ') }} DA</span>
                <span style="font-size:0.75rem;color:#64748b;">/ nuit</span>
            </div>
        </div>
    </div>
</a>
