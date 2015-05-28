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
Route::get('leaves/list', 'LeavesController@listLeaves');
Route::get('leaves/{id}/curriculums', 'LeavesController@showCurriculums');
Route::get('leaves/{id}/updateClassSwitchings', 'LeavesController@updateClassSwitchings');
Route::delete('leaves/{id}', 'LeavesController@delete');

Route::get('classSwitchings/create', 'ClassSwitchingsController@create');
Route::get('classSwitchings/notChecked', 'ClassSwitchingsController@notChecked');
Route::get('classSwitchings/{id}', 'ClassSwitchingsController@show');
Route::get('classSwitchings/{id}/edit', 'ClassSwitchingsController@edit');
Route::post('classSwitchings', 'ClassSwitchingsController@store');
Route::patch('classSwitchings/{id}', 'ClassSwitchingsController@update');
Route::patch('classSwitchings/{id}/pass', 'ClassSwitchingsController@pass');
Route::patch('classSwitchings/{id}/reject', 'ClassSwitchingsController@reject');
Route::delete('classSwitchings/{id}', 'ClassSwitchingsController@destroy');

Route::get('substitutes/create', 'SubstitutesController@create');
Route::get('substitutes/{id}', 'SubstitutesController@show');
Route::post('substitutes', 'SubstitutesController@store');

Route::get('manager/users', 'ManagerController@users');
Route::post('manager/fetchRegisteredUser', 'ManagerController@fetchRegisteredUser');
Route::delete('manager/deleteUser/{id}', 'ManagerController@deleteUser');
Route::get('manager/setManager', 'ManagerController@setManager');
Route::post('manager/setManager', 'ManagerController@setAsManager');
Route::get('manager/exportLog', 'ManagerController@exportLog');
Route::get('manager/export/leaveDeletionLog', 'ManagerController@exportLeaveDeletionLog');
Route::get('manager/export/userDeletionLog', 'ManagerController@exportUserDeletionLog');


Route::get('teachers', 'LeavesController@getTeacherNames');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('/activate/{code}', 'Auth\AuthController@activateAccount');
