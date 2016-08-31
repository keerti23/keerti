<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;


class Company extends Model {

    
    protected $table = 'company';
    protected $fillable = ['name', 'email','contact_name','phone_no','logo'];


    public static function rules($action,$id=NULL){
        $rules = [
            'Add' => [
                'name'          => 'required',
                'email'         => 'required|email|unique:company',
                'contact_name'  => 'required',
                'phone_no'      => 'required'
            ],
            'Edit' => [
                'name'          => 'required',
                'email'         => 'required|email|unique:company,email,'. $id,
                'contact_name'  => 'required',
                'phone_no'      => 'required'

            ]
        ];
        return $rules[$action];
    }

}
