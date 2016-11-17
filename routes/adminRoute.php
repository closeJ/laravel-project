<?php

Route::group(['prefix' => 'admin','middleware' => 'auth'],function()
{
	Route::get('/','AdminController@index');
	Route::post('manage','AdminController@manage');
	Route::get('getId','AdminController@getAjaxId');
	Route::get('group','GroupController@index');
	Route::get('/group/manage','GroupController@manage');
	Route::post('save','GroupController@save');
	Route::get('/group/destroy','GroupController@destroy');
});
