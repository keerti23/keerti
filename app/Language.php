<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;


class Language extends Model {

    
    protected $table = 'languages';



    public static function rules($action,$id=NULL){
        $rules = [
          
            'Edit' => [
                

            ],
             'Add' => [
                

            ]
        ];
        return $rules[$action];
    }

}
