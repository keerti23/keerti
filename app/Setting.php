<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {

    protected $guarded = ['id'];

    public static function rules($action)
    {
        $rules = [
            'update' => [
                'email'     => 'required|email',
                'name'      => 'required',
                'logo'      => 'image',
                'address'   => 'required',
                'phone_number'=> 'required|numeric',
            ],
        ];
        return $rules[$action];
    }

}
