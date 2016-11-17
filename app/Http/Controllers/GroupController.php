<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Services\GroupService;
use Carbon\Carbon;
use App\Role;
use Exception;
use App\Library\MenuName;

class GroupController extends Controller
{
    protected $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function index(Request $request)
    {
        $menuName = new MenuName;
        $title = $menuName->getName();
        $request->session()->put('prev_url', $request->url());
        $groups = Role::all();
        if (app('super') === true) {
            return view('group.group_list', ['groups' => $groups,'title' => $title]);
        } else {//若無此權限則進入錯誤提醒頁面
            return redirect('error');
        }
    }

    public function manage(Request $request)
    {
        $menuTree = $this->groupService->getMenuTree();
        $id = $request->input('id');
        $groupPermissions = $this->groupService->getGroupPermissions($id);

        return view('group.group_manger', ['groupPermissions' => $groupPermissions, 'menuTree' => $menuTree]);
    }

    public function save(Request $request)
    {
        $id = $request->input('id');
        $name = $request->name;
        $status = 'ok';
        $message = '';
        try {
            $requestValidateMsg = $this->groupService->requestValidate($request);
            if ($requestValidateMsg != '') {
                throw new Exception($requestValidateMsg);
            }

            if ($id == '') {
                $group = new Role;
                $group->name = $name;
                $group->save();
                $groupId = $group->id;

                $group = Role::find($groupId);
                $group->menus()->sync($request->permissions);
            } else {
                $group = Role::find($id);
                $group->menus()->sync($request->permissions);
                $group->name = $name;
                $group->updated_at = Carbon::now();
                $group->save();
            }
        } catch (Exception $e) {
            $status = 'fail';
            $message = $e->getMessage();
        }
        return response()->json(compact('status', 'message'));
    }
    public function destroy(Request $request)
    {
        $id = $request->input('id');
        Role::destroy($id);
        return redirect()->action("GroupController@index");
    }
}
