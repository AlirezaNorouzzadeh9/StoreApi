<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth',
    'middleware' => 'throttle:5,5',
    'controller' => AuthController::class,
], function () {
    Route::post('/', 'auth');
    Route::post('/verify', 'verifyAuth');
    Route::post('/reset-password', 'resetPassword');
    Route::post('/verify-reset-password', 'verifyResetPassword');
    Route::post('/confirm-reset-password', 'confirmResetPassword');
});
