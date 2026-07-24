@extends('layouts.app', ['title' => 'Admin - Réservations'])

@section('content')
<div class="container">
    <h1 style="font-size:1.5rem;font-weight:700;margin-bottom:1rem;">Gérer les réservations</h1>

    <form method="GET" class="glass" style="padding:1rem;margin-bottom:1rem;display:flex;gap:0.75rem;align-items:end;">
        <div style="width:200px;">
            <label class="label">Statut</label>
            <select name="status" class="input">
                <option value="">Tous</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Terminée</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulée</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrer</button>
    </form>

    @if($bookings->count())
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:0.875rem;">
            <thead>
                <tr style="border-bottom:1px solid rgba(255,255,255,0.1);text-align:left;">
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">ID</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Annonce</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Locataire</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Arrivée</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Départ</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Total</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Statut</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $b)
                <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">
                    <td style="padding:0.75rem;color:#64748b;">{{ $b->id }}</td>
                    <td style="padding:0.75rem;">
                        <a href="{{ route('admin.properties.show', $b->property) }}" style="color:#e2e8f0;font-weight:500;">{{ Str::limit($b->property->title ?? 'N/A', 30) }}</a>
                    </td>
                    <td style="padding:0.75rem;color:#94a3b8;">{{ $b->user->name }}</td>
                    <td style="padding:0.75rem;color:#94a3b8;">{{ $b->check_in->format('d/m/Y') }}</td>
                    <td style="padding:0.75rem;color:#94a3b8;">{{ $b->check_out->format('d/m/Y') }}</td>
                    <td style="padding:0.75rem;color:#818cf8;font-weight:600;">{{ number_format($b->total_price, 0, ',', ' ') }} DA</td>
                    <td style="padding:0.75rem;"><span class="badge badge-{{ $b->status }}">{{ $b->status }}</span></td>
                    <td style="padding:0.75rem;">
                        <div style="display:flex;gap:0.25rem;">
                            <a href="{{ route('admin.bookings.show', $b) }}" class="btn btn-ghost" style="font-size:0.75rem;padding:0.25rem 0.5rem;">Voir</a>
                            @if($b->status !== 'confirmed' && $b->status !== 'cancelled')
                            <form method="POST" action="{{ route('admin.bookings.status', [$b, 'confirmed']) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-ghost" style="font-size:0.75rem;padding:0.25rem 0.5rem;color:#4ade80;">Confirmer</button>
                            </form>
                            @endif
                            @if($b->status !== 'cancelled')
                            <form method="POST" action="{{ route('admin.bookings.status', [$b, 'cancelled']) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-ghost" style="font-size:0.75rem;padding:0.25rem 0.5rem;color:#fca5a5;">Annuler</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;text-align:center;">{{ $bookings->withQueryString()->links() }}</div>
    @else
    <div class="glass" style="padding:3rem;text-align:center;">
        <p style="color:#64748b;">Aucune réservation.</p>
    </div>
    @endif
</div>
@endsection
