<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/verifyEmailFirst', 'Auth\RegisterController@verifyEmailFirst')->name('verifyEmailFirst');
Route::get('/verify/{email}/{verify_token}','Auth\RegisterController@sendEmailDone')->name('sendEmailDone');


Route::resource('categories', 'CategoryController');

Route::resource('nominations', 'NominationController');

Route::resource('nominationUsers', 'NominationUserController');

Route::resource('roles', 'RoleController');

Route::resource('settings', 'SettingController');

Route::resource('users', 'UserController');

Route::resource('votings', 'VotingController');