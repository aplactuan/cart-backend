<?php

use App\Http\Controllers\Addresses\AddressIndexController;
use App\Http\Controllers\Addresses\AddressStoreController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Cart\DeleteItemController;
use App\Http\Controllers\Cart\StoreItemsController;
use App\Http\Controllers\Cart\UpdateItemController;
use App\Http\Controllers\Cart\UserItemsController;
use App\Http\Controllers\Categories\CategoryController;
use App\Http\Controllers\Products\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class);

Route::prefix('auth')->group(function() {
    Route::post('/register',RegisterController::class);
    Route::post('/login', LoginController::class);
    Route::get('/me', MeController::class)->middleware(['auth:api']);
});

Route::middleware(['auth:api'])->prefix('cart')->group(function () {
    Route::get('/', UserItemsController::class);
    Route::post('/', StoreItemsController::class);
    Route::patch('/{productVariation}', UpdateItemController::class);
    Route::delete('/{productVariation}', DeleteItemController::class);
});

Route::middleware(['auth:api'])->prefix('addresses')->group(function () {
    Route::get('/', AddressIndexController::class);
    Route::post('/', AddressStoreController::class);
});

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
