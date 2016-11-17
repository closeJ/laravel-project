<?php

namespace App\Services;

use App\Role;
use App\Menu;
use Validator;

class GroupService
{
    public function requestValidate($request)
    {
        $result = '';
        $messages = [
            'name.required' => '請填寫名稱',
            'permissions.required' => '至少要勾選一項權限',
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permissions' => 'required',
        ], $messages);

        if ($validator->fails()) {
            $result = $validator->errors()->first();
        }

        return $result;
    }

    /**
     * 撈出的權限資料分層.
     *
     * @param array $groups
     *
     * @return array
     */
    public function getMenuTree()
    {
        // 模組名稱
        $modules = [];
        // 功能名稱
        $mainMenu = [];
        // 子功能
        $subMenu = [];

        $menus = Menu::all();
        foreach ($menus as $menu) {
            $parent_id = $menu->parent_id;
            $is_sub = $menu->is_sub;
            $id = $menu->id;
            $name = $menu->name;
            switch ($parent_id) {
                case 0:
                    $modules[] = ['id' => $id, 'name' => $name];
                    break;

                default:
                    if ($is_sub == 1) {
                        $subMenu[$parent_id][] = ['id' => $id, 'name' => $name];
                    } else {
                        $mainMenu[$parent_id][] = ['id' => $id, 'name' => $name];
                    }
                    break;
            }
        }

        $result = [
            'modules' => $modules,
            'mainMenu' => $mainMenu,
            'subMenu' => $subMenu,
        ];
        return $result;
    }

    public function getGroupPermissions($id)
    {
        $groupPermissions = [];
        $role = Role::where('id', $id)->get();
        foreach ($role as $rolelist) {
            $name = $rolelist->name;
            foreach ($rolelist->menus as $permission) {
                $permission_id = $permission->id;
                $groupPermissions[] = $permission_id;
            }
        }
        $result = [
            'id' => $id,
            'name' => set_default($name),
            'groupPermissions' => $groupPermissions,
        ];
        return $result;
    }
}
