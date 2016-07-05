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

Route::get('/', ['middleware' => 'guest', function () {
    return view('login'); 
}]);

Route::post('/login', array('uses' => 'AuthController@doLogin', 'middleware' => 'guest'));

Route::group(array('middleware' => 'auth'), function() {
	/*==========  Auth routes  ==========*/
	Route::get('/home', 'AuthController@rolCheck');
	Route::get('/logout', 'AuthController@doLogout');

	/*==========  RESTful routes  ==========*/
	Route::get('wards', 'BedController@getWards');
	Route::resource('patients', 'PatientController');
	Route::resource('beds', 'BedController');
	Route::resource('transactions', 'TransactionController');
	Route::resource('records', 'RecordController');
	Route::resource('staff', 'StaffController');
	Route::resource('users', 'UserController');
});