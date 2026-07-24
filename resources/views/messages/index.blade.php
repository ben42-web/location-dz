@extends('layouts.app', ['title' => 'Messages'])

@section('content')
<div class="container" style="max-width:720px;">
    <h1 style="font-size:1.5rem;font-weight:700;margin-bottom:1rem;">Messages @if($unread) <span style="background:#ef4444;color:white;padding:0.125rem 0.5rem;border-radius:9999px;font-size:0.75rem;">{{ $unread }}</span> @endif</h1>

    @forelse($conversations as $userId => $msgs)
        @php $lastMsg = $msgs->last(); @endphp
        <a href="{{ route('messages.show', $userId) }}" style="text-decoration:none;color:inherit;">
            <div class="glass" style="padding:1rem;margin-bottom:0.75rem;display:flex;gap:1rem;align-items:center;" onmouseover="this.style.borderColor='rgba(99,102,241,0.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'">
                <div style="width:44px;height:44px;border-radius:50%;background:#6366f1;display:flex;align-items:center;justify-content:center;color:white;font-weight:600;flex-shrink:0;">
                    {{ substr($lastMsg->sender_id === auth()->id() ? $lastMsg->receiver->name : $lastMsg->sender->name, 0, 1) }}
                </div>
                <div style="flex:1;overflow:hidden;">
                    <div style="font-weight:600;font-size:0.9375rem;">{{ $lastMsg->sender_id === auth()->id() ? $lastMsg->receiver->name : $lastMsg->sender->name }}</div>
                    <div style="font-size:0.8125rem;color:#64748b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $lastMsg->content }}</div>
                </div>
                <div style="font-size:0.75rem;color:#475569;white-space:nowrap;">{{ $lastMsg->created_at->diffForHumans() }}</div>
            </div>
        </a>
    @empty
    <div class="glass" style="padding:3rem;text-align:center;">
        <p style="color:#64748b;">Aucun message.</p>
    </div>
    @endforelse
</div>
@endsection
