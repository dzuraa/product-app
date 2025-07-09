<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// API untuk daftar user
Route::middleware('auth:api')->group(function () {
    // Route yang bisa diakses Superadmin dan Admin
    Route::middleware(['check.user.type.api:Superadmin,Admin'])->group(function () {
        Route::get('/users', [UserApiController::class, 'index']);
    });

    // Route yang hanya bisa diakses Superadmin
    Route::middleware(['check.user.type.api:Superadmin'])->group(function () {
        Route::post('/users', [UserApiController::class, 'store']);
        Route::get('/users/{user}', [UserApiController::class, 'show']);
        Route::put('/users/{user}', [UserApiController::class, 'update']);
        Route::delete('/users/{user}', [UserApiController::class, 'destroy']);
    });
});

// API untuk produk
Route::middleware('auth:api')->group(function () {
    // Route yang bisa diakses Admin dan Superadmin
    Route::middleware(['check.user.type.api:Admin,Superadmin'])->group(function () {
        Route::apiResource(("products"), ProductApiController::class);
    });
});

// API Authentication
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);
