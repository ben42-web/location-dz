@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Hero --}}
    <div style="text-align:center;padding:3rem 0 2rem;">
        <h1 style="font-size:2.5rem;font-weight:800;margin-bottom:0.75rem;">
            Trouvez votre <span style="color:#818cf8;">séjour</span> en Algérie
        </h1>
        <p style="color:#94a3b8;max-width:600px;margin:0 auto 1.5rem;font-size:1.05rem;">
            Des milliers de maisons, appartements et villas à louer partout en Algérie.
        </p>

        {{-- Search Bar --}}
        <form action="{{ route('properties.index') }}" method="GET" style="max-width:700px;margin:0 auto;">
            <div style="display:flex;gap:0.5rem;">
                <input type="text" name="q" placeholder="Rechercher une ville, un quartier..." class="input" style="flex:1;padding:0.875rem 1rem;font-size:1rem;">
                <button type="submit" class="btn btn-primary" style="padding:0.875rem 1.5rem;font-size:1rem;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                    Rechercher
                </button>
            </div>
        </form>
    </div>

    {{-- Cities --}}
    @if($cities->count())
    <div style="margin-bottom:2.5rem;">
        <h2 style="font-size:1.25rem;font-weight:700;margin-bottom:1rem;">Villes populaires</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:0.75rem;">
            @foreach($cities as $city)
                <a href="{{ route('properties.index', ['city' => $city->city]) }}" class="glass" style="padding:1rem;text-align:center;transition:all 0.2s;text-decoration:none;" onmouseover="this.style.borderColor='rgba(99,102,241,0.5)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'">
                    <div style="font-size:1rem;font-weight:600;color:#e2e8f0;">{{ $city->city }}</div>
                    <div style="font-size:0.75rem;color:#64748b;margin-top:0.25rem;">{{ $city->count }} annonces</div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Featured --}}
    @if($featured->count())
    <div style="margin-bottom:2.5rem;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <h2 style="font-size:1.25rem;font-weight:700;">Annonces en vedette</h2>
            <a href="{{ route('properties.index') }}" style="font-size:0.875rem;">Voir tout →</a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:1rem;">
            @foreach($featured as $prop)
                @include('properties._card', ['property' => $prop])
            @endforeach
        </div>
    </div>
    @endif

    {{-- Recent --}}
    @if($recent->count())
    <div style="margin-bottom:2.5rem;">
        <h2 style="font-size:1.25rem;font-weight:700;margin-bottom:1rem;">Annonces récentes</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:1rem;">
            @foreach($recent as $prop)
                @include('properties._card', ['property' => $prop])
            @endforeach
        </div>
    </div>
    @endif

    {{-- CTA --}}
    <div class="glass" style="padding:2rem;text-align:center;margin-bottom:2rem;">
        <h2 style="font-size:1.5rem;font-weight:700;margin-bottom:0.5rem;">Vous avez un bien à louer ?</h2>
        <p style="color:#94a3b8;margin-bottom:1rem;">Publiez votre annonce gratuitement et trouvez des locataires.</p>
        <a href="{{ route('properties.create') }}" class="btn btn-primary" style="padding:0.75rem 1.5rem;">Publier une annonce</a>
    </div>
</div>
@endsection
