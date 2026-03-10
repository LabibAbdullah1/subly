<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserDatabaseController;
use App\Http\Controllers\Admin\DeploymentQueueController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\SubdomainController;
use App\Http\Controllers\Admin\PaymentController;

Route::get('/', [DashboardController::class, 'index'])->name('index');
Route::resource('plans', PlanController::class);
Route::resource('vouchers', VoucherController::class);
Route::resource('users', UserController::class);
Route::resource('subdomains', SubdomainController::class);
Route::resource('databases', UserDatabaseController::class);
Route::resource('payments', PaymentController::class)->only(['index', 'show']);

Route::prefix('deployments')->name('deployments.')->group(function () {
    Route::get('/', [DeploymentQueueController::class, 'index'])->name('index');
    Route::put('/{deployment}/status', [DeploymentQueueController::class, 'updateStatus'])->name('update_status');
    Route::get('/{deployment}/download', [DeploymentQueueController::class, 'download'])->name('download');
    Route::post('/{deployment}/setup-db', [DeploymentQueueController::class, 'setupDatabase'])->name('setup_db');
    Route::delete('/{deployment}', [DeploymentQueueController::class, 'destroy'])->name('destroy');
});

Route::prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('/{user}', [ChatController::class, 'show'])->name('show');
    Route::post('/{user}', [ChatController::class, 'store'])->name('store');
    Route::delete('/{chat}', [ChatController::class, 'destroy'])->name('destroy');
});

Route::prefix('feedback')->name('feedback.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\FeedbackController::class, 'index'])->name('index');
    Route::put('/{feedback}/toggle-featured', [App\Http\Controllers\Admin\FeedbackController::class, 'toggleFeatured'])->name('toggle_featured');
    Route::delete('/{feedback}', [App\Http\Controllers\Admin\FeedbackController::class, 'destroy'])->name('destroy');
});
