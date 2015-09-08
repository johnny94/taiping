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

Route::get('classSwitchings/create', 'ClassSwitchingsController@create');
Route::get('classSwitchings/notChecked', 'ClassSwitchingsController@notChecked');
Route::get('classSwitchings/{id}', 'ClassSwitchingsController@show');
Route::get('classSwitchings/{id}/edit', 'ClassSwitchingsController@edit');
Route::post('classSwitchings', 'ClassSwitchingsController@store');
Route::patch('classSwitchings/{id}', 'ClassSwitchingsController@update');
Route::patch('classSwitchings/{id}/pass', 'ClassSwitchingsController@pass');
Route::patch('classSwitchings/{id}/reject', 'ClassSwitchingsController@reject');
Route::delete('classSwitchings/{id}', 'ClassSwitchingsController@destroy');

Route::get('manager/switchings', 'ManagerController@switchings');
Route::post('manager/fetchSwitchings', 'ManagerController@fetchSwitchings');
Route::delete('manager/deleteSwitching/{id}', 'ManagerController@deleteSwitching');
Route::get('manager/export/switchingLog', 'ManagerController@exportSwitchingLog');

Route::get('manager/users', 'ManagerController@users');
Route::post('manager/fetchRegisteredUser', 'ManagerController@fetchRegisteredUser');
Route::delete('manager/deleteUser/{id}', 'ManagerController@deleteUser');
Route::get('manager/setManager', 'ManagerController@setManager');
Route::post('manager/setManager', 'ManagerController@setAsManager');
Route::get('manager/exportLog', 'ManagerController@exportLog');
Route::get('manager/export/userDeletionLog', 'ManagerController@exportUserDeletionLog');
Route::get('manager/export/switchingDeletionLog', 'ManagerController@exportSwitchingDeletionLog');
Route::get('teachers', 'ClassSwitchingsController@getTeacherNames');

Route::get('manager/subjects', 'SubjectsController@index');
Route::post('manager/subjects', 'SubjectsController@store');
Route::post('api/subjects', 'SubjectsController@fetchAllSubjects');
Route::delete('manager/subjects/{id}', 'SubjectsController@destroy');
Route::patch('manager/subjects/{id}', 'SubjectsController@update');

Route::get('manager/periods', 'PeriodsController@index');
Route::post('manager/periods', 'PeriodsController@store');
Route::post('api/periods', 'PeriodsController@fetchAllPeriods');
Route::delete('manager/periods/{id}', 'PeriodsController@destroy');
Route::patch('manager/periods/{id}', 'PeriodsController@update');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('/activate/{code}', 'Auth\AuthController@activateAccount');
