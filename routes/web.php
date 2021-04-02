<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Modules\User\User\UserController;

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
            return view('admin.modules.user.user.index');
        })
            ->name('admin.product.index');
    });
    Route::prefix('user')->group(function () {
        Route::get('user', [UserController::class, 'index'])
            ->name('admin.user.user.index');

        //
        Route::get('user-list', [UserController::class, 'getUsers'])
            ->name('admin.user.user.getUsers');
        Route::post('user-search', [UserController::class, 'search'])
            ->name('admin.user.user.search');
        Route::post('user-info', [UserController::class, 'getInfoUser'])
            ->name('admin.user.user.userInfo');
        Route::post('user-delete', [UserController::class, 'deleteUser'])
            ->name('admin.user.user.userDelete');
        Route::post('user-block', [UserController::class, 'blockUser'])
            ->name('admin.user.user.userBlock');
    });
});