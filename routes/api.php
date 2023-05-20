<?php

use App\Http\Controllers\MapsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CallController;

/*
|--------------------------------------------------------------------------composer dump-autoload

| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
Route::get('/auth/profile', [AuthController::class, 'profile']);

Route::group(['prefix' => 'user', 'middleware'=>'auth:sanctum'], function () {
    Route::post('/call', [CallController::class, 'call']);
    Route::get('/stations', [MapsController::class, 'getClosestStation']);
   
});