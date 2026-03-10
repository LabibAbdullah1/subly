<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        // Get all users who have chats, and unread count
        $users = User::whereHas('chats')->withCount(['chats as unread_count' => function ($query) {
            $query->where('is_admin', false)->where('is_read', false);
        }])->orderByDesc('unread_count')->get();

        return view('admin.chats.index', compact('users'));
    }

    public function show(User $user)
    {
        // Auto-delete read messages older than 24 hours
        \App\Models\Chat::where('is_read', true)
            ->where('created_at', '<', now()->subDay())
            ->delete();

        $chats = $user->chats()->oldest()->get();
        
        // Mark user messages as read for this admin
        $user->chats()->where('is_admin', false)->where('is_read', false)->update(['is_read' => true]);

        return response()->json($chats);
    }

    public function store(Request $request, User $user)
    {
        $request->validate(['message' => 'required|string']);

        $chat = $user->chats()->create([
            'message' => $request->message,
            'is_admin' => true,
        ]);

        return response()->json($chat);
    }

    public function destroy(Chat $chat)
    {
        $chat->delete();
        return response()->json(['success' => true]);
    }
}
