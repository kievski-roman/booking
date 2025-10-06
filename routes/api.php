<?php

use App\Http\Controllers\V1\Api\ProfileController;
use App\Http\Controllers\V1\Api\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/test', [UserController::class, 'index']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth:sanctum');
