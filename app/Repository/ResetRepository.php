<?php
namespace App\Repository;
use App\CompanyData;
use App\User;
use App\Library\RandomPassword;
use App\Library\Accessibility;
use Auth;

class ResetRepository
{
	protected $model;
    protected $model2;
	public function __construct(CompanyData $model,User $model2)
    {
        $this->model = $model;
        $this->model2 = $model2;
    }
    public function reset($request)
    {
        $RandomPassword = new RandomPassword;
        //重置密碼
        $user = $this->model->find($request->id);
        $userId = $user->user_id;
        $saveUser = $this->model2->find($userId);

        //取得英數字隨機密碼
        $password = $RandomPassword->generateStrongPassword();
        $email = $request->auto_email;

        $token = $request->_token;
        $saveUser->password = bcrypt($password);
        $saveUser->remember_token = $token;
        $saveUser->save();

        $sendUser = '帳號 :' .$saveUser->username;
        $sendPass = '密碼 :' .$password;
         //寄送email
        $emailData = [
            'email' => $email,
            'username' => $sendUser,
            'password' => $sendPass,
            'subject' => 'CG遊戲數據後台修改密碼成功通知信',
            'title' => '恭喜修改密碼成功!'
        ];

        $message = '將傳送重置密碼到此 : '.$email.' 信箱';
        $accessibility = new Accessibility;
        $accessibility->email($emailData);
        return $message;
    }
    public function resetOwn($request)
    {
        $id = Auth::user()->id;
        $user_data = $this->model2->find($id);
        $user_data->password = bcrypt($request->password);
        $user_data->remember_token = $request->_token;
        $user_data->save();
        $message = "修改密碼成功";
        return $message;
    }
}