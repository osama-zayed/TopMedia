<?php

// use App\Http\Controllers\HomeController;

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localize']
    ],
    function () {

        Route::get('/', [HomeController::class, 'index']);
        Route::resource('home', HomeController::class);
        Route::resource('Category', CategoryController::class);
        
    }
);
