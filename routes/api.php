<?php

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

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::resource('wallet', 'App\Http\Controllers\Api\WalletController');
    Route::resource('users', 'App\Http\Controllers\Api\UserController')->middleware('userMiddleware');
    Route::post('login', 'App\Http\Controllers\Api\LoginController@login');
    Route::post('register', 'App\Http\Controllers\Api\RegisterController@register');
    Route::get('getMatches', 'App\Http\Controllers\Api\ValorantApiController@getMatches');
    Route::post('getUserDetail', 'App\Http\Controllers\Api\ValorantApiController@getUserDetail');
    Route::resource('price', 'App\Http\Controllers\Api\PriceController');
    Route::resource('order', 'App\Http\Controllers\Api\OrderController');
    Route::get('user',[App\Http\Controllers\Api\UserController::class, 'getUser']);
    Route::post('update',[App\Http\Controllers\Api\UserController::class, 'update']);
    Route::put('updateOrder',[App\Http\Controllers\Api\OrderController::class, 'updateOrder']);

    Route::post('getMatchResult', 'App\Http\Controllers\Api\ValorantApiController@getMatchResult');
