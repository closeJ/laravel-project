<?php

namespace App\Repository;
use App\User;


class AdminRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getdata()
    {
        $users = $this->model->paginate(30);
        return $users;
    }
    public function insert_data($user_id,$user_role)
    {
       //新增user到role_user table
       $user = $this->model->find($user_id);
       $user->roles()->sync($user_role);
    }
}
