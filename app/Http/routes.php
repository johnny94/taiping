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

// TODO:
//   1. Controller method: notChecked -> not-checked
Route::get('class-switchings/create', 'ClassSwitchingsController@create');
Route::get('classSwitchings/notChecked', 'ClassSwitchingsController@notChecked');
Route::get('classSwitchings/{id}', 'ClassSwitchingsController@show');
Route::get('classSwitchings/{id}/edit', 'ClassSwitchingsController@edit');
Route::post('classSwitchings', 'ClassSwitchingsController@store');
Route::patch('classSwitchings/{id}', 'ClassSwitchingsController@update');
Route::patch('class-switchings/{id}/status', 'ClassSwitchingsController@updateStatus');
Route::delete('classSwitchings/{id}', 'ClassSwitchingsController@destroy');
Route::delete('classSwitchings', 'ClassSwitchingsController@destroyAll');

Route::get('manager/switchings', 'ManagerController@switchings');
Route::get('manager/users', 'ManagerController@users');
Route::get('manager/subjects', 'ManagerController@subjects');
Route::get('manager/periods', 'ManagerController@periods');
Route::get('manager/settings', 'ManagerController@settings');
Route::get('manager/exportLog', 'ManagerController@exportLog');

Route::get('logs/download/switching-log', 'LogsController@exportSwitchingLog');
Route::get('logs/download/user-deletion-log', 'LogsController@exportUserDeletionLog');
Route::get('logs/download/switching-deletion-log', 'LogsController@exportSwitchingDeletionLog');

// TODO: Move export method
Route::delete('manager/deleteSwitching/{id}', 'ClassSwitchingsController@destroyByAdmin');

Route::delete('users/{id}', 'UsersController@destroy');
Route::put('users/manager', 'UsersController@setAsManager');
Route::delete('users/manager/{id}', 'UsersController@unsetManager');
Route::patch('users/{id}/active', 'UsersController@active');

Route::post('subjects', 'SubjectsController@store');
Route::delete('subjects/{id}', 'SubjectsController@destroy');
Route::patch('subjects/{id}', 'SubjectsController@update');

Route::post('periods', 'PeriodsController@store');
Route::delete('periods/{id}', 'PeriodsController@destroy');
Route::patch('periods/{id}', 'PeriodsController@update');

Route::get('api/class-switchings/search', 'ClassSwitchingsController@search');
Route::get('api/users/names', 'UsersController@searchByName');
Route::get('api/users/search', 'UsersController@search');
Route::get('api/periods/search', 'PeriodsController@search');
Route::get('api/subjects/search', 'SubjectsController@search');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('/activate/{code}', 'Auth\AuthController@activateAccount');
