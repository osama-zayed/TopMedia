<?php

// use App\Http\Controllers\HomeController;

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class,'index']);
Route::resource('home', HomeController::class);
Route::get('/electronic', function () {
    return view('page.electronic');
})->name('electronic');
Route::get('/jewellery', function () {
    return view('page.jewellery');
})->name('jewellery');
Route::get('/fashion', function () {
    return view('page.fashion');
})->name('fashion');
