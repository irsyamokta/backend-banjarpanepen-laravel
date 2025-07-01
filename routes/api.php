<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::prefix('v1')->group(function () {
    Route::get('/', function (Request $request) {
        return 'Running';
    });

    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me'])->middleware('CheckAuth');
        Route::get('/verify/{token}', [AuthController::class, 'verifyEmail']);
    });

    Route::middleware('CheckAuth')->prefix('user')->group(function () {
        Route::get('/all', [UserController::class, 'getUsers']);
        Route::patch('/update', [UserController::class, 'updateUser']);
        Route::delete('/delete/{id}', [UserController::class, 'deleteUser']);
    });
});
