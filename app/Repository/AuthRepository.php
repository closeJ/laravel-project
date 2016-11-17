<?php

namespace App\Repository;
use App\User;
use App\CompanyData;
use App\Library\RandomPassword;
use App\Library\Accessibility;

class AuthRepository
{
    protected $model;
    protected $random;
    protected $sendEmail;
    public function __construct(CompanyData $model,RandomPassword $random,Accessibility $sendEmail)
    {
        $this->model = $model;
        $this->random = $random;
        $this->sendEmail = $sendEmail;
    }
	public function saveUserData($data,$user_id)//儲存總代理、代理、平台創建帳號資料
	{
        //取得英數字隨機密碼
        $password = $this->random->generateStrongPassword();
        $random = $this->random->generateStrongPassword(4,false,'ud');
        if($data['role'] == '平台會員'){
            $user = new User;
            $user->username = $random;
            $user->password = bcrypt($password);
            $user->save();
            $insertId = $user->id;
            $update = User::find($insertId);
            $update->username = "a".$insertId.$random;
            $alertName = '平台帳號|平台密碼';
            $update->roles()->attach(5);//平台會員代號
            $update->save();
        } else {
            $user = new User;
            $user->username = $random;
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = bcrypt($password);
            $user->save();
            $insertId = $user->id;
            $update = User::find($insertId);
            if ($data['role'] == '總代理'){
                $update->username = "DC".$insertId.$random;
                $alertName = '總代理帳號|總代理密碼';
                $update->roles()->attach(3);//總代理群組代號
            } elseif($data['role'] == '代理'){
                $update->username = "A".$insertId.$random;
                $alertName = '代理帳號|代理密碼';
                $update->roles()->attach(4);//代理群組代號
            }
            $update->save();
        }

        $userData = User::find($user_id);
        $companyId = $this->model->where('user_id',$user_id)->get();
        $role = $userData->roles()->select('id')->get();
        if ($data['role'] == '平台會員') {
            $company = new CompanyData;
            $company->user_id = $insertId;
            $company->credit = $data['credit'];
            $company->credit_before = $data['credit'];
            $company->currency = $data['current'];
            $company->lock_time = '';
            if ($role[0]->id == '4') { //僅限代理可創建平台帳號
                $company->parent_id = $companyId[0]->id;
            }
            if ($data['comission'] == 1){
                $company->commission = $data['comiddion_yes'];
                $company->is_comis = 1;
            } else {
                $company->commission = 0;
                $company->is_comis = 0;
            }
            $company->platform_id = $data['platform'];
            $company->save();
        } else {
            $company = new CompanyData;
            $company->user_id = $insertId;
            $company->country = $data['country'];
            $company->currency = $data['current'];
            $company->phone = $data['phone'];
            $company->company_phone = $data['companyPhone'];
            $company->address = $data['address'];
            $company->credit = $data['credit'];
            $company->credit_before = $data['credit'];
            $company->platform_id = 0;
            $company->lock_time = '';
            if($data['role'] == '代理' && $role[0]->id == '3'){ //僅限總代理可創建帳號
                $company->parent_id = $companyId[0]->id;
            } elseif( $role[0]->id == 1 || $role[0]->id == 2){
                $company->parent_id = 0;
            }
            if ($data['comission'] == 1){
                $company->commission = $data['comiddion_yes'];
                $company->is_comis = 1;
            } else {
                $company->commission = 0;
                $company->is_comis = 0;
            }
            $company->save();
        }
        //return create user data
        if(!empty($data['email'])){
            $email = '已寄送您剛創建的帳號密碼到此:'.$data['email'].'信箱';
        } else {
            $email = '';
        }
        if ($data['role'] == '總代理'){
            $username = "DC".$insertId.$random;
        } elseif($data['role'] == '代理'){
            $username = 'A'.$insertId.$random;
        } else {
            $username = 'a'.$insertId.$random;
        }
        $datas = collect([
            'name' => $alertName,
            'username' => $username,
            'password' => $password,
            'credit' => $data['credit'],
            'email' => $email,
        ]);

        //  if ($data['role'] != '平台會員') {
        //     //寄送email
        //     $emailData = [
        //         'email' => $data['email'],
        //         'username' => '您的帳號 : '.$data['username'].'00'.$insertId,
        //         'password' => '您的密碼 : '.$password,
        //         'subject' => 'CG遊戲數據後台創建帳號成功通知信',
        //         'title' => '創建帳號成功'
        //     ];
        //     $this->sendEmail->email($emailData);
        // }
        return $datas;
	}
    public function save_service_data($data)//創建營運帳號
    {
        $password = $this->random->generateStrongPassword();
        $user = new User;
        $user->name = $data['name'];
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->password = bcrypt($password);
        $user->save();
        $insertId = $user->id;
        $update = User::find($insertId);
        $update->roles()->attach(2);//營運代號
        $update->save();
        $alertName = '創建成功!<br>帳號為: '. $data['username'].'<br>密碼為: '. $password.'<br><a href="/">返回首頁</a>';
        //寄送email
        // $emailData = [
        //     'email' => $data['username'],
        //     'username' => '您的帳號 : '.$data['username'],
        //     'password' => '您的密碼 : '.$password,
        //     'subject' => 'CG遊戲數據後台創建帳號成功通知信',
        //     'title' => '創建帳號成功'
        // ];
        // $this->sendEmail->email($emailData);
        return $alertName;
    }
}