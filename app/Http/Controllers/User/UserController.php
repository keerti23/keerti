<?php namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\UserBaseController;
use App\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Pagination\Paginator;
use App\Coupon;
use App\Product;
use Carbon;
use DB;
use App\PasswordReset;
use App\Admin;
use URL;
use App\Page;
use App\Language;


use Illuminate\Http\Request;

class UserController extends UserBaseController {

      public function __construct()
    {
       
               $this->data['pages']   = Page::limit(3)->get();
               $this->data['contact'] = Page::where('title','=','contact')->first(); 
                return $this->data;
    }

	public function login()
    {
        $form= [
            'action'    =>  route('user.logincheck'),
            'Heading'   =>  'User Login',
        ];

        return view ('layouts.userloginform',$form,$this->data);
    }

    public function loginCheck(Request $request)
    {
        $validator = Validator::make($request->all(),User::rules('login'));
        if ($validator->fails()) {
            return [
                'status'     => 'error',
                'message'    => $validator->getMessageBag()->toArray()
            ];

        }
        else {
            $userData = [
                'email'      =>  $request->input('email'),
                'password'   =>  $request ->input('password')
            ];
            if (Auth::user()->validate($userData)) {
                if (Auth::user()->attempt($userData)) {
              
                if(Auth::user()->get()->status == 'inactive' || Auth::user()->get()->status == 'banned')
                {
                    return [
                    'status'     => 'active_error',
                    'message'   => Lang::get("your account is not active")
                ];
                }
                    return [
                        'status'    => 'success',
                        'message'   => Lang::get("messages.loginSuccess"),
                        'url'       => route('user.dashboard'),
                        'action'    => 'redirect'

                    ];
                }
            }
            // if any error send back with message
            else {
                return [
                    'status'     => 'errors',
                    'message'   => "incorrect LOgin Details"
                ];
            }
        }
    }

    public function logout()
    {
        Auth::user()->logout();
        Session::flush();
        return Redirect::route('user.login')->send();
    }

   
   

   

    public function dashboard()
     {
     
       
        $this->data['current'] = 'active';
      
        // return Carbon\Carbon::parse('2016-08-02 10:25:25')->diffInDays(Carbon\Carbon::now());
       
        Session::forget('productAprroved');
        if(Session::get('product_id') != '')
        {
            $data = Coupon::select('id')->where('product_id','=',Session::get('product_id'))->where('reviewer_id','=',Auth::user()->get()->id)->first();
         
            if($data == '')
            {
            $coupon = Coupon::select('coupon.id as id','coupon.review_title as review_title','coupon.rating as rating','coupon.coupon_code as coupon_code','coupon.review_description as review_description','product.product_image as product_image','product.item_name as name','product.id as product_id','product.review_due_days')->where('reviewer_id','=',null)->
                                where('product_id','=',Session::get('product_id'))->
                                leftJoin('product','product.id','=','coupon.product_id')->first();
                                if($coupon != "")
                                {
                                    $couponData = Coupon::where('coupon_code','=',$coupon->coupon_code)->where('product_id','=',Session::get('product_id'))->first();


                                    $couponData->reviewer_id = Auth::user()->get()->id;
                                    $couponData->coupon_recieved_date = \Carbon\Carbon::now();
                                    $couponData->review_submitted_due_date = \Carbon\Carbon::now()->addDays($coupon->review_due_days);
                                    $couponData->save();
                                    Session::set('productAprroved','yes');
                                    $this->data['data'] = 'yes';
                        }
                                else
                                {
                                     Session::set('productAprroved','no');
                                     $this->data['data'] = "no";
                                }
            }


          

        
        }
       
       $this->data['couponData'] = Coupon::select('coupon.id as id','coupon.review_title as review_title','coupon.rating as rating','coupon.review_description as review_description','coupon.coupon_code as coupon_code','product.product_image as product_image','product.item_name as name','product.id as product_id','coupon.coupon_recieved_date as coupon_recieved_date','coupon.review_submitted_due_date as review_submitted_due_date','coupon.amazon_review_url as amazon_review_url','coupon.onsite_review_url as onsite_review_url','product.amazon_product_url as amazon_product_url','coupon.amazon_review_url_status as amazon_review_url_status')->where('reviewer_id','=',Auth::user()->get()->id)->where(function($query) {
                /** @var $query Illuminate\Database\Query\Builder  */
                return $query->where('review_description','=','')
                    ->orWhere('amazon_review_url_status', '=',0);
            })->leftJoin('product','product.id','=','coupon.product_id')->groupBy('product_id')->get();
        
        $this->data = $this->mainFunction();
        return view ('user.front',$this->data);  

       
    }

    public function review(Request $request)
    {

  $this->data['current'] = 'active';
        $reviewData = Coupon::where('coupon_code','=',$request->input('coupon_code'))->where('product_id','=',$request->input('product_id'))->first();
        $reviewData->review_title   = $request->input('review_title');
        $reviewData->review_description = $request->input('review_description');
        $reviewData->review_submitted_date = \Carbon\Carbon::now();
        $reviewData->rating = $request->input('ratingValue');
        $reviewData->onsite_review_url = URL::to('product/detail/'.$request->input('product_id'));  
        $reviewData->save();

        //Setting flash session message
                Session::flash('toastrHeading', Lang::get("ReviewAdded"));
                Session::flash('toastrMessage', Lang::get("Review Added Successfully"));
                Session::flash('toastrType', 'success');

                 $this->data['couponData'] = Coupon::select('coupon.id as id','coupon.review_title as review_title','coupon.rating as rating','coupon.review_description as review_description','coupon.coupon_code as coupon_code','product.product_image as product_image','product.item_name as name','product.id as product_id','coupon.coupon_recieved_date as coupon_recieved_date','coupon.review_submitted_due_date as review_submitted_due_date','coupon.amazon_review_url as amazon_review_url','coupon.onsite_review_url as onsite_review_url','product.amazon_product_url as amazon_product_url',
                    'coupon.amazon_review_url_status as amazon_review_url')->where('reviewer_id','=',Auth::user()->get()->id)
                ->where(function($query) {
                /** @var $query Illuminate\Database\Query\Builder  */
                return $query->where('review_description','=','')
                    ->orWhere('amazon_review_url_status', '=', 0);
            })
                 ->leftJoin('product','product.id','=','coupon.product_id')->groupBy('product_id')->get();
 

                return view ('user.front',$this->data); 
            
       }

       //amazon review url
       public function amazon(Request $request)
       {


         $this->data['current'] = 'active';

            $reviewData = Coupon::where('coupon_code','=',$request->input('coupon_code'))->where('product_id','=',$request->input('product_id'))->first();
            if($request->input('amazonReview') != '')
            {
                 $reviewData->amazon_review_url   = $request->input('amazonReview');

                
            }
            else
            {
                if($reviewData->review_submitted_due_date < \Carbon\Carbon::now())
                {
                    $user = User::where('id','=',Auth::user()->get()->id)->first();
                    $user->status = 'suspended';
                    $user->save();
                }
               $reviewData->review_title   = $request->input('review_title');
                $reviewData->review_description = $request->input('review_description');
                $reviewData->review_submitted_date = \Carbon\Carbon::now();
                $reviewData->rating = $request->input('ratingValue');
                $reviewData->onsite_review_url = URL::to('product/detail/'.$request->input('product_id'));  
            }
       
        $reviewData->save();
        $success_msg_review = Language::where('name','=','success_msg_review')->first();

        //Setting flash session message
                Session::flash('toastrHeading', Lang::get(""));
                Session::flash('toastrMessage', Lang::get($success_msg_review->value));
                Session::flash('toastrType', 'success');

                 $this->data['couponData'] = Coupon::select('coupon.id as id','coupon.review_title as review_title','coupon.rating as rating','coupon.coupon_code as coupon_code','coupon.review_description as review_description','product.product_image as product_image','product.item_name as name','product.id as product_id','coupon.coupon_recieved_date as coupon_recieved_date','coupon.review_submitted_due_date as review_submitted_due_date','coupon.amazon_review_url as amazon_review_url','coupon.onsite_review_url as onsite_review_url','coupon.amazon_review_url_status as amazon_review_url','coupon.amazon_review_url_status as amazon_review_url_status')->where('reviewer_id','=',Auth::user()->get()->id)
                ->where(function($query) {
                /** @var $query Illuminate\Database\Query\Builder  */
                return $query->where('review_description','=','')
                    ->orWhere('amazon_review_url_status', '=', 0);
            })
                 ->leftJoin('product','product.id','=','coupon.product_id')->groupBy('product_id')->get();
 
  $this->data = $this->mainFunction();
                return view ('user.front',$this->data); 
            
       }

       public function mainFunction()
       {

        $this->data['agree_with_disclosure'] = Language::where('name','=','agree_with_disclosure')->first();
        $this->data['word_count'] = Language::where('name','=','word_count')->first();
   
        $this->data['user'] = User::find(Auth::user()->get()->id);
        
        $this->data['couponTotal'] = Coupon::where('reviewer_id','=',Auth::user()->get()->id)->count();
        
        $this->data['reviewDone'] = Coupon::where('reviewer_id','=',Auth::user()->get()->id)->where('review_description','!=',' ')->where('amazon_review_url_status','=',1)->count();
        $this->data['reviewLeft'] = Coupon::where('reviewer_id','=',Auth::user()->get()->id)->where('review_description','=',' ')->count();
        
        $this->data['reviewLate'] = Coupon::where('reviewer_id','=',Auth::user()->get()->id)->where('review_description','!=',' ')->whereRaw('review_submitted_date > review_submitted_due_date')->where('amazon_review_url_status','=',1)->count();
        
        $this->data['totalRetailValue'] = Coupon::select(DB::raw('SUM(product.product_retail_value ) As value'))->where('reviewer_id','=',Auth::user()->get()->id)->
        leftJoin('product','product.id','=','coupon.product_id')->first();

$user = User::where('id','=',Auth::user()->get()->id)->first();
  
        return $this->data;
       }

       public function userHistory()
       {
          $this->data['history'] = 'active';

         $this->data = $this->mainFunction();
         $this->data['coupon'] = Coupon::select('coupon.id as reviewId','coupon.*','product.*')->history()->where('reviewer_id','=',Auth::user()->get()->id)->orderBy('coupon_recieved_date')->paginate(10);

        return view('user.userhistory',$this->data);
       }

        public function userEdit()
       {
        $this->data = $this->mainFunction();
        $this->data['user'] = User::where('id','=',Auth::user()->get()->id)->first();

        return view('user.useredit',$this->data);
       }

       public function userEditDetail(Request $request)
       {
          


        $user = User::where('id','=',Auth::user()->get()->id)->first();

         if ($request->hasFile('image')) {
            $validator = Validator::make($request->all(),User::rules('update',$request->input('id')));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {

                $file = $request->file('image');
                $oldImage = $user->image;
                File::delete(public_path() . '/assets/admin/upload/' . $oldImage);

                $destination = public_path() . '/assets/admin/upload/';
                $extension = $file->getClientOriginalExtension();
                $filename = rand(1111,9999).'.'.$extension;
                $file->move($destination, $filename);

                $user->image = $filename;
            }

   }
            $user->name                    = $request->input('first_name');
            $user->phone_no                = $request->input('phone_no');
            $user->address                 = $request->input('address');
            $user->city                    = $request->input('city');
            $user->state                   = $request->input('state');
            $user->zip_code                = $request->input('zip_code');
            $user->save();

  $data = '
<contact id="'.$user->ontraport_id.'">
    <Group_Tag name="Contact Information">
      <field name="First Name"> '.$request->input('name').'</field>
        <field name="Last Name"> </field>
        <field name="Office Phone"> '.$request->input('phone_no').'</field>
        <field name="Email">'.$request->input('email').'</field>
    </Group_Tag>
</contact>
<contact id="98765">
    <Group_Tag name="Contact Information">
        <field name="Email">test@test.com</field>
    </Group_Tag>
</contact>';
$data = urlencode(urlencode($data));
// Replace the strings with your API credentials located in Admin > OfficeAutoPilot API Instructions and Key Manager
$appid = "2_106720_4XRgsX85H ";
$key = "gALmxmhEFRmnqoe";
$reqType= "update";
$postargs = "appid=".$appid."&key=".$key."&return_id=1&reqType=".$reqType."&data=".$data;
$request = "http://api.ontraport.com/cdata.php";
$session = curl_init($request);
curl_setopt ($session, CURLOPT_POST, true);
curl_setopt ($session, CURLOPT_POSTFIELDS, $postargs);
curl_setopt($session, CURLOPT_HEADER, false);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($session);
curl_close($session);

                // Session::flash('toastrHeading', Lang::get("Profile Edit"));
                // Session::flash('toastrMessage', Lang::get("Profile Successfully Edited"));
                // Session::flash('toastrType', 'success');
                // return [
                //     'status'    => 'success',
                //     'message'   => Lang::get("Profile Successfully Edited"),
                //     'action'    => 'reload'
                // ];
                $this->data['couponData'] = Coupon::select('coupon.id as id','coupon.review_title as review_title','coupon.rating as rating','coupon.review_description as review_description','coupon.coupon_code as coupon_code','product.product_image as product_image','product.item_name as name','product.id as product_id','coupon.coupon_recieved_date as coupon_recieved_date','coupon.review_submitted_due_date as review_submitted_due_date','coupon.amazon_review_url as amazon_review_url','coupon.onsite_review_url as onsite_review_url','product.amazon_product_url as amazon_product_url',
                    'coupon.amazon_review_url_status as amazon_review_url')->where('reviewer_id','=',Auth::user()->get()->id)
                ->where(function($query) {
                /** @var $query Illuminate\Database\Query\Builder  */
                return $query->where('review_description','=','')
                    ->orWhere('amazon_review_url_status', '=', 0);
            })
                 ->leftJoin('product','product.id','=','coupon.product_id')->groupBy('product_id')->get();
                   $this->data = $this->mainFunction();

                return view ('user.front',$this->data); 
    
       }

        public function userPassword()
       {
        $this->data = $this->mainFunction();

        return view('user.changepassword',$this->data);
       }

       public function passwordChange(Request $request)
       {

        $validator = Validator::make($request->all(),User::rules('change'));
        if ($validator->fails()) {
            return [
                'status'    => 'errors',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
		
             $password = Auth::user()->get()->password;
            $oldpassword =$request->input('old_password');
            $newpassword =$request->input('new_password');
            if(Hash::check($oldpassword,$password)) {
                $id = Auth::user()->get()->id;
           
                $user = User::find($id);
                $user->password = bcrypt($newpassword);
                $user->save();

                Session::flash('toastrHeading', Lang::get("messages.passwordChanged"));
                Session::flash('toastrMessage', Lang::get("messages.passwordSuccessChange"));
                Session::flash('toastrType', 'success');
                return [
                    'status'    => 'success',
                    'message'   => Lang::get("messages.passwordSuccessChange"),
                    'action'    => 'reload'
                ];
            }else{
                return[
                    'status' => 'error',
                    'message' => 'Old Password Not correct'
                ];
            }
        
        }
            
       }

       public function searchProduct(Request $request)
       {
        $selectValue = $request->input('selectValue');
        $product_name = $request->input('product_name');
        $recived_date_from = $request->input('recived_date_from');
        $recived_date_to = $request->input('recived_date_to');
        $due_date_from = $request->input('due_date_from');
        $due_date_to = $request->input('due_date_to');




        if($selectValue == 'all')
        {
                $this->data['coupon'] = Coupon::select('coupon.id as reviewId','coupon.*','product.*')->where('reviewer_id','=',Auth::user()->get()->id)->history()->paginate(2);

           
        }
        elseif($due_date_from != null)
        {
       
       $this->data['coupon'] = Coupon::select('coupon.id as reviewId','coupon.*','product.*')->where('reviewer_id','=',Auth::user()->get()->id)->history()->whereDate('review_submitted_date','>',$due_date_from)->whereDate('review_submitted_date','<',$due_date_to)->paginate(3);
        }
         elseif($recived_date_to != null)
        {

            
       $this->data['coupon'] = Coupon::select('coupon.id as reviewId','coupon.*','product.*')->where('reviewer_id','=',Auth::user()->get()->id)->history()->whereDate('coupon_recieved_date','>=',$recived_date_from)->whereDate('coupon_recieved_date','<=',$recived_date_to)->paginate(3);
        }

         
        elseif($selectValue == 'due_date')
        {
            $this->data['coupon'] = Coupon::select('coupon.id as reviewId','coupon.*','product.*')->where('reviewer_id','=',Auth::user()->get()->id)->history()->where('review_submitted_date','>','review_submitted_due_date')->paginate(2);
        }
        elseif($product_name != '')
        {

             $this->data['coupon'] = Coupon::select('coupon.id as reviewId','coupon.*','product.*')->where('reviewer_id','=',Auth::user()->get()->id)->history()->where('product.item_name','=',$product_name)->orderBy('coupon_recieved_date')->paginate(2);
        }
        else
        {
             $this->data['coupon'] = Coupon::select('coupon.id as reviewId','coupon.*','product.*')->where('reviewer_id','=',Auth::user()->get()->id)->history()->orderBy('coupon_recieved_date')->paginate(2);
        }

        $search_product = $request->input('product_name');

        

        $view = view('user.searchproduct',$this->data);
        $contents = $view->render();


        return $contents;

       }

       public function userReview($id)
       {
         $this->data = $this->mainFunction();
       $this->data['data'] = Coupon::leftJoin('product','product.id','=','coupon.product_id')-> where('coupon.id','=',$id)->first();
         return view('user.userreview',$this->data);
       }



}
