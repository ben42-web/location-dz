@extends('layouts.app', ['title' => 'Admin - Types de biens'])

@section('content')
<div class="container" style="max-width:720px;">
    <h1 style="font-size:1.5rem;font-weight:700;margin-bottom:1rem;">Types de biens</h1>

    {{-- Add new type --}}
    <div class="glass" style="padding:1.25rem;margin-bottom:1.5rem;">
        <h3 style="font-size:1rem;font-weight:600;margin-bottom:0.75rem;">Ajouter un type</h3>
        <form method="POST" action="{{ route('admin.types.store') }}" style="display:flex;gap:0.75rem;align-items:end;">
            @csrf
            <div style="flex:1;">
                <label class="label">Nom du type</label>
                <input type="text" name="name" class="input" required placeholder="Ex: Riad">
                @error('name') <span style="color:#fca5a5;font-size:0.75rem;">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>

    {{-- List --}}
    @if($types->count())
    <div class="glass" style="overflow:hidden;">
        @foreach($types as $type)
        <div style="display:flex;align-items:center;gap:1rem;padding:0.875rem 1.25rem;{{ !$loop->last ? 'border-bottom:1px solid rgba(255,255,255,0.05);' : '' }}">
            <div style="flex:1;">
                <div style="font-weight:600;">{{ $type->name }}</div>
                <div style="font-size:0.75rem;color:#64748b;">{{ $type->properties_count }} annonce(s) · Slug: {{ $type->slug }}</div>
            </div>
            <span class="badge {{ $type->is_active ? 'badge-confirmed' : 'badge-cancelled' }}">{{ $type->is_active ? 'Actif' : 'Inactif' }}</span>
            <div style="display:flex;gap:0.375rem;">
                <form method="POST" action="{{ route('admin.types.toggle', $type) }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost" style="font-size:0.75rem;padding:0.25rem 0.5rem;color:{{ $type->is_active ? '#fca5a5' : '#4ade80' }};">{{ $type->is_active ? 'Désactiver' : 'Activer' }}</button>
                </form>
                @if($type->properties_count === 0)
                <form method="POST" action="{{ route('admin.types.destroy', $type) }}" onsubmit="return confirm('Supprimer ce type ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-ghost" style="font-size:0.75rem;padding:0.25rem 0.5rem;color:#fca5a5;">Supprimer</button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="glass" style="padding:3rem;text-align:center;">
        <p style="color:#64748b;">Aucun type créé. Ajoutez-en un ci-dessus.</p>
    </div>
    @endif
</div>
@endsection
