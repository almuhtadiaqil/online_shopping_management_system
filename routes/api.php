<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShoppingCartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('api')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::resource('products', ProductController::class);
    Route::prefix('/shopping/cart')->group(function () {
        Route::get('/', [ShoppingCartController::class, 'index']);
        Route::post('/', [ShoppingCartController::class, 'store']);
        Route::post('/checkout', [ShoppingCartController::class, 'checkout']);
    });
});
