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

// Static Policy Pages
Route::view('/terms', 'pages.terms')->name('pages.terms');
Route::view('/rules', 'pages.rules')->name('pages.rules');
Route::view('/purchase-terms', 'pages.purchase-terms')->name('pages.purchase-terms');
Route::view('/privacy', 'pages.privacy')->name('pages.privacy');

Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::post('/read-all', [\App\Http\Controllers\Client\NotificationController::class, 'markAllAsRead'])->name('readAll');
    Route::post('/clear-all', [\App\Http\Controllers\Client\NotificationController::class, 'clearAll'])->name('clearAll');
    Route::post('/{id}/read', [\App\Http\Controllers\Client\NotificationController::class, 'markAsRead'])->name('read');
    Route::delete('/{id}', [\App\Http\Controllers\Client\NotificationController::class, 'destroy'])->name('destroy');
});

require __DIR__.'/auth.php';
