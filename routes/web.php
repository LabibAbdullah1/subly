<?php

use App\Models\Plan;
use App\Models\Feedback;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
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
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::put('/user/password', [PasswordController::class, 'update'])->name('password.update');
// Temporary setup route for production (can be visited by admin to fix storage link)
Route::get('/link-storage', function () {
    if (auth()->check() && auth()->user()->role === 'Admin') {
        try {
            \Illuminate\Support\Facades\Artisan::call('storage:link');
            return "Storage link created successfully!";
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
    abort(403);
});

// Temporary migration runner route for production cPanel
Route::get('/run-migrations', function () {
    if (auth()->check() && auth()->user()->role === 'Admin') {
        try {
            // Hapus riwayat migrasi reports agar dipaksa jalan ulang
            \Illuminate\Support\Facades\DB::table('migrations')
                ->where('migration', '2026_03_08_101228_create_reports_table')
                ->delete();

            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            $output = \Illuminate\Support\Facades\Artisan::output();
            return "Migrations run successfully!<br><pre>" . $output . "</pre>";
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
    abort(403);
});

require __DIR__.'/auth.php';

