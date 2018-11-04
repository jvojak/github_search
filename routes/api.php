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
    return $request->user();
});


Route::group(['prefix' => 'v1'], function () {
	// Searches queried term within a given social provider for popularity score (0-10)
	Route::get('/{provider}/search', 'Api\v1\SearchController@search')->middleware('client');;
});

Route::group(['prefix' => 'v2'], function () {
	// Searches queried term within a given social provider for popularity score (0-10)
	Route::get('/{provider}/search', 'Api\v2\SearchController@search')->middleware('client');;
});