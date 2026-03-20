<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\ProfileController;

Route::group(['prefix' => 'auth','middleware'=> 'throttle:5,5'], function () {
    Route::post('/', [AuthController::class, 'auth']);
    Route::post('/verify', [AuthController::class, 'verifyAuth']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/verify-reset-password', [AuthController::class, 'verifyResetPassword']);
    Route::post('/confirm-reset-password', [AuthController::class, 'confirmResetPassword']);
});



Route::group(['prefix'=> 'profile','middleware' => 'auth:sanctum'], function(){
    Route::get('/me', [ProfileController::class, 'authMe']);
    Route::put('/change-general', [ProfileController::class, 'changeGeneral']);
    Route::post('/change-phone', [ProfileController::class, 'changePhone']);
    Route::post('/change-email', [ProfileController::class, 'changeEmail']);
    Route::post('/verify-phone', [ProfileController::class, 'verifyPhone']);
    Route::post('/verify-email', [ProfileController::class, 'verifyEmail']);
    Route::put('/change-password', [ProfileController::class, 'changePassword']);
});


