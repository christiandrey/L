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

Route::get('/', ['uses' => 'PagesController@index']);
Route::get('blade', 'PagesController@blade');
Route::get('users/create', ['uses' => 'UsersController@create']);
Route::post('users', ['uses' => 'UsersController@store']);

/*
Route::get('/users', function () {
  $users = [
    '0' => [
      'first_name' => 'Oluwaseun',
      'last_name' => 'Adedire',
      'location' => 'Abuja'
    ],
    '1' => [
      'first_name' => 'Toluwalopemi',
      'last_name' => 'Adebowale',
      'location' => 'Ogun'
    ]
  ];

  return $users;
});
*/

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::group(['middleware' => 'authenticated'], function () {
    Route::get('users', 'UsersController@index');
    Route::get('profile', 'PagesController@profile');
    Route::get('settings', 'PagesController@settings');
});
