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

// , 'before' => 'authenticate'
Route::group(['prefix' => 'api/v1'], function() {
  Route::get('storage', ['as' => 'entry-point', 'uses' => 'ApiStorageController@store']);
});
