<?php

use App\Models\Plan;
use App\Models\Feedback;

Route::get('/', function () {
    $plans = Plan::where('is_active', true)->orderBy('price')->get();
    $feedbacks = Feedback::with('user')->where('is_featured', true)->latest()->get();
    return view('welcome', compact('plans', 'feedbacks'));
});

Route::get('/home', function () {
    if (auth()->check()) {
        $url = auth()->user()->role === 'Admin' ? route('admin.index') : route('client.index');
        return redirect()->to($url);
    }
    return redirect()->route('login');
})->name('dashboard');

Route::post('dashboard/midtrans/webhook', [App\Http\Controllers\Client\CheckoutController::class, 'webhook'])->name('midtrans.webhook');

require __DIR__.'/auth.php';
