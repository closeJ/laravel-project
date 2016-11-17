<?php
//手動執行排程
Route::group(['prefix' => 'game'],function()
{
	Route::get('cron',function()
	{
		//Artisan::call('cron:sologame');
		//Artisan::call('cron:report');
		//Artisan::call('cron:record');
		Artisan::call('cron:playerAmount');
	});
});