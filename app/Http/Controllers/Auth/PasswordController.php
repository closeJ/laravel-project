<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Exception;
use App\Services\ResetService;
use Validator;
use App\CompanyData;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct(ResetService $resetService)
    {
        $this->resetService = $resetService;
    }
    public function index(Request $request)
    {
        $id = $request->id;
        $email = CompanyData::find($id);
        return view('auth.passwords.reset',['id' => $id,'email' => $email]);
    }
    public function ownReset()
    {
        return view('auth.passwords.reset_own');
    }
    public function reseting(Request $request)
    {
        $status = 'ok';
        $message = '';
        try  {
            $requestValidateMsg = $this->resetService->requestValidate($request);
            if ($requestValidateMsg != '') {
                throw new Exception($requestValidateMsg);
            }
            if ($request->reseType == 'other') {
                $data = $this->resetService->resetPassword($request);
            } else {
                $data = $this->resetService->resetOwnPassword($request);
            }
        } catch (Exception $e){
            $status = 'fail';
            $message = $e->getMessage();
        }
        return response()->json(compact('status', 'message','data'));
    }
}
