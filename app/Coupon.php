<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;


class Coupon extends Model {

    
    protected $table = 'coupon';

    public function scopeHistory($query)
    {
        return $query->where('amazon_review_url_status','=',1)->
          where('review_description','!=',' ')->leftJoin('product','product.id','=','coupon.product_id');
    }
    
}
