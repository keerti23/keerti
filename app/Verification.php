<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;


class Verification extends Model {

    
    protected $table = 'mobile_verification';
    protected $fillable = ['opt_code','phone_no','status'];


    public static function rules($action,$id=NULL){
        $rules = [
            'Add' => [
                'phone_no'          => 'required',
            ],
          ];
        return $rules[$action];
    }

}
