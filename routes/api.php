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

Route::group(['middleware' => ['auth:api','cors']], function(){
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

    // PriceChecks API route.
    Route::get('pricechecks', 'PriceCheckController@index');
    Route::get('pricechecks/{pricecheck}', 'PriceCheckController@show');
    Route::post('pricechecks', 'PriceCheckController@store');
    Route::put('pricechecks/{pricecheck}', 'PriceCheckController@update');
    Route::delete('pricechecks/{pricecheck}', 'PriceCheckController@delete');

    // WrongChecks API route.
    Route::get('wrongchecks', 'WrongCheckController@index');
    Route::get('wrongchecks/{wrongcheck}', 'WrongCheckController@show');
    Route::post('wrongchecks', 'WrongCheckController@store');
    Route::put('wrongchecks/{wrongcheck}', 'WrongCheckController@update');
    Route::delete('wrongchecks/{wrongcheck}', 'WrongCheckController@delete');

     // Checkers API route.
     Route::get('checkers', 'CheckerController@index');
});

Route::group(['middleware' => 'cors'], function(){
    // Drugs API Routes without Token.
    Route::get('drugs', 'DrugController@index');
    Route::get('drugs/{drug}', 'DrugController@show');
    
    // User Login API Routes without Token.
    Route::get('user/login', 'Auth\APILoginController@index');
    Route::get('user/auth', 'Auth\APILoginController@getAuthenticatedUser');

    // Keywords API route.
    Route::get('keywords', 'KeywordController@index');

    // Data API route.
    Route::get('data', 'DataController@index');

    // UnprocessedData API route.
    Route::get('unprocesseddata', 'UnprocessedDataController@index');

    // DataCollector API route.
    Route::get('datacollector', 'DataCollectorController@index');

    // StatisticsCheck API route.
    Route::get('statisticscheck', 'StatisticsCheckController@index');
});