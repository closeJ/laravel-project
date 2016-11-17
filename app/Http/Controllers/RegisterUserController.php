<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests;
use Validator;
use Auth;

class RegisterUserController extends Controller
{
	protected $username = 'username';
    protected $redirectTo = 'other/register';
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function index()
    {
		return view('auth.register');
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'username' => 'required|max:50',
            'email' => 'required|email|max:255|'
        ]);
    }
    public function register(Request $request)
    {
    	 if ($request->role == '營運') {
             $validator = $this->validator($request->all());

            if ($validator->fails()) {
                $this->throwValidationException(
                    $request, $validator
                );
            }
            $data = $this->createOther($request->all());
            $request->session()->flash('alert-success',$data);
            return redirect('other/register');
        } else {
            $data = $this->create($request->all());
            return $data;
        }
    }
    protected function create(array $data)
    {
        $user_id = Auth::user()->id;
        $data = $this->authService->save_user_data($data,$user_id);
        return $data;
    }
    protected function createOther(array $data)
    {
        $datas = $this->authService->save_service_data($data);
        return $datas;
    }
}
