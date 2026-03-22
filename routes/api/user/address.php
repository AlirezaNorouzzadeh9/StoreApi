<?php

use App\Http\Controllers\User\AddressController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'address',
    'middleware' => 'auth:sanctum',
    'controller' => AddressController::class,
], function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'store');
    Route::put('/{id}', 'update');
    Route::patch('/set-default/{id}', 'setDefault');
    Route::delete('/{id}', 'destroy');
});
