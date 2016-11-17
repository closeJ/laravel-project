<?php

namespace App\Repository;

use App\User;
use App\Menu;

class PermissionRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getUserData($id)
    {
        $userData = $this->model->find($id);

        return $userData;
    }

    public function getPermissionId($currentAction)
    {
        list($contollerName, $methodName) = $currentAction;
        $permissionId = Menu::where(['controlName' => $contollerName, 'methodName' => $methodName])->select('id')->value('id');

        return $permissionId;
    }
}
