<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;


class Category extends Model {

    
    protected $table = 'category';
    protected $fillable = ['name'];


    public static function rules($action,$id=NULL){
        $rules = [
            'Add' => [
                'name'          => 'required',
            ],
            'Edit' => [
                'name'          => 'required',

            ]
        ];
        return $rules[$action];
    }

}
