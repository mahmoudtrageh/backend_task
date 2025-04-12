<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// routes/api.php
Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);

Route::post('/forgot-password', [App\Http\Controllers\API\OtpPasswordResetController::class, 'requestOtp']);
Route::post('/reset-password', [App\Http\Controllers\API\OtpPasswordResetController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
    Route::get('/user', [App\Http\Controllers\API\AuthController::class, 'user']);
    
    // Other protected API routes
});