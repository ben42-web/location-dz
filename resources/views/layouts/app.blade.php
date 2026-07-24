<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Location DZ') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; margin: 0; background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); color: #e2e8f0; min-height: 100vh; }
        [x-cloak] { display: none !important; }
        a { color: #818cf8; text-decoration: none; }
        a:hover { color: #a5b4fc; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
        .glass { background: rgba(255,255,255,0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; }
        .btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer; border: none; transition: all 0.2s; }
        .btn-primary { background: #6366f1; color: white; }
        .btn-primary:hover { background: #4f46e5; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-danger:hover { background: #dc2626; }
        .btn-ghost { background: transparent; color: #a5b4fc; border: 1px solid rgba(255,255,255,0.2); }
        .btn-ghost:hover { background: rgba(255,255,255,0.1); }
        .input { width: 100%; padding: 0.625rem 0.875rem; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); border-radius: 8px; color: #e2e8f0; font-size: 0.875rem; outline: none; }
        .input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.2); }
        .input::placeholder { color: rgba(255,255,255,0.4); }
        .label { display: block; font-size: 0.8125rem; font-weight: 600; color: #94a3b8; margin-bottom: 0.375rem; }
        select.input { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 0.75rem center; }
        .flash-success { background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #4ade80; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; }
        .flash-error { background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; }
        .badge { display: inline-block; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .badge-pending { background: rgba(250,204,21,0.2); color: #facc15; }
        .badge-confirmed { background: rgba(34,197,94,0.2); color: #4ade80; }
        .badge-cancelled { background: rgba(239,68,68,0.2); color: #fca5a5; }
        .badge-completed { background: rgba(99,102,241,0.2); color: #818cf8; }
        .stars { color: #facc15; }
    </style>
</head>
<body>
    <nav style="background:rgba(0,0,0,0.3);border-bottom:1px solid rgba(255,255,255,0.08);padding:0.75rem 0;position:sticky;top:0;z-index:100;backdrop-filter:blur(10px);">
        <div class="container" style="display:flex;align-items:center;justify-content:space-between;">
            <a href="{{ route('home') }}" style="font-size:1.25rem;font-weight:700;color:#818cf8;display:flex;align-items:center;gap:0.5rem;">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3m10-11l2 2v8a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2"/></svg>
                Location DZ
            </a>
            <div style="display:flex;align-items:center;gap:1rem;">
                <a href="{{ route('properties.index') }}" style="color:#94a3b8;font-size:0.875rem;font-weight:500;">Explorer</a>
                @auth
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" style="color:#fca5a5;font-size:0.875rem;font-weight:600;">⚙ Admin</a>
                    @endif
                    <a href="{{ route('dashboard') }}" style="color:#94a3b8;font-size:0.875rem;font-weight:500;">Tableau de bord</a>
                    <a href="{{ route('messages.index') }}" style="color:#94a3b8;font-size:0.875rem;font-weight:500;">Messages</a>
                    <div x-data="{ open: false }" style="position:relative;">
                        <button @click="open = !open" style="display:flex;align-items:center;gap:0.5rem;color:#94a3b8;background:none;border:none;cursor:pointer;font-size:0.875rem;">
                            <div style="width:32px;height:32px;border-radius:50%;background:#6366f1;display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:0.8125rem;">{{ substr(auth()->user()->name, 0, 1) }}</div>
                            {{ auth()->user()->name }}
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak style="position:absolute;right:0;top:100%;margin-top:0.5rem;width:200px;background:rgba(30,27,75,0.95);border:1px solid rgba(255,255,255,0.1);border-radius:8px;padding:0.5rem;backdrop-filter:blur(10px);">
                            <a href="{{ route('dashboard') }}" style="display:block;padding:0.5rem 0.75rem;border-radius:6px;font-size:0.8125rem;color:#94a3b8;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">Mon tableau de bord</a>
                            <a href="{{ route('profile.edit') }}" style="display:block;padding:0.5rem 0.75rem;border-radius:6px;font-size:0.8125rem;color:#94a3b8;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">Mon profil</a>
                            <hr style="border:none;border-top:1px solid rgba(255,255,255,0.08);margin:0.375rem 0;">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" style="display:block;width:100%;text-align:left;padding:0.5rem 0.75rem;border-radius:6px;font-size:0.8125rem;color:#fca5a5;background:none;border:none;cursor:pointer;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">Déconnexion</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost" style="font-size:0.8125rem;">Connexion</a>
                    <a href="{{ route('register') }}" class="btn btn-primary" style="font-size:0.8125rem;">Inscription</a>
                @endauth
            </div>
        </div>
    </nav>

    @if (session('success'))
        <div class="container" style="padding-top:1rem;">
            <div class="flash-success" id="flash-msg">{{ session('success') }}</div>
        </div>
    @endif
    @if (session('error'))
        <div class="container" style="padding-top:1rem;">
            <div class="flash-error" id="flash-msg">{{ session('error') }}</div>
        </div>
    @endif

    <main style="padding:1.5rem 0;">
        @yield('content')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var flash = document.getElementById('flash-msg');
            if (flash) {
                setTimeout(function() { flash.style.transition = 'opacity 0.5s'; flash.style.opacity = '0'; setTimeout(function() { flash.remove(); }, 500); }, 4000);
            }
        });
    </script>
</body>
</html>
