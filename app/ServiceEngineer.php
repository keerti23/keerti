<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class ServiceEngineer extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable, CanResetPassword;
    protected $table = 'serviceEngineers';
    protected $fillable = ['name', 'email', 'password','employee_number'];
    protected $hidden = ['password', 'remember_token'];

    public static function rules($action,$id=NULL){
        $rules = [
            'login' => [
                'email'          => 'required|email',
                'password'       => 'required',
            ],
            'passwordReset' =>[
                'oldpassword'    =>'required',
                'newpassword'    =>'required|same:confirmpassword',
                'confirmpassword'=>'required',
            ],
            'Add' => [
                'name'          => 'required',
                'email'         => 'required|email|unique:serviceEngineers',
                'password'      => 'required|same:passwordAgain',
                'passwordAgain' => 'required',
                'employee_number'=>'required|unique:serviceEngineers'

            ],
            'Edit' => [
                'name'          => 'required',
                'email'         => 'required|email|unique:serviceEngineers,email,'. $id,
                'employee_number'=>'required|unique:serviceEngineers,employee_number,'. $id

            ]
        ];
        return $rules[$action];
    }

}
