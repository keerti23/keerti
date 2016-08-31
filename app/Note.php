<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;


class Note extends Model {

    
    protected $table = 'notes';
    protected $fillable = ['note'];


    public static function rules($action,$id=NULL){
        $rules = [
            'Add' => [
                'note'          => 'required',
            ],
            'Edit' => [
                'note'          => 'required',

            ]
        ];
        return $rules[$action];
    }

}
