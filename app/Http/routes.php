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

Route::get('classSwitchings/create', 'ClassSwitchingsController@create');
Route::get('classSwitchings/notChecked', 'ClassSwitchingsController@notChecked');
Route::get('classSwitchings/{id}', 'ClassSwitchingsController@show');
Route::get('classSwitchings/{id}/edit', 'ClassSwitchingsController@edit');
Route::post('classSwitchings', 'ClassSwitchingsController@store');
Route::patch('classSwitchings/{id}', 'ClassSwitchingsController@update');
Route::patch('classSwitchings/{id}/pass', 'ClassSwitchingsController@pass');
Route::patch('classSwitchings/{id}/reject', 'ClassSwitchingsController@reject');

Route::get('substitutes/create', 'SubstitutesController@create');
Route::get('substitutes/{id}', 'SubstitutesController@show');
Route::post('substitutes', 'SubstitutesController@store');

Route::get('teachers', 'LeavesController@getTeacherNames');

//Route::get('home', 'HomeController@index');

Route::get('password/reset', 'PasswordController@index');
Route::post('password/reset', 'PasswordController@reset');

Route::controllers([
	'auth' => 'Auth\AuthController',
	//'password' => 'Auth\PasswordController',
]);
