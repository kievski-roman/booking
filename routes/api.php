<?php

use App\Http\Controllers\V1\Api\ProfileController;
use App\Http\Controllers\V1\Api\ScheduleController;
use App\Http\Controllers\V1\Api\ServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Api\UserController;
use App\Http\Controllers\V1\Api\MasterController;

Route::prefix('v1')->group(function () {
    Route::post('/register', [UserController::class, 'register'])->name('register');
    Route::post('/login', [UserController::class, 'login'])->name('login');
    Route::get('/masters', [MasterController::class, 'index']); // Добавь для списка мастеров
    Route::get('/master/{id}', [MasterController::class, 'show']);
    Route::middleware('auth:sanctum')->group(callback: function () {
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::post('/logout', [UserController::class, 'logout']);

        Route::post('/services', [ServiceController::class, 'store']);
        Route::put('/services/{service}', [ServiceController::class, 'update']);


        Route::post('/schedules', [ScheduleController::class, 'store']);
        Route::put('/schedules/{schedule}', [ScheduleController::class, 'update']);
        Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy']);
    });
});

