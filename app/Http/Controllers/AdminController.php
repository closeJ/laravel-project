<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination\Paginator;
use App\Services\AdminUserService;
use App\User;
use App\Role;
use App\Http\Requests;
use App\Library\MenuName;

class AdminController extends Controller
{
	protected $adminUserService;

    public function __construct(AdminUserService $adminUserService)
    {
        $this->adminUserService = $adminUserService;
    }
    public function index(Request $request)
    {
        $menuName = new MenuName;
        $title = $menuName->getName();
    	$request->session()->put('prev_url', $request->url());
        $roles = Role::all();
        $id = $this->getAjaxId($request);
        $users = $this->adminUserService->getAdminRole();
        $user_role = $this->adminUserService->getUserRoles($id);
        $selectUser = User::lists('username','id');
        if (app('super') === true) {
    	   return view('admin.admin_list',['users' => $users,'selectUser' => $selectUser,'user_role' => $user_role,'roles' => $roles,'title' => $title]);
        } else {//若無此權限則顯示提醒頁面
            return redirect('error');
        }
    }
    public function manage(Request $request)
    {
       $user_id = $request->admin_id;
       $user_role = $request->userRoles;
       $this->adminUserService->getAjaxRequest($user_id,$user_role);
    }
    public function getAjaxId(Request $request)
    {
        $id = $request->id;
        return $id;
    }
}
