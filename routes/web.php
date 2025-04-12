<?php

use Illuminate\Support\Facades\Route;

// routes/web.php
Route::get('admin/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [App\Http\Controllers\Admin\AuthController::class, 'login']);
Route::post('admin/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');

// Admin protected routes
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Other admin dashboard routes
});
