<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;


class Product extends Model {

    
    protected $table = 'product';
    
    public static function rules($action,$id=NULL){
        $rules = [
            'Add' => [
                'item_name'           => 'required',
                'amazon_asin'         => 'required',
                'product_upc'         => 'required',
                'product_retail_value'=> 'required',
                'review_due_days'     => 'required',
                'amazon_product_url'  => 'required',
                'description'         => 'required',
            ] 
        ];
        return $rules[$action];
    }

}
