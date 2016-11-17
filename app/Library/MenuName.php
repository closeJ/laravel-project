<?php
/* get this you visit menu name */
namespace App\Library;
use App\Menu;

class MenuName
{
	public function getName()
	{
		$controller = getCurrentControllerName();
		$data = Menu::where('controlName',$controller)->select('name')->get();
		return $data[0];
	}
}