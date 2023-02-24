<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Categories\CategoryController;
use App\Http\Controllers\Products\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class);

Route::name('auth.')->group(function() {
    Route::post('/register', [RegisterController::class, 'action']);
});

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
