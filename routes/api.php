<?php

use App\Http\Controllers\V1\Api\AppointmentController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\V1\Api\UserController;
use App\Http\Controllers\V1\Api\ProfileController;
use App\Http\Controllers\V1\Api\ServiceController;
use App\Http\Controllers\V1\Api\ScheduleController;
use App\Http\Controllers\V1\Api\MasterController;
use App\Http\Controllers\V1\Api\ClientAppointmentController;
use App\Http\Controllers\V1\Api\MasterAppointmentController;
use App\Http\Controllers\V1\Api\AdminAppointmentController;

Route::prefix('v1')->group(function () {
    Route::get('/health', fn() => response()->json(['ok' => true]));

    // Auth
    Route::post('/register-client', [UserController::class, 'registerClient'])->name('auth.registerClient');
    Route::post('/register-master', [UserController::class, 'registerMaster'])->name('auth.registerMaster');
    Route::post('/login', [UserController::class, 'login'])->name('auth.login');

    Route::middleware('auth:sanctum')->group(function () {

        // ---- Admin zone ----
        Route::prefix('admin')->middleware('role:admin')->group(function () {
            Route::get('/profiles', [ProfileController::class, 'index']);    // список пользователей
            Route::delete('/users/{user}', [UserController::class, 'destroy']);

            Route::get('/appointments', [AdminAppointmentController::class, 'index']);
            Route::get('/appointments/{appointment}', [AdminAppointmentController::class, 'show']);
            Route::patch('/appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus']);
            Route::delete('/appointments/{appointment}', [AdminAppointmentController::class, 'destroy']);
        });


        Route::prefix('me')->group(function () {

            Route::get('/profile', [ProfileController::class, 'show']);
            Route::patch('/profile', [ProfileController::class, 'updateUser']);
            Route::patch('/profile/master', [ProfileController::class, 'updateMaster']);

            Route::middleware('role:client')->group(function () {
                Route::get('/appointments', [ClientAppointmentController::class, 'index']);
                Route::post('/appointments', [ClientAppointmentController::class, 'store']);
                Route::get('/appointments/{appointment}', [ClientAppointmentController::class, 'show']);
                Route::patch('/appointments/{appointment}/cancel', [ClientAppointmentController::class, 'cancel']);
            });

            Route::middleware('role:master')->prefix('master')->group(function () {
                Route::get('/appointments', [MasterAppointmentController::class, 'index']);
                Route::get('/appointments/{appointment}', [MasterAppointmentController::class, 'show']);
                Route::patch('/appointments/{appointment}/confirm', [MasterAppointmentController::class, 'confirm']);
                Route::patch('/appointments/{appointment}/cancel', [MasterAppointmentController::class, 'cancel']);

                Route::apiResource('schedules', ScheduleController::class);

                Route::apiResource('services', ScheduleController::class);
            });
        });

        Route::post('/logout', [UserController::class, 'logout'])->name('auth.logout');


        Route::get('/masters', [MasterController::class, 'index']);
        Route::get('/masters/{master}', [MasterController::class, 'show']);


    });
});

