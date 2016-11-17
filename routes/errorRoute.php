<?php
//無此權限提示
Route::get('error',function()
{
	return View::make('errors.disable');
});
