@extends('layouts.app', ['title' => 'Admin - Utilisateurs'])

@section('content')
<div class="container">
    <h1 style="font-size:1.5rem;font-weight:700;margin-bottom:1rem;">Gérer les utilisateurs</h1>

    <form method="GET" class="glass" style="padding:1rem;margin-bottom:1rem;display:flex;gap:0.75rem;align-items:end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label class="label">Recherche</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Nom, email..." class="input">
        </div>
        <div style="width:150px;">
            <label class="label">Rôle</label>
            <select name="role" class="input">
                <option value="">Tous</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="host" {{ request('role') === 'host' ? 'selected' : '' }}>Hôte</option>
                <option value="guest" {{ request('role') === 'guest' ? 'selected' : '' }}>Locataire</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrer</button>
    </form>

    @if($users->count())
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:0.875rem;">
            <thead>
                <tr style="border-bottom:1px solid rgba(255,255,255,0.1);text-align:left;">
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">ID</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Nom</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Email</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Rôle</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Annonces</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Réservations</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Inscrit</th>
                    <th style="padding:0.75rem;color:#94a3b8;font-weight:600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">
                    <td style="padding:0.75rem;color:#64748b;">{{ $user->id }}</td>
                    <td style="padding:0.75rem;font-weight:500;">
                        <a href="{{ route('admin.users.show', $user) }}" style="color:#e2e8f0;">{{ $user->name }}</a>
                    </td>
                    <td style="padding:0.75rem;color:#94a3b8;">{{ $user->email }}</td>
                    <td style="padding:0.75rem;">
                        <span class="badge" style="background:{{ $user->role === 'admin' ? 'rgba(239,68,68,0.2)' : ($user->role === 'host' ? 'rgba(34,197,94,0.2)' : 'rgba(99,102,241,0.2)') }};color:{{ $user->role === 'admin' ? '#fca5a5' : ($user->role === 'host' ? '#4ade80' : '#818cf8') }};">{{ $user->role }}</span>
                    </td>
                    <td style="padding:0.75rem;color:#64748b;text-align:center;">{{ $user->properties_count }}</td>
                    <td style="padding:0.75rem;color:#64748b;text-align:center;">{{ $user->bookings_count }}</td>
                    <td style="padding:0.75rem;color:#64748b;">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td style="padding:0.75rem;">
                        <div style="display:flex;gap:0.375rem;">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-ghost" style="font-size:0.75rem;padding:0.25rem 0.5rem;">Voir</a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.toggle-role', $user) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-ghost" style="font-size:0.75rem;padding:0.25rem 0.5rem;">Basculer rôle</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;text-align:center;">{{ $users->withQueryString()->links() }}</div>
    @else
    <div class="glass" style="padding:3rem;text-align:center;">
        <p style="color:#64748b;">Aucun utilisateur trouvé.</p>
    </div>
    @endif
</div>
@endsection
