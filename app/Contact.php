<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;


class Contact extends Model {

    
    protected $table = 'contact';
    protected $fillable = ['name','email','message'];


    public static function rules($action,$id=NULL){
        $rules = [
            'Add' => [
                'name'          => 'required',
                'email'         => 'required|email|unique:contact',
                'message'       => 'required',
            ]
        ];
        return $rules[$action];
    }

}
