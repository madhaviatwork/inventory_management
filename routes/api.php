<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', '\App\Http\controllers\AuthController@login');
Route::post('register', '\App\Http\controllers\AuthController@register');
Route::middleware('api')->group(function () {
    Route::controller(\App\Http\controllers\AuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
    });

    Route::controller(\App\Http\controllers\ProductController::class)->group(function () {
        Route::get('products', 'index');
        Route::post('product', 'store');
        Route::get('product/{id}', 'show');
        Route::put('product/{id}', 'update');
        Route::delete('product/{id}', 'destroy');
    }); 

    Route::controller(\App\Http\controllers\CategoryController::class)->group(function () {
        Route::get('categories', 'index');
        Route::post('category', 'store');
        Route::get('category/{id}', 'show');
        Route::put('category/{id}', 'update');
        Route::delete('category/{id}', 'destroy');
    }); 
}); 
