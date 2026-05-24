<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\AdminFeedbackNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        // Prevent duplicate feedback for the same plan
        if (Feedback::where('user_id', $request->user()->id)->where('plan_id', $request->plan_id)->exists()) {
            return redirect()->back()->withErrors(['plan_id' => 'You have already submitted feedback for this plan.']);
        }

        Feedback::create([
            'user_id' => $request->user()->id,
            'plan_id' => $request->plan_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Notify admin via email about new feedback
        try {
            $feedback = Feedback::with(['user', 'plan'])
                ->where('user_id', $request->user()->id)
                ->where('plan_id', $request->plan_id)
                ->latest()
                ->first();
            $adminEmail = config('mail.admin_email', env('MAIL_FROM_ADDRESS'));
            if ($feedback && $adminEmail) {
                Mail::to($adminEmail)->send(new AdminFeedbackNotification($feedback));
            }
        } catch (\Exception $e) {
            \Log::error('Admin feedback email failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Feedback submitted! Thank you.');
    }
}
