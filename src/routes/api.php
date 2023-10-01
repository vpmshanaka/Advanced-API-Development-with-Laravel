<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\StockController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
//     return $request->user();
// });

Route::controller(AuthController::class)->group(function () {
    Route::post('signup', 'signup');
    Route::post('signin', 'signin');
});



Route::middleware(['auth:sanctum'])->group(function () {
    // Use Route::post for signout
    Route::post('signout', [AuthController::class, 'signout']);

    // Routes that require 'isUser' ability.
    Route::middleware(['isUserCheck'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('stocks', StockController::class);
    });
});