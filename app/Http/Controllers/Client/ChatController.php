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
        // Auto-delete read messages older than 24 hours (with image cleanup)
        \App\Models\Chat::where('is_read', true)
            ->where('created_at', '<', now()->subDay())
            ->get()
            ->each(function($chat) {
                $chat->delete();
            });

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
        try {
            $request->validate([
                'message' => 'nullable|string',
                'image' => 'nullable|image|max:10240', // Increased to 10MB
            ]);

            if (!$request->message && !$request->hasFile('image')) {
                return response()->json(['error' => 'Pesan atau bukti bayar harus disertakan.'], 422);
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('chats', 'public');
            }

            $chat = auth()->user()->chats()->create([
                'message' => $request->message ?? '',
                'image_path' => $imagePath,
                'is_admin' => false,
            ]);

            return response()->json($chat);
        } catch (\Exception $e) {
            \Log::error('Chat store error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengirim pesan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Chat $chat)
    {
        try {
            if ($chat->user_id != auth()->id() || $chat->is_admin) {
                return response()->json(['error' => 'Unauthorized.'], 403);
            }

            // Image deletion is now handled automatically by the Chat model's deleted event
            $chat->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Chat delete error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghapus pesan: ' . $e->getMessage()], 500);
        }
    }
}
