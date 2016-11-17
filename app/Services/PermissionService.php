<?php

namespace App\Services;

use App\Repository\PermissionRepository;

class PermissionService
{
    protected $permissionRepo;

    public function __construct(PermissionRepository $permissionRepo)
    {
        $this->permissionRepo = $permissionRepo;
    }

    /**
     * 撈出的權限資料分層.
     *
     * @param array $groups
     *
     * @return array
     */
    public function getUserPermissions($permissions = [])
    {
        if (count($permissions) === 0) {
            $userData = $this->getPermissionData();
            $permissionIds = [];

           foreach ($userData->roles as $group) {
                foreach ($group->menus as $permission) {
                    $permissionIds[] = $permission->id;
                }
            }

            return $permissionIds;
        }

        return $permissions;
    }

    public function getPermissionData()
    {
        $this->permissionData = $this->permissionRepo->getUserData(\Auth::user()->id);

        return $this->permissionData;
    }

    public function getCurrentPermission($currentAction = [])
    {
        //取得目前權限id
        if (count($currentAction) === 0) {
            $currentAction = getCurrentAction();
        }
        $this->permissionId = $this->permissionRepo->getPermissionId($currentAction);

        return $this->permissionId;
    }

    public function hasPermission($datas = [])
    {
        if (count($datas) === 0) {
            return in_array($this->getCurrentPermission(), $this->getUserPermissions());
        }

        list($controllerName, $methodName, $permissions) = $datas;

        return in_array($this->getCurrentPermission([$controllerName, $methodName]), $this->getUserPermissions($permissions));
    }
}
