<?php

use App\Http\Controllers\V1\Api\AppointmentController;
use App\Http\Controllers\V1\Api\MasterController;
use App\Http\Controllers\V1\Api\ProfileController;
use App\Http\Controllers\V1\Api\ScheduleController;
use App\Http\Controllers\V1\Api\ServiceController;
use App\Http\Controllers\V1\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/register', [UserController::class, 'register'])->name('register');
    Route::post('/login', [UserController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(callback: function () {
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::post('/logout', [UserController::class, 'logout']);


        Route::get('/masters', [MasterController::class, 'index']);
        Route::get('/masters/{master}', [MasterController::class, 'show']);

        Route::post('/services', [ServiceController::class, 'store']);
        Route::put('/services/{service}', [ServiceController::class, 'update']);
        Route::delete('/services/{service}', [ServiceController::class, 'destroy']);

        Route::post('/schedules', [ScheduleController::class, 'store']);
        Route::put('/schedules/{schedule}', [ScheduleController::class, 'update']);
        Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy']);

        Route::get('/appointments', [AppointmentController::class, 'index']);
        Route::post('/appointments', [AppointmentController::class, 'store']);
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'update']);
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy']);
    });
});
