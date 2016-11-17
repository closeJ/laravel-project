<?php

namespace App\Http\View;

use Illuminate\View\View;
use Illuminate\Contracts\Auth\Guard;
use App\User;

class Menu
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * 將資料綁定到視圖。
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        $id = $this->auth->user()->id;
        $member = User::find($id);
        $mainMenuArray = [];
        $subMenuArray = [];
            foreach ($member->roles as $group) {
                foreach ($group->menus->where('is_show',1) as $menu){
                if ($menu->parent_id == 0) {
                    $mainMenuArray[$menu->id] = [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    ];
                } else {
                    $subMenuArray[$menu->parent_id][$menu->id] = [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'method_name' => ($menu->methodName == 'index') ? '' : $menu->methodName,
                    'route_name' => $menu->routeName,
                    ];
                }
              }
            }
        $view->with(['mainMenuArray' => $mainMenuArray, 'subMenuArray' => $subMenuArray]);
    }
}
