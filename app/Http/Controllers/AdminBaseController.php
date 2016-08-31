<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Setting;


class AdminBaseController extends Controller {

    public $data = [];

    public function __construct()
    {
        $this->data['admin'] = Auth::admin()->get();
        $this->data['settings'] =Setting::find(1);
    }

}
