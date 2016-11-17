<?php

namespace App\Services;
use App\Repository\ResetRepository;
use Validator;

class ResetService
{
    protected $resetRepo;
    public function __construct(ResetRepository $resetRepo)
    {
        $this->resetRepo = $resetRepo;
    }
    public function requestValidate($request)
    {
        $result = '';
        if ($request->reseType == 'other') {
            $messages = [
                'auto_email.required' => '請輸入您的信箱',
            ];
            $validator = Validator::make($request->all(), [
                'auto_email' => 'required|email',
            ], $messages);
        } else {
            $messages = [
                'password.required' => '請輸入新密碼',
                'password_confirm.same' => '請輸入再次輸入一次密碼'
            ];
            $validator = Validator::make($request->all(), [
                'password' => 'required|min:6',
                'password_confirm' => 'required|same:password'
            ], $messages);
        }

        if ($validator->fails()) {
            $result = $validator->errors()->first();
        }

        return $result;
    }
    public function resetPassword($request)
    {
        return $this->resetRepo->reset($request);
    }
    public function resetOwnPassword($request)
    {
        return $this->resetRepo->resetOwn($request);
    }
}
