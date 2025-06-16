<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });

// routes/web.php
Route::middleware(['auth:sanctum', config('jetstream.auth_session')])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management - Superadmin penuh, Admin hanya akses index
    Route::middleware(['check.user.type:Superadmin, Admin'])->group(function () {
        Route::resource('users', UserController::class);
    });
    // User list untuk Admin (read-only)
    Route::middleware(['check.user.type:Admin'])->group(function () {
        Route::get('/users-view', [UserController::class, 'index'])->name('users.view');
    });

    // Product Management - Admin & Superadmin
    Route::middleware(['check.user.type:Admin,Superadmin'])->group(function () {
        Route::resource('products', ProductController::class);
    });
});

// API Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/api/dashboard-stats', [DashboardController::class, 'apiStats']);
});

// Route::middleware(['check.user.type:Superadmin'])->group(function () {
//     Route::get('/users', [UserController::class, 'index'])->name('users.index');
// });
