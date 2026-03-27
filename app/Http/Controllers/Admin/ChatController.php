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
        // Auto-delete read messages older than 24 hours (with image cleanup)
        \App\Models\Chat::where('is_read', true)
            ->where('created_at', '<', now()->subDay())
            ->get()
            ->each(function($chat) {
                $chat->delete();
            });

        $chats = $user->chats()->oldest()->get();
        
        // Mark user messages as read for this admin
        $user->chats()->where('is_admin', false)->where('is_read', false)->update(['is_read' => true]);

        return response()->json($chats);
    }

    public function store(Request $request, User $user)
    {
        try {
            $request->validate([
                'message' => 'nullable|string',
                'image' => 'nullable|image|max:10240',
            ]);

            if (!$request->message && !$request->hasFile('image')) {
                return response()->json(['error' => 'Message or image required.'], 422);
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('chats', 'public');
            }

            $chat = $user->chats()->create([
                'message' => $request->message ?? '',
                'image_path' => $imagePath,
                'is_admin' => true,
            ]);

            return response()->json($chat);
        } catch (\Exception $e) {
            \Log::error('Admin chat store error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Chat $chat)
    {
        try {
            // Image deletion is now handled automatically by the Chat model's deleted event
            $chat->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Admin chat delete error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
