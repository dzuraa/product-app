<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductExportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;

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

// Export Routes
Route::middleware(['auth:sanctum', 'check.user.type:Superadmin,Admin'])->group(function () {
    Route::get('/products/export', [ProductExportController::class, 'export'])->name('products.export');
});

Route::get('/login-new', [AuthController::class, 'showLoginForm'])->name('login.new');
Route::post('/login-ajax', [AuthController::class, 'loginAjax'])->name('login.ajax');
