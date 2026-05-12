<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BloodRequestController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::prefix('auth')->group(function () {
    Route::get('/google', [AuthController::class, 'redirectToGoogle']);
    Route::get('/google/callback', [AuthController::class, 'handleGoogleCallback']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
});

// Blood request routes
Route::post('/requests', [BloodRequestController::class, 'store']);
Route::get('/requests', [BloodRequestController::class, 'index']);
Route::get('/requests/debug-donors', [BloodRequestController::class, 'debugDonors']);
