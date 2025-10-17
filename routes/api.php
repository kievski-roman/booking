<?php

use App\Http\Controllers\V1\Api\AppointmentController;
use App\Http\Controllers\V1\Api\MasterController;
use App\Http\Controllers\V1\Api\ProfileController;
use App\Http\Controllers\V1\Api\ScheduleController;
use App\Http\Controllers\V1\Api\ServiceController;
use App\Http\Controllers\V1\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', fn () => response()->json(['ok' => true]));
    Route::post('/register-client', [UserController::class, 'registerClient'])->name('registerClient');
    Route::post('/register-master', [UserController::class, 'registerMaster'])->name('registerMaster');
    Route::post('/login', [UserController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(callback: function () {

        Route::prefix('admin')->group(function () {
            Route::get('/profiles', [ProfileController::class, 'index']);
            Route::delete('/users/{user}', [UserController::class, 'destroy']);
        });

        Route::get('/profile', [ProfileController::class, 'show']);
        Route::patch('/profile', [ProfileController::class, 'updateUser']);
        Route::patch('/profile/master', [ProfileController::class, 'updateMaster']);
        Route::post('/logout', [UserController::class, 'logout']);


        Route::get('/masters', [MasterController::class, 'index']);
        Route::get('/masters/{master}', [MasterController::class, 'show']);

        Route::get('/services', [ServiceController::class, 'index']);
        Route::get('/services/{service}', [ServiceController::class, 'show']);
        Route::post('/services', [ServiceController::class, 'store']);
        Route::put('/services/{service}', [ServiceController::class, 'update']);
        Route::delete('/services/{service}', [ServiceController::class, 'destroy']);

        Route::get('/schedules', [ScheduleController::class, 'index']);
        Route::get('/schedules/{schedule}', [ScheduleController::class, 'show']);
        Route::post('/schedules', [ScheduleController::class, 'store']);
        Route::put('/schedules/{schedule}', [ScheduleController::class, 'update']);
        Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy']);

        Route::get('/appointments', [AppointmentController::class, 'index']);
        Route::get('/appointments/{appointment}', [AppointmentController::class, 'show']);
        Route::post('/appointments', [AppointmentController::class, 'store']);

        Route::patch('/appointments/{appointment}', [AppointmentController::class, 'update']);
    });
});
