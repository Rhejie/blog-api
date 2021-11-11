<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function() {
    Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
        Route::get('/profile', [App\Http\Controllers\AuthController::class, 'profile']);
    });


});

Route::group(['prefix' => 'user', 'middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'blog'], function () {
        Route::post('/store', [App\Http\Controllers\BlogController::class, 'store']);
        Route::get('/get-blogs/{id}', [App\Http\Controllers\BlogController::class, 'getBlogs']);
    });
});


Route::group(['prefix' => 'blog'], function () {
    Route::get('/get-welcome-blogs', [App\Http\Controllers\BlogController::class, 'getWelcomeBlogs']);
});
