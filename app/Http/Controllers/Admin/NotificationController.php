<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = \DB::table('notifications')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::where('role', 'Client')->orderBy('name')->get();
        return view('admin.notifications.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'target' => 'required|string',
            'user_id' => 'nullable|required_if:target,specific|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $message = $request->message;

        if ($request->target === 'all') {
            $users = User::where('role', 'Client')->get();
            \Log::info('Sending broadcast to all clients', ['count' => $users->count()]);
            Notification::send($users, new GeneralNotification($message));
            $count = $users->count();
            return redirect()->back()->with('success', "Notification sent to all $count clients.");
        } else {
            $user = User::findOrFail($request->user_id);
            \Log::info('Sending broadcast to specific user', ['user_id' => $user->id, 'email' => $user->email]);
            $user->notify(new GeneralNotification($message));
            return redirect()->back()->with('success', "Notification sent to {$user->name}.");
        }
    }
}
