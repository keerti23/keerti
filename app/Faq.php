<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;


class Faq extends Model {

    
    protected $table = 'faq';



    public static function rules($action,$id=NULL){
        $rules = [
        'Add' => [
                

            ],
          
            'Edit' => [
                

            ]
        ];
        return $rules[$action];
    }

}
