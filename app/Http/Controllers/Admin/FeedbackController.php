<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::with(['user', 'plan'])->latest()->paginate(15);
        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function toggleFeatured(Feedback $feedback)
    {
        $feedback->update([
            'is_featured' => !$feedback->is_featured
        ]);

        return redirect()->back()->with('success', 'Feedback status updated!');
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return redirect()->back()->with('success', 'Feedback deleted!');
    }
}
