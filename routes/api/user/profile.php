<?php

use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'profile',
    'middleware' => 'auth:sanctum',
    'controller' => ProfileController::class,
], function () {
    Route::get('/me', 'authMe');
    Route::put('/change-general', 'changeGeneral');
    Route::post('/change-phone', 'changePhone');
    Route::post('/change-email', 'changeEmail');
    Route::post('/verify-phone', 'verifyPhone');
    Route::post('/verify-email', 'verifyEmail');
    Route::put('/change-password', 'changePassword');
    Route::post('/logout', 'logout');
});
