<?php

use App\Http\Controllers\V1\Api\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Api\UserController;
use App\Http\Controllers\V1\Api\MasterController;

Route::prefix('v1')->group(function () {
    Route::post('/register', [UserController::class, 'register'])->name('register');
    Route::post('/login', [UserController::class, 'login'])->name('login');
    Route::get('/masters', [MasterController::class, 'index']); // Добавь для списка мастеров

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::post('/logout', [UserController::class, 'logout']);
        Route::post('/services', [MasterController::class, 'store']);
    });
});

