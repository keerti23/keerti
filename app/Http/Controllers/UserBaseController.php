<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;



class UserBaseController extends Controller {

    public $data = [];

    public function __construct()
    {
        $this->data['user'] = Auth::user()->get();
    }

}
