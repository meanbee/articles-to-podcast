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
Route::get('/register', ['as' => 'account.register', 'uses' => 'Auth\AuthController@getRegister']);


Route::get('dashboard', ['as' => 'account.dashboard', 'uses' => 'DashboardController@index']);

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::resource('items', 'ItemsController');
