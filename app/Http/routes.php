<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['as' => 'cms.home', 'uses' => 'CmsController@index']);
Route::get('/about', ['as' => 'cms.about', 'uses' => 'CmsController@about']);

Route::get('/dashboard', ['as' => 'account.dashboard', 'uses' => 'DashboardController@index']);
Route::get('/home', ['as' => 'account.dashboard', 'uses' => 'DashboardController@index']);
Route::get('/logout', ['as' => 'account.logout', 'uses' => 'PocketController@logout']);


Route::get('podcast/{id}/{secret}', ['as' => 'podcast', 'uses' => 'FeedController@podcast']);

Route::get('pocket/login', ['as' => 'pocket.login', 'uses' => 'PocketController@login']);
Route::get('pocket/login/response', ['as' => 'pocket.response', 'uses' => 'PocketController@loginResponse']);
Route::get('pocket/synchronise', ['as' => 'pocket.synchronise', 'uses' => 'PocketController@synchronise']);

Route::resource('items', 'ItemsController');
