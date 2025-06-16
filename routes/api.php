<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store'])->middleware('superadmin');
    Route::put('/users/{id}', [UserController::class, 'update'])->middleware('superadmin');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('superadmin');
});
