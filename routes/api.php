<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('auth')->group(function () {
    Route::post('login', "AuthController@login");
    Route::post('register', "AuthController@register");
    Route::post('logout', "AuthController@logout")->middleware('auth:sanctum');
    Route::get('me', "AuthController@me")->middleware('auth:sanctum');
    Route::put('editUser', "AuthController@editUser")->middleware('auth:sanctum');
});
Route::prefix('Category')->group(function () {
    Route::get('showAll', "CategoryController@showAll");
});
Route::prefix('Setting')->group(function () {
    Route::get('showAll', "SettingController@showAll");
});
Route::prefix('Product')->group(function () {
    Route::get('showAll', "ProductController@showAll");
    Route::post('showById', "ProductController@showById");
});
Route::prefix('Banner')->group(function () {
    Route::get('showAll', "BannerController@showAll");
});
Route::prefix('favorites')->group(function () {
    Route::get('showAll', "FavoritController@showAll");
    Route::post('add', "FavoritController@add");
});