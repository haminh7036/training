<?php

use App\Http\Controllers\API\TestController;
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

// Route::middleware('auth:api')->get('/user-demo', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [TestController::class, 'login']);
Route::get('/user', [TestController::class, 'user'])
    ->middleware(['auth:sanctum', 'revokeSanctum']);