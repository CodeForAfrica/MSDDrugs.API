<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    // Drugs API routes with Token.
    Route::post('drugs', 'DrugController@store');
    Route::put('drugs/{drug}', 'DrugController@update');
    Route::delete('drugs/{drug}', 'DrugController@delete');

    // Users API route.
    Route::get('users', 'UserController@index');
    Route::get('users/{user}', 'UserController@show');
    Route::post('users', 'UserController@store');
    Route::put('users/{user}', 'UserController@update');
    Route::delete('users/{user}', 'UserController@delete');
});

Route::group(['middleware' => 'cors'], function(){
    // Drugs API Routes without Token.
    Route::get('drugs', 'DrugController@index');
    Route::get('drugs/{drug}', 'DrugController@show');
    
    // User Login API Routes without Token.
    Route::get('user/login', 'Auth\APILoginController@index');
    Route::get('user/auth', 'Auth\APILoginController@getAuthenticatedUser');
});