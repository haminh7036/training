<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Session
Route::get('/session', function() {
    dd(session()->all());
});

//Auth
Route::name('auth.')->group(function () {
    Route::get('login', [LoginController::class,'getLogin'])
        ->name('getLogin');
    Route::post('post-login', [LoginController::class,'postLogin'])
        ->name('postLogin');

});

//Dashboard
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::prefix('product')->group(function () {
        Route::get('product', function() {
            return view('welcome');
        })
        ->name('admin.product.index');
    });
});