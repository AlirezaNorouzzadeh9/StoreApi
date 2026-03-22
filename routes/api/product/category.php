<?php

use App\Http\Controllers\Product\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'categories',
    'controller' => CategoryController::class,
], function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'store');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});
