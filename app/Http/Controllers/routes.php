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

Route::get('/', function()
{
	return View::make('hello');
});


Route::resource('patient', 'PatientController');
Route::resource('ward', 'WardController');
Route::resource('bed', 'BedController');
Route::resource('transaction', 'TransactionController');
Route::resource('record', 'RecordController');
Route::resource('doctor', 'DoctorController');
Route::resource('nurse', 'NurseController');
Route::resource('user', 'UserController');
