<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Product\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

*/
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::middleware('guest')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', RegisterController::class)->name('register');
        Route::post('login', LoginController::class)->name('login');
    });
});

Route::middleware('auth:api')->group(function () {
    Route::get('product-category', [ProductController::class, 'categoryList']);
});
