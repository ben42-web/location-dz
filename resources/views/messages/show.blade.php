@extends('layouts.app', ['title' => 'Chat avec ' . $otherUser->name])

@section('content')
<div class="container" style="max-width:720px;">
    <a href="{{ route('messages.index') }}" style="font-size:0.875rem;">← Messages</a>
    <h2 style="font-size:1.125rem;font-weight:600;margin:0.5rem 0 1rem;">{{ $otherUser->name }}</h2>

    <div class="glass" style="padding:1rem;max-height:500px;overflow-y:auto;margin-bottom:1rem;">
        @forelse($messages as $msg)
        <div style="display:flex;{{ $msg->sender_id === auth()->id() ? 'justify-content:flex-end' : 'justify-content:flex-start' }};margin-bottom:0.75rem;">
            <div style="max-width:75%;padding:0.75rem 1rem;border-radius:12px;{{ $msg->sender_id === auth()->id() ? 'background:#6366f1;color:white;border-bottom-right-radius:4px;' : 'background:rgba(255,255,255,0.08);border-bottom-left-radius:4px;' }}">
                @if($msg->property)
                    <div style="font-size:0.75rem;opacity:0.7;margin-bottom:0.25rem;">🏠 {{ $msg->property->title }}</div>
                @endif
                <div style="font-size:0.875rem;">{{ $msg->content }}</div>
                <div style="font-size:0.6875rem;opacity:0.5;margin-top:0.25rem;">{{ $msg->created_at->format('H:i') }}</div>
            </div>
        </div>
        @empty
        <p style="color:#64748b;text-align:center;padding:2rem;">Commencez la conversation !</p>
        @endforelse
    </div>

    <form method="POST" action="{{ route('messages.send', $otherUser->id) }}" style="display:flex;gap:0.5rem;">
        @csrf
        <input type="text" name="content" class="input" placeholder="Votre message..." required autofocus autocomplete="off" style="flex:1;">
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
</div>

<script>
    var container = document.querySelector('.glass[style*="max-height"]');
    if (container) container.scrollTop = container.scrollHeight;
</script>
@endsection
