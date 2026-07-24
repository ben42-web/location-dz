<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver', 'property'])
            ->latest()
            ->get()
            ->groupBy(function ($msg) use ($userId) {
                return $msg->sender_id === $userId ? $msg->receiver_id : $msg->sender_id;
            });

        $unread = Message::where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();

        return view('messages.index', compact('conversations', 'unread'));
    }

    public function show($userId)
    {
        $otherUser = User::findOrFail($userId);
        $myId = auth()->id();

        $messages = Message::where(function ($q) use ($myId, $userId) {
                $q->where('sender_id', $myId)->where('receiver_id', $userId);
            })->orWhere(function ($q) use ($myId, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $myId);
            })
            ->with(['sender', 'receiver', 'property'])
            ->orderBy('created_at')
            ->get();

        Message::where('sender_id', $userId)
            ->where('receiver_id', $myId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('messages.show', compact('messages', 'otherUser'));
    }

    public function send(Request $request, $receiverId)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:2000',
            'property_id' => 'nullable|exists:properties,id',
        ]);

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiverId,
            'property_id' => $validated['property_id'] ?? null,
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Message envoyé.');
    }
}
