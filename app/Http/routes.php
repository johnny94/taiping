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

Route::get('/', ['middleware' => 'auth', 
	'uses' => 'ClassesController@index']);

Route::get('classes', 'ClassesController@index');

Route::get('leaves/create', 'LeavesController@create');
Route::post('leaves', 'LeavesController@createLeaveStep1');
Route::get('leaves', 'LeavesController@index');
Route::post('leaves/all', 'LeavesController@all');
//Route::get('leaves/all', 'LeavesController@all');

Route::get('switchings/{id}', 'LeavesController@switching');
Route::get('leaves/switching/create', 'LeavesController@createSwitching');
Route::post('leaves/switchings', 'LeavesController@createLeaveWithSwitching');
Route::get('switchings/{id}/edit', 'LeavesController@editSwitching');
Route::patch('switchings/{id}', 'LeavesController@updateSwitching');
Route::patch('switchings/{id}/pass', 'LeavesController@passSwitching');
Route::patch('switchings/{id}/reject', 'LeavesController@rejectSwitching');

Route::get('substitutes/{id}', 'LeavesController@substitute');
Route::get('leaves/substitute/create', 'LeavesController@createSubstitute');
Route::post('leaves/substitutes', 'LeavesController@createLeaveWithSubstitute');

Route::get('leaves/unchecked_switching', 'LeavesController@uncheckedSwitching');
Route::get('teachers', 'LeavesController@getTeacherNames');

//Route::get('home', 'HomeController@index');

Route::get('password/reset', 'PasswordController@index');
Route::post('password/reset', 'PasswordController@reset');

Route::controllers([
	'auth' => 'Auth\AuthController',
	//'password' => 'Auth\PasswordController',
]);
