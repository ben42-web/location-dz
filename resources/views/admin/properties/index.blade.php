@extends('layouts.app', ['title' => 'Admin - Annonces'])

@section('content')
<div class="container">
    <h1 style="font-size:1.5rem;font-weight:700;margin-bottom:1rem;">Gérer les annonces</h1>

    <form method="GET" class="glass" style="padding:1rem;margin-bottom:1rem;display:flex;gap:0.75rem;align-items:end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label class="label">Recherche</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Titre, ville..." class="input">
        </div>
        <div style="width:150px;">
            <label class="label">Ville</label>
            <input type="text" name="city" value="{{ request('city') }}" class="input">
        </div>
        <div style="width:150px;">
            <label class="label">Statut</label>
            <select name="status" class="input">
                <option value="">Tous</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrer</button>
    </form>

    @if($properties->count())
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:0.875rem;">
            <thead>
                <tr style="border-bottom:1px solid rgba(255,255,255,0.1);text-align:left;">
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">ID</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Titre</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Propriétaire</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Ville</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Prix</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Statut</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($properties as $prop)
                <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">
                    <td style="padding:0.75rem;color:#64748b;">{{ $prop->id }}</td>
                    <td style="padding:0.75rem;">
                        <a href="{{ route('admin.properties.show', $prop) }}" style="font-weight:500;color:#e2e8f0;">{{ Str::limit($prop->title, 40) }}</a>
                    </td>
                    <td style="padding:0.75rem;color:#94a3b8;">{{ $prop->user->name }}</td>
                    <td style="padding:0.75rem;color:#94a3b8;">{{ $prop->city }}</td>
                    <td style="padding:0.75rem;color:#818cf8;font-weight:600;">{{ number_format($prop->price_per_night, 0, ',', ' ') }} DA</td>
                    <td style="padding:0.75rem;">
                        <span class="badge {{ $prop->is_active ? 'badge-confirmed' : 'badge-cancelled' }}">{{ $prop->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td style="padding:0.75rem;">
                        <div style="display:flex;gap:0.375rem;">
                            <a href="{{ route('admin.properties.show', $prop) }}" class="btn btn-ghost" style="font-size:0.75rem;padding:0.25rem 0.5rem;">Voir</a>
                            <form method="POST" action="{{ route('admin.properties.toggle', $prop) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-ghost" style="font-size:0.75rem;padding:0.25rem 0.5rem;color:{{ $prop->is_active ? '#fca5a5' : '#4ade80' }};">{{ $prop->is_active ? 'Désactiver' : 'Activer' }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;text-align:center;">{{ $properties->withQueryString()->links() }}</div>
    @else
    <div class="glass" style="padding:3rem;text-align:center;">
        <p style="color:#64748b;">Aucune annonce trouvée.</p>
    </div>
    @endif
</div>
@endsection
