<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get("auth-test", ['before' => 'auth.basic', function() {
  return Response::json(['success'=>true]);
}]);

Route::group(['prefix' => 'api/v1/storage', 'before' => 'auth.basic'], function() {
  Route::post('store', ['as' => 'store-data', 'uses' => 'ApiStorageController@store']);
  Route::get('get/{identifier}', ['as' => 'retrieve-data', 'uses' => 'ApiStorageController@get']);
});