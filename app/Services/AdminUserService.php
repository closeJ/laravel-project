<?php

namespace App\Services;
use App\Repository\AdminRepository;
use App\User;

class AdminUserService
{
    protected $adminRepository;
    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }
    public function getAdminRole()
    {
        return $this->adminRepository->getdata();
    }
    public function getAjaxRequest($user_id,$user_role)
    {
        return $this->adminRepository->insert_data($user_id,$user_role);
    }
     public function getUserRoles($id)
    {
        $userRoles = [];
        $user = User::where('id', $id)->get();

        foreach ($user as $userData) {
            $username = $userData->username;
            foreach ($userData->roles as $role) {
                $roleId = $role->id;
                $userRoles[] = $roleId;
            }
        }

        $result = [
            'id' => $id,
            'username' => set_default($username),
            'userRoles' => $userRoles,
        ];
        return $result;
    }
}
