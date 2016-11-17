<?php
namespace App\Services;
use App\Repository\AuthRepository;

class AuthService
{
	protected $authRepo;
	public function __construct(AuthRepository $authRepo)
    {
        $this->authRepo = $authRepo;
    }
    public function save_user_data($data,$user_id)
    {
    	return $this->authRepo->saveUserData($data,$user_id);
    }
    public function save_service_data($data)
    {
    	return $this->authRepo->save_service_data($data);
    }
}