<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Modules\Order\Customer\CustomerController;
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

//Client
Route::get('/', function() {
    return view('welcome');
})->middleware('auth');


//Dashboard
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::prefix('product')->group(function () {
        //product
        Route::get('product', function() {
            return redirect()
                ->route('admin.user.user.index');
        })
            ->name('admin.product.index');
        //category
    });
    Route::prefix('user')->group(function () {
        //user
        Route::get('user', [UserController::class, 'index'])
            ->name('admin.user.user.index');
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
        Route::post('user-add', [UserController::class, 'addUser'])
            ->name('admin.user.user.userAdd');
        Route::post('email-validate', [UserController::class, 'uniqueEmail'])
            ->name('admin.user.user.uniqueEmail');
        Route::post('email-validate-2', [UserController::class, 'uniqueEmailEdit'])
            ->name('admin.user.user.uniqueEmailEdit');
        Route::post('user-edit', [UserController::class, 'editUser'])
            ->name('admin.user.user.userEdit');
        //group role
    });
    Route::prefix('order')->group(function () {
        Route::get('/customer', [CustomerController::class, 'index'])
            ->name('admin.order.customer.index');
        Route::get('/customer-list', [CustomerController::class, 'getCustomers'])
            ->name('admin.order.customer.getCustomers');
        Route::post('/customer-email-unique', [CustomerController::class, 'emailUnique'])
            ->name('admin.order.customer.emailUnique');
        Route::post('/add-customer', [CustomerController::class, 'addCustomer'])
            ->name('admin.order.customer.addCustomer');
    });
});

