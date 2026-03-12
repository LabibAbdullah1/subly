<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return view('client.chat.index');
    }

    public function messages()
    {
        // Auto-delete read messages older than 24 hours
        \App\Models\Chat::where('is_read', true)
            ->where('created_at', '<', now()->subDay())
            ->delete();

        $chats = auth()->user()->chats()->oldest()->get();
        // Mark admin messages as read for this user
        auth()->user()->chats()->where('is_admin', true)->where('is_read', false)->update(['is_read' => true]);
        
        $adminOnline = \App\Models\User::where('role', 'Admin')
            ->where('last_seen_at', '>=', now()->subMinutes(2))
            ->exists();

        return response()->json([
            'messages' => $chats,
            'admin_online' => $adminOnline
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['message' => 'required|string']);

        $chat = auth()->user()->chats()->create([
            'message' => $request->message,
            'is_admin' => false,
        ]);

        return response()->json($chat);
    }

    public function destroy(Chat $chat)
    {
        if ($chat->user_id !== auth()->id()) {
            abort(403);
        }
        $chat->delete();
        return response()->json(['success' => true]);
    }
}
