<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable, CanResetPassword;
    protected $fillable = ['name', 'email', 'password','url','address','phone_no'];
    protected $hidden = [ 'remember_token'];

    public static function rules($action,$id=NULL){
        $rules = [
            'add' => [
                'name'          => 'required',
                'email'         => 'required|email|unique:users',
                'password'      => 'required|same:con_password',
                'con_password'  =>'required',
                'url'           => 'required',
                'phone_no'      => 'required'
            ],
             'edit' => [
                'name'          => 'required',
                 'email'         => 'required|email|unique:users,email,'. $id,
                'url'           => 'required',
                'phone_no'      => 'required'
            ],
             'login' => [
                'email' => 'required|email',
                'password' => 'required',
            ],
            'change' => [
            'old_password' => 'required',
            'new_password' => 'required|same:con_password',
            'con_password' =>  'required'
            ],
            'update' =>[
                'file' => 'max:5000'
            ]
            
        ];
        return $rules[$action];
    }

}
