<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;

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

Route::group(['prefix' => 'v1', 'middleware' => 'api', 'namespace' => 'Api'], function () {
    Route::get('ip', function () {
        return request()->ip();
    });

    Route::group(['prefix' => 'auth'], function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });

    Route::middleware('auth:api')->group(function () {
        Route::group(['prefix' => 'products'],function () {
            Route::get('category/{category}', [ProductController::class,'getProductByCategory']);
            Route::get('brand/{brand}', [ProductController::class, 'getProductByBrand']);
        });
        Route::apiResources([
            'users' => UserController::class,
            'products' => ProductController::class
        ]);
    });
});
