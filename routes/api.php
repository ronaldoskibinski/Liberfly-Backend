<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;


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

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('new-user', [AuthController::class, 'newUser']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => 'auth', 'prefix' => 'user'], function ($router) {
    Route::get('', [UserController::class, 'index'])->name("Find Users");
    Route::get('count', [UserController::class, 'count'])->name("Users Quantity");
    Route::get('{id}', [UserController::class, 'show'])->name("Find User By Id");
    Route::post('', [UserController::class, 'store'])->name("Create User");
    Route::put('{id}', [UserController::class, 'update'])->name("Update User");
    Route::delete('{id}', [UserController::class, 'destroy'])->name("Delete User");
});

Route::group(['middleware' => 'auth', 'prefix' => 'product'], function ($router) {
    Route::get('', [ProductController::class, 'index'])->name("Find Products");
    Route::get('count', [ProductController::class, 'count'])->name("Products Quantity");
    Route::get('{id}', [ProductController::class, 'show'])->name("Find Product By Id");
    Route::post('', [ProductController::class, 'store'])->name("Create Product");
    Route::put('{id}', [ProductController::class, 'update'])->name("Update Product");
    Route::delete('{id}', [ProductController::class, 'destroy'])->name("Delete Product");
});