<?php

use App\Http\Controllers\API\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// routes/api.php
Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
    Route::get('/user', [App\Http\Controllers\API\AuthController::class, 'user']);

    Route::post('/leave-request', [App\Http\Controllers\API\LeaveRequestController::class, 'StoreLeaveRequest']);
    Route::patch('leave-requests/{id}/status', [App\Http\Controllers\API\LeaveRequestController::class, 'updateLevelRequestStatus'])
    ->name('leave-requests.update-status');
    Route::get('/leave-requests', [App\Http\Controllers\API\LeaveRequestController::class, 'index']);

    Route::get('/employees', [EmployeeController::class, 'index']);

    // Other protected API routes
});