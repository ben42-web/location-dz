@extends('layouts.app', ['title' => 'Admin - ' . $user->name])

@section('content')
<div class="container" style="max-width:720px;">
    <a href="{{ route('admin.users.index') }}" style="font-size:0.875rem;">← Utilisateurs</a>

    <div class="glass" style="padding:1.5rem;margin-top:1rem;">
        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:1rem;">
            <div>
                <h1 style="font-size:1.25rem;font-weight:700;margin:0;">{{ $user->name }}</h1>
                <p style="color:#64748b;font-size:0.875rem;margin:0.25rem 0;">{{ $user->email }}</p>
            </div>
            <span class="badge" style="background:{{ $user->role === 'admin' ? 'rgba(239,68,68,0.2)' : ($user->role === 'host' ? 'rgba(34,197,94,0.2)' : 'rgba(99,102,241,0.2)') }};color:{{ $user->role === 'admin' ? '#fca5a5' : ($user->role === 'host' ? '#4ade80' : '#818cf8') }};font-size:0.875rem;">{{ $user->role }}</span>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div>
                    <label class="label">Nom</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input" required>
                </div>
                <div>
                    <label class="label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input" required>
                </div>
            </div>
            <div style="margin-bottom:1rem;">
                <label class="label">Rôle</label>
                <select name="role" class="input" style="max-width:200px;">
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="host" {{ $user->role === 'host' ? 'selected' : '' }}>Hôte</option>
                    <option value="guest" {{ $user->role === 'guest' ? 'selected' : '' }}>Locataire</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0.75rem;margin-top:1rem;">
        <div class="glass" style="padding:1rem;text-align:center;">
            <div style="font-size:1.5rem;font-weight:700;color:#818cf8;">{{ $user->properties_count ?? $user->properties->count() }}</div>
            <div style="color:#64748b;font-size:0.8125rem;">Annonces</div>
        </div>
        <div class="glass" style="padding:1rem;text-align:center;">
            <div style="font-size:1.5rem;font-weight:700;color:#facc15;">{{ $user->bookings->count() }}</div>
            <div style="color:#64748b;font-size:0.8125rem;">Réservations</div>
        </div>
        <div class="glass" style="padding:1rem;text-align:center;">
            <div style="font-size:1.5rem;font-weight:700;color:#34d399;">{{ $user->reviews->count() }}</div>
            <div style="color:#64748b;font-size:0.8125rem;">Avis</div>
        </div>
    </div>

    {{-- Properties --}}
    @if($user->properties->count())
    <h3 style="font-size:1rem;font-weight:600;margin:1.5rem 0 0.5rem;">Annonces de {{ $user->name }}</h3>
    @foreach($user->properties as $prop)
    <div class="glass" style="padding:0.75rem;margin-bottom:0.5rem;display:flex;align-items:center;gap:0.75rem;">
        <div style="width:60px;height:60px;border-radius:6px;overflow:hidden;background:linear-gradient(135deg,rgba(99,102,241,0.2),rgba(168,85,247,0.2));flex-shrink:0;">
            @if($prop->images->first())
                <img src="{{ asset('storage/' . $prop->images->first()->image_path) }}" style="width:100%;height:100%;object-fit:cover;" alt="">
            @endif
        </div>
        <div style="flex:1;">
            <a href="{{ route('admin.properties.show', $prop) }}" style="font-weight:500;">{{ $prop->title }}</a>
            <div style="font-size:0.8125rem;color:#64748b;">{{ $prop->city }} · {{ number_format($prop->price_per_night, 0, ',', ' ') }} DA</div>
        </div>
        <span class="badge {{ $prop->is_active ? 'badge-confirmed' : 'badge-cancelled' }}">{{ $prop->is_active ? 'Active' : 'Inactive' }}</span>
    </div>
    @endforeach
    @endif

    {{-- Delete --}}
    @if($user->id !== auth()->id())
    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="margin-top:1.5rem;" onsubmit="return confirm('Supprimer cet utilisateur et toutes ses données ?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;">Supprimer l'utilisateur</button>
    </form>
    @endif
</div>
@endsection
