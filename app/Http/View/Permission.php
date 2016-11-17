<?php

namespace App\Http\View;

use Illuminate\View\View;
use Illuminate\Contracts\Auth\Guard;
use App\User;

class Permission
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
        $userPermissions = [];
        if (isset($this->auth->user()->id)) {
            $id = $this->auth->user()->id;
            $member = User::find($id);

            foreach ($member->roles as $group) {
                foreach ($group->menus as $permission) {
                    $userPermissions[] = $permission->id;
                }
            }
        }
        $view->with(['userPermissions' => $userPermissions]);
    }
}
