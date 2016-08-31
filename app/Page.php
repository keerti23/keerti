<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;


class Page extends Model {

    
    protected $table = 'pages';
    protected $fillable = ['title','sub_title','description','meta_title','meta_keyword','meta_description'];


    public static function rules($action,$id=NULL){
        $rules = [
            'Add' => [
                'title'          => 'required',
                
            ],
            'Edit' => [
                'title'          => 'required',

            ]
        ];
        return $rules[$action];
    }

}
