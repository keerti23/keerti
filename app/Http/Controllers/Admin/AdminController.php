<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\AdminBaseController;
use App\Admin;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;

use Illuminate\Http\Request;

class AdminController extends AdminBaseController {

	public function login()
    {
        $form = [
            'action'    =>  route('admin.logincheck'),
            'Heading'   =>  'Admin Login',
        ];

        return view ('layouts.loginform',$form);
    }

    public function loginCheck(Request $request)
    {
        $validator = Validator::make($request->all(),Admin::rules('login'));
        if ($validator->fails()) {
            return [
                'status'     => 'error',
                'message'    => $validator->getMessageBag()->toArray()
            ];

        }
        else {
            $userData = [
                'email'      =>  $request->input('email'),
                'password'   =>  $request ->input('password')
            ];
            if (Auth::admin()->validate($userData)) {
                if (Auth::admin()->attempt($userData)) {
                    return [
                        'status'    => 'success',
                        'message'   => Lang::get("messages.loginSuccess"),
                        'url'       => route('admin.dashboard'),
                        'action'    => 'redirect'

                    ];
                }
            }
            // if any error send back with message
            else {
                return [
                    'status'     => 'error',
                    'message'   => Lang::get("messages.incorrectLogin")
                ];
            }
        }
    }

    public function logout()
    {
        Auth::admin()->logout();
        Session::flush();
        return Redirect::route('admin.login')->send();
    }


}
