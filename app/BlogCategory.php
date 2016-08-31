<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;


class BlogCategory extends Model {

    
    protected $table = 'blog_category';



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
