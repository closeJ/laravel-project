<?php
namespace App\Library;
use Excel;
use Mail;
use App\VendorAccount;
use App\CompanyData;
use App\User;

class Accessibility
{
	public function export($filename,$datas)
	{
		Excel::create($filename,function($excel) use ($datas) {
            $excel->sheet('report',function($sheet) use ($datas) {
                $sheet->row(1,$datas[0]);//標題
                $sheet->rows($datas[1]);//資料
            });
        })->export('xls');
	}
	public function email($emailData)
	{
		Mail::queue('emails.welcome', $emailData , function ($message) use ($emailData) {
            $message->to($emailData['email'])->subject($emailData['subject']);
        });
	}
	public function userRoleId($user_id)
	{
		$user = User::find($user_id);
		$role = $user->roles()->first();
		$company = CompanyData::where('user_id',$user_id)->get();
		$subUser = [];
		if ($role->id != 1 && $role->id != 2) {
			$subUser = CompanyData::where('parent_id',$company[0]->id)->get();
		} else {
			$subUser = CompanyData::where('parent_id',0)->get();
		}
		return $subUser;
	}
	//取得總代理、代理所屬平台
	public function getPlatformId($role,$user_id)
	{
		$proxyUser = CompanyData::where('user_id',$user_id)->first();
		$platform = [];
		$platformDatas = [];
		$proxies = [];
		$data = [];
		switch ($role->id) {
            case 1:
            case 2:
                $distributors = CompanyData::where('parent_id',0)->get();
                foreach ($distributors as $distributor) {
                    $proxies[$distributor->id] = CompanyData::where('parent_id',$distributor->id)->get();
                    foreach ($proxies[$distributor->id] as $proxy) {
		            	if (count($proxy) > 0) {
		            		$platformDatas[$proxy->parent_id] = CompanyData::where('parent_id',$proxy->id)->get();
		            		foreach ($platformDatas[$proxy->parent_id] as $platformData) {
				            	if (count($platformData) > 0) {
				            		$platform[$platformData->parent_id][] = $platformData;
				            	} else {
				            		$platform = [];
				            	}
	            			}
		            	} else {
		            		$platformDatas = [];
		            	}
	            	}
	            	$data = collect([$proxies,$platformDatas,$platform])->all();
                }
                break;
            case 3:
                $proxyDatas = CompanyData::where('parent_id',$proxyUser->id)->get();
                foreach ($proxyDatas as $proxyData) {
                	if(count($proxyDatas) > 1) {
                		$platformDatas[$proxyData->id] = CompanyData::where('parent_id',$proxyData->id)->get();
                		 foreach ($platformDatas[$proxyData->id] as $platformData) {
		                	if(count($platformDatas) > 0) {
		                		$platform[$platformData->parent_id][] = $platformData->platform_id;
		                	} else {
		                		$platform = [];
		                	}
		                }
                	} else {
                		$platformDatas = [];
                	}
                	$data = collect([$platformDatas,$platform])->all();
                }
            case 4:
                $platformDatas = CompanyData::where('parent_id',$proxyUser->id)->get();
                foreach ($platformDatas as $platformData) {
                	if (count($platformDatas) > 1) {
                		$data[] = $platformData->platform_id;
                	} else {
                		$data = [];
                	}
                }
                break;
        }
        return $data;
	}
	//取得CG遊戲玩家ID
	public function getPlayerId($user_id)
	{
		$user_random = VendorAccount::where('username',$user_id)->get();
		$data = [];
		foreach ($user_random as $user) {
			$data = $user->members->username;
		}
		return $data;
	}
}