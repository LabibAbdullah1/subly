<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\DeploymentController;
use App\Http\Controllers\Client\ReportController;
use App\Http\Controllers\Client\FeedbackController;
use App\Http\Controllers\Client\PlanController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\SubdomainController;

Route::get('/', [DashboardController::class, 'index'])->name('index');
Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');

Route::post('/checkout/{plan}', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

Route::prefix('deployments')->name('deployments.')->group(function () {
    Route::post('/', [DeploymentController::class, 'store'])->name('store');
});

Route::prefix('reports')->name('reports.')->group(function () {
    Route::post('/', [ReportController::class, 'store'])->name('store');
});

Route::prefix('feedback')->name('feedback.')->group(function () {
    Route::post('/', [FeedbackController::class, 'store'])->name('store');
});

Route::post('/subdomains', [SubdomainController::class, 'store'])->name('subdomains.store');
