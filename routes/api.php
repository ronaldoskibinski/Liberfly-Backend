<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;


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
    Route::post('me', [AuthController::class, 'me']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => 'auth', 'prefix' => 'user'], function ($router) {
    Route::get('', [UserController::class, 'index'])->name("app.find_users");
    Route::get('count', [UserController::class, 'count'])->name("app.users_quantity");
    Route::get('{id}', [UserController::class, 'show'])->name("app.find_user_by_id");
    Route::post('', [UserController::class, 'store'])->name("app.create_user");
    Route::put('{id}', [UserController::class, 'update'])->name("app.update_user");
    Route::delete('{id}', [UserController::class, 'destroy'])->name("app.delete_user");
});

Route::group(['middleware' => 'auth', 'prefix' => 'product'], function ($router) {
    Route::get('', [ProductController::class, 'index'])->name("app.find_products");
    Route::get('count', [ProductController::class, 'count'])->name("app.products_quantity");
    Route::get('{id}', [ProductController::class, 'show'])->name("app.find_product_by_id");
    Route::post('', [ProductController::class, 'store'])->name("app.create_product");
    Route::put('{id}', [ProductController::class, 'update'])->name("app.update_product");
    Route::delete('{id}', [ProductController::class, 'destroy'])->name("app.delete_product");
});