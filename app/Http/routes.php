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

Route::get('/', function () {
    return view('welcome');
});

Route::post('users', 'UserController@index');
Route::get('push', 'UserController@create');
Route::post('login', 'UserController@login');
Route::post('xpto', 'UserController@store');
Route::get('allusers', 'UserController@allusers');
Route::get('send_to_rapha', 'UserController@send_to_rapha');