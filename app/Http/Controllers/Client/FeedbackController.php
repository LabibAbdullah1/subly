<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

        return redirect()->back()->with('success', 'Feedback submitted! Thank you.');
    }
}
