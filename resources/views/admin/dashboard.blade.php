@extends('layouts.app', ['title' => 'Admin - Tableau de bord'])

@section('content')
<div class="container">
    <h1 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem;">Tableau de bord administrateur</h1>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:0.75rem;margin-bottom:2rem;">
        <div class="glass" style="padding:1.25rem;text-align:center;">
            <div style="font-size:1.75rem;font-weight:700;color:#818cf8;">{{ $stats['users'] }}</div>
            <div style="color:#64748b;font-size:0.8125rem;">Utilisateurs</div>
        </div>
        <div class="glass" style="padding:1.25rem;text-align:center;">
            <div style="font-size:1.75rem;font-weight:700;color:#34d399;">{{ $stats['hosts'] }}</div>
            <div style="color:#64748b;font-size:0.8125rem;">Hôtes</div>
        </div>
        <div class="glass" style="padding:1.25rem;text-align:center;">
            <div style="font-size:1.75rem;font-weight:700;color:#facc15;">{{ $stats['properties'] }}</div>
            <div style="color:#64748b;font-size:0.8125rem;">Annonces</div>
        </div>
        <div class="glass" style="padding:1.25rem;text-align:center;">
            <div style="font-size:1.75rem;font-weight:700;color:#38bdf8;">{{ $stats['active_properties'] }}</div>
            <div style="color:#64748b;font-size:0.8125rem;">Actives</div>
        </div>
        <div class="glass" style="padding:1.25rem;text-align:center;">
            <div style="font-size:1.75rem;font-weight:700;color:#f87171;">{{ $stats['bookings'] }}</div>
            <div style="color:#64748b;font-size:0.8125rem;">Réservations</div>
        </div>
        <div class="glass" style="padding:1.25rem;text-align:center;">
            <div style="font-size:1.75rem;font-weight:700;color:#fbbf24;">{{ $stats['pending_bookings'] }}</div>
            <div style="color:#64748b;font-size:0.8125rem;">En attente</div>
        </div>
        <div class="glass" style="padding:1.25rem;text-align:center;">
            <div style="font-size:1.75rem;font-weight:700;color:#a78bfa;">{{ number_format($stats['total_revenue'], 0, ',', ' ') }}</div>
            <div style="color:#64748b;font-size:0.8125rem;">Revenu (DA)</div>
        </div>
        <div class="glass" style="padding:1.25rem;text-align:center;">
            <div style="font-size:1.75rem;font-weight:700;color:#fb923c;">{{ $stats['types'] }}</div>
            <div style="color:#64748b;font-size:0.8125rem;">Types biens</div>
        </div>
    </div>

    {{-- Quick links --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:0.75rem;margin-bottom:2rem;">
        <a href="{{ route('admin.users.index') }}" class="glass" style="padding:1.25rem;display:flex;align-items:center;gap:0.75rem;text-decoration:none;" onmouseover="this.style.borderColor='rgba(99,102,241,0.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'">
            <span style="font-size:1.5rem;">👥</span>
            <div>
                <div style="font-weight:600;color:#e2e8f0;">Gérer les utilisateurs</div>
                <div style="font-size:0.75rem;color:#64748b;">{{ $stats['users'] }} comptes</div>
            </div>
        </a>
        <a href="{{ route('admin.properties.index') }}" class="glass" style="padding:1.25rem;display:flex;align-items:center;gap:0.75rem;text-decoration:none;" onmouseover="this.style.borderColor='rgba(99,102,241,0.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'">
            <span style="font-size:1.5rem;">🏠</span>
            <div>
                <div style="font-weight:600;color:#e2e8f0;">Gérer les annonces</div>
                <div style="font-size:0.75rem;color:#64748b;">{{ $stats['properties'] }} biens</div>
            </div>
        </a>
        <a href="{{ route('admin.types.index') }}" class="glass" style="padding:1.25rem;display:flex;align-items:center;gap:0.75rem;text-decoration:none;" onmouseover="this.style.borderColor='rgba(99,102,241,0.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'">
            <span style="font-size:1.5rem;">🏷️</span>
            <div>
                <div style="font-weight:600;color:#e2e8f0;">Types de biens</div>
                <div style="font-size:0.75rem;color:#64748b;">{{ $stats['types'] }} types</div>
            </div>
        </a>
        <a href="{{ route('admin.bookings.index') }}" class="glass" style="padding:1.25rem;display:flex;align-items:center;gap:0.75rem;text-decoration:none;" onmouseover="this.style.borderColor='rgba(99,102,241,0.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'">
            <span style="font-size:1.5rem;">📅</span>
            <div>
                <div style="font-weight:600;color:#e2e8f0;">Réservations</div>
                <div style="font-size:0.75rem;color:#64748b;">{{ $stats['bookings'] }} total</div>
            </div>
        </a>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
        {{-- Recent Users --}}
        <div class="glass" style="padding:1.25rem;">
            <h3 style="font-size:1rem;font-weight:600;margin-bottom:0.75rem;">Utilisateurs récents</h3>
            @foreach($recentUsers as $user)
            <div style="display:flex;align-items:center;gap:0.75rem;padding:0.5rem 0;border-bottom:1px solid rgba(255,255,255,0.05);">
                <div style="width:32px;height:32px;border-radius:50%;background:#6366f1;display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:0.75rem;">{{ substr($user->name, 0, 1) }}</div>
                <div style="flex:1;">
                    <div style="font-size:0.875rem;font-weight:500;">{{ $user->name }}</div>
                    <div style="font-size:0.75rem;color:#64748b;">{{ $user->email }}</div>
                </div>
                <span class="badge" style="background:{{ $user->role === 'admin' ? 'rgba(239,68,68,0.2)' : ($user->role === 'host' ? 'rgba(34,197,94,0.2)' : 'rgba(99,102,241,0.2)') }};color:{{ $user->role === 'admin' ? '#fca5a5' : ($user->role === 'host' ? '#4ade80' : '#818cf8') }};">{{ $user->role }}</span>
            </div>
            @endforeach
        </div>

        {{-- Recent Bookings --}}
        <div class="glass" style="padding:1.25rem;">
            <h3 style="font-size:1rem;font-weight:600;margin-bottom:0.75rem;">Réservations récentes</h3>
            @foreach($recentBookings as $b)
            <div style="display:flex;align-items:center;gap:0.75rem;padding:0.5rem 0;border-bottom:1px solid rgba(255,255,255,0.05);">
                <div style="flex:1;">
                    <div style="font-size:0.875rem;font-weight:500;">{{ $b->property->title ?? 'N/A' }}</div>
                    <div style="font-size:0.75rem;color:#64748b;">{{ $b->user->name }} · {{ $b->check_in->format('d/m') }}→{{ $b->check_out->format('d/m') }}</div>
                </div>
                <span class="badge badge-{{ $b->status }}">{{ $b->status }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
