<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\DeploymentController;
use App\Http\Controllers\Client\ChatController;
use App\Http\Controllers\Client\FeedbackController;
use App\Http\Controllers\Client\PlanController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\SubdomainController;
use App\Http\Controllers\Client\NotificationController;

Route::get('/', [DashboardController::class, 'index'])->name('index');
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/portal/{subdomain}', [DashboardController::class, 'portal'])->name('portal');
Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');

Route::post('/checkout/{plan}', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

Route::prefix('deployments')->name('deployments.')->group(function () {
    Route::get('/', [DeploymentController::class, 'index'])->name('index');
    Route::post('/', [DeploymentController::class, 'store'])->name('store');
});

Route::prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('/messages', [ChatController::class, 'messages'])->name('messages');
    Route::post('/', [ChatController::class, 'store'])->name('store');
    Route::delete('/{chat}', [ChatController::class, 'destroy'])->name('destroy');
});

Route::prefix('feedback')->name('feedback.')->group(function () {
    Route::post('/', [FeedbackController::class, 'store'])->name('store');
});

Route::prefix('subdomains')->name('subdomains.')->group(function () {
    Route::post('/', [SubdomainController::class, 'store'])->name('store');
    Route::get('/{subdomain}/renew', [SubdomainController::class, 'renew'])->name('renew');
    Route::delete('/{subdomain}', [SubdomainController::class, 'destroy'])->name('destroy');
});
