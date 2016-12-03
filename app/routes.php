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

Route::get("login", function() {
  $user = new User;
  $user->email = 'alain.chevanier@gmail.com';
  $user->password = 'alain';

  Auth::login($user);

  return Response::json([
      'success' => Auth::check(),
      'user' => Auth::user()
    ]);
});

Route::get("logout", function() {
  Auth::logout();
  return Response::json([
      'success' => !Auth::check()    
    ]);
});

Route::group(['prefix' => 'api/v1', 'before' => 'auth.basic'], function() {
  Route::get('storage', ['as' => 'store-data', 'uses' => 'ApiStorageController@store']);
  Route::get('get', ['as' => 'retrieve-data', 'uses' => 'ApiStorageController@get']);
});
