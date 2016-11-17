<?php
Route::Auth();
Route::get('/', 'HomeController@index');

Route::group(['prefix' => 'password'],function() {
	Route::get('/','Auth\PasswordController@index');
	Route::get('own','Auth\PasswordController@ownReset');
	Route::post('store','Auth\PasswordController@store');
	Route::post('reseting','Auth\PasswordController@reseting');
});
Route::group(['prefix' => 'other'],function() {
	Route::get('register','RegisterUserController@index');
	Route::post('valian','RegisterUserController@register');
});

