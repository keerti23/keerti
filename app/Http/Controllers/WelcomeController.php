<?php namespace App\Http\Controllers;



use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;
use App\Setting;
use Illuminate\Support\Facades\File;
use App\User;
use App\Verification;
use App\Http\Controllers\Mail;
use Infusionsoft;
use URL;
use App\Category;
use App\Coupon;
use App\Product;
use App\Contact;
use App\Admin;
use App\Page;
use App\Language;
use App\Faq;
use App\Testimonial;
use DB;
use App\BlogCategory;
use App\Blog;


use Illuminate\Http\Request;
class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */

   public function __construct()
    {
       
         $this->data['pages']   = Page::limit(3)->get();
               $this->data['contact'] = Page::select('description')->where('title','=','contact')->first(); 
                return $this->data;
       
    }

	public function index()
	{

 // //Your authentication key
 //        $authKey ="120755AZl8lF59HpVU579b4a13";

 //        //Multiple mobiles numbers separated by comma
 //        $mobileNumber ='9649204274';

 //        //Sender ID,While using route4 sender id should be 6 characters long.
 //        //$senderId = "555000"; //for promotional
 //        $senderId = "KIRTII";   //for transactional

 //        //Your message to send, Add URL encoding here.
 //        $optCode = rand(1000,9999);
 //        $message = $optCode." is your confirmation code";

 //        //Define route
 //        //$route = "1"; //for promotional
 //        $route = "4"; //for transactional

 //        //Prepare you post parameters
 //        $postData = array(
 //            'authkey' => $authKey,
 //            'mobiles' => $mobileNumber,
 //            'message' => $message,
 //            'sender' => $senderId,
 //            'route' => $route
 //        );

 //        //API URL
 //        $url="https://control.msg91.com/sendhttp.php";

 //        // init the resource
 //        $ch = curl_init();
 //        curl_setopt_array($ch, array(
 //            CURLOPT_URL => $url,
 //            CURLOPT_RETURNTRANSFER => true,
 //            CURLOPT_POST => true,
 //            CURLOPT_POSTFIELDS => $postData
 //            //,CURLOPT_FOLLOWLOCATION => true
 //        ));


 //        //Ignore SSL certificate verification
 //        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
 //        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


 //        //get response
 //        $output = curl_exec($ch);

 //        //Print error if any
 //        if(curl_errno($ch))
 //        {
 //            return 'error:' . curl_error($ch);
 //        }

 //        curl_close($ch);

//     $data =URL::to('product/confirmation/product/MTU=') ;
//     \Mail::raw($data, function ($message) {
//       $message->from('kirti@blivesolutions.com', 'Blive Solution');
//                   $message->to('kirti@blivesolutions.com');
  
// });
// return "mail";
	
    $this->data['category'] = Category::all();

    $this->data['review'] = Coupon::select('coupon.*','product.*','users.*')->leftJoin('product','product.id','=','coupon.product_id')
                            ->leftJoin('users','users.id','=','coupon.reviewer_id')
                            ->where('coupon.amazon_review_url_status','=',1)
                            ->where('coupon.onsite_review_url','!=',' ')
                            ->where('coupon.onsite_review_url','!=' ,'null')
                              ->where('coupon.rating','!=',0)
                            -> orderBy('review_submitted_date','desc')->take(3)->get();
    $this->data['frontData'] = Page::select('description','title','image')->where('title','=','Why Would Companies Give Away Free Product?')->first(); 

    $this->data['productData'] = Product::orderBy('created_at','desc')->limit(5)->get();

		return view('layouts.frontlayout',$this->data);
	}

	//USER REGISTRATION FORM
	public function registration()

	{
       $this->data['term_of_service'] = Language::where('name','=','term_of_service')->first(); 
       $this->data['signup_subtitle_info'] = Language::where('name','=','signup_subtitle_info')->first();
       $this->data['registeration_help_content'] = Language::where('name','=','registeration_help_content')->first();
		   return view('layouts.userregistration',$this->data);
 	}

public function verification(Request $request)
{

  $validator = Validator::make($request->all(),Verification::rules('Add'));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
          $phone_no = $request->input('phone_no');
          $data= Verification::where('phone_no','=',$phone_no)->first();
          if($data == '')
          {


 //Your authentication key
        $authKey ="120755AZl8lF59HpVU579b4a13";

        //Multiple mobiles numbers separated by comma
        $mobileNumber =$phone_no;

        //Sender ID,While using route4 sender id should be 6 characters long.
        //$senderId = "555000"; //for promotional
        $senderId = "KIRTII";   //for transactional

        //Your message to send, Add URL encoding here.
        $optCode = rand(1000,9999);
        $message = $optCode." is your confirmation code";

        //Define route
        //$route = "1"; //for promotional
        $route = "4"; //for transactional

        //Prepare you post parameters
        $postData = array(
            'authkey' => $authKey,
            'mobiles' => $mobileNumber,
            'message' => $message,
            'sender' => $senderId,
            'route' => $route
        );

        //API URL
        $url="https://control.msg91.com/sendhttp.php";

        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
            //,CURLOPT_FOLLOWLOCATION => true
        ));


        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


        //get response
        $output = curl_exec($ch);

        //Print error if any
        if(curl_errno($ch))
        {
            return 'error:' . curl_error($ch);
        }

        curl_close($ch);

       $verification = new Verification();
       $verification->phone_no = $phone_no;
       $verification->opt_code = $optCode;
       $verification->save();
       return [
                'status'    => 'success',
              ];

  }
else
{
            return [
                'status'    => 'failMobile',
              ];


}

}
}

public function optCode(Request $request)
{
  $phone_no = $request->input('phone_no');
  $opt_code = $request->input('opt_code');
  $data = Verification::where('phone_no','=',$phone_no)->where('opt_code','=',$opt_code)->first();
  if($data == '')
  {
    return ['status' =>'fail','message'=>'your opt code is not valid'];
  }
  else
  {
    return ['status' =>'success','message'=>'your opt code is valid'];
  }
}

public function add(Request $request)
{
   
	//Your authentication key
 
   $this->data['success_msg'] = Language::where('name','=','success_msg')->first(); 
	 $validator = Validator::make($request->all(),User::rules('add'));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
            $user = new User();
            if ($request->hasFile('image')) {

                $file = $request->file('image');
                $oldImage = $user->image;
                File::delete(public_path() . '/assets/admin/upload/user/' . $oldImage);

                $destination = public_path() . '/assets/admin/upload/user/';
                $extension = $file->getClientOriginalExtension();
                $filename = rand(1111,9999).'.'.$extension;
                $file->move($destination, $filename);

                $user->image= $filename;
            }

              $user->name         = $request->input('name').' '.$request->input('last_name');
            $user->email        = $request->input('email');
            $user->password     = Hash::make($request->input('password'));
            $user->phone_no     = $request->input('phone_no');
            $user->url          = $request->input('url');
            $user->address     = $request->input('address');
            $user->confirmed_token  = rand(1000,999999);
            $user->city             = $request->input('city');
            $user->state            = $request->input('state');
            $user->zip_code         = $request->input('zip_code');
            $user->ip_log           = $request->ip();
            $user->status      = 'inactive';
            $user->amazon_url_status = 0;

           
            $data['confirmed_token']  = $user->confirmed_token;
            $email = $request->input('email');


               \Mail::send('emails.password', $data,function ($message)  use ($email)
              {
                  $message->from('rankandreview@gmail.com', 'Rank and Review');
                  $message->to($email);

              });

          $adminMail = Admin::select('email')->first();
          $admin_mail = $adminMail->email;

              $data ="Some new user register your rank and review project";
              \Mail::raw($data, function ($message)  use ($admin_mail) 
              {
                $message->from('kirti@blivesolutions.com', 'Blive Solution');
                            $message->to($admin_mail);
            
              });


            $data = '
<contact>
    <Group_Tag name="Contact Information">
        <field name="First Name"> '.$request->input('name').'</field>
        <field name="Last Name">'.$request->input('last_name').' </field>
        <field name="Office Phone"> '.$request->input('phone_no').'</field>
        <field name="Email">'.$request->input('email').'</field>
         <field name="status">good</field>
        <field name="Number Of Promotion Granted">0</field>
        <field name="Total no of reviews left">0</field>
        <field name="Number of Review Panding">0</field>
        <field name="Late Review">0</field>
    </Group_Tag>
    <Group_Tag name="Sequences and Tags">
        <field name="Contact Tags">Test</field>
        <field name="Sequences">*/*3*/*8*/*</field>
    </Group_Tag>
</contact>
';
$data = urlencode(urlencode($data));
// Replace the strings with your API credentials located in Admin > OfficeAutoPilot API Instructions and Key Manager
$appid = "2_106720_4XRgsX85H ";
$key = "gALmxmhEFRmnqoe";
//Set your request type and construct the POST request
$reqType= "add";
$postargs = "appid=".$appid."&key=".$key."&return_id=1&reqType=".$reqType."&data=".$data;
$request = "http://api.ontraport.com/cdata.php";
//Start the curl session and send the data
$session = curl_init($request);
curl_setopt ($session, CURLOPT_POST, true);
curl_setopt ($session, CURLOPT_POSTFIELDS, $postargs);
curl_setopt($session, CURLOPT_HEADER, false);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
//Store the response from the API for confirmation or to process return data
$response = curl_exec($session);
//Close the session
curl_close($session);
$xml=simplexml_load_string($response) or die("Error: Cannot create object");
$id = (int)$xml->contact['id'];

$user->ontraport_id = $id;
 $user->save();
          
  //           //Setting flash session message
                Session::flash('toastrHeading', Lang::get("UserAdded"));
                Session::flash('toastrMessage', Lang::get("User Successfully Registered"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => $success_msg->value,
                //'action'    => 'reload'
            ];
        }
}


public function confirmation($code)
{
  $user = User::where('confirmed_token' ,'=',$code)->first();
  $user->status = 'good';
  $user->save();
    $this->data['category'] = Category::all();

    $this->data['review'] = Coupon::select('coupon.*','product.*','users.*')->leftJoin('product','product.id','=','coupon.product_id')
                            ->leftJoin('users','users.id','=','coupon.reviewer_id')
                            ->where('coupon.amazon_review_url_status','=',1)
                            ->where('coupon.onsite_review_url','!=',' ')
                            ->where('coupon.onsite_review_url','!=' ,'null')
                            ->where('coupon.rating','!=',0)
                            -> orderBy('review_submitted_date','desc')->take(3)->get();
    $this->data['frontData'] = Page::select('description','title','image')->where('title','=','Why Would Companies Give Away Free Product?')->first(); 

    $this->data['productData'] = Product::orderBy('created_at','desc')->limit(5)->get();

  $this->data['pageTitle'] = "home";
  $this->data['Settings'] = "home";
    return view('layouts.frontlayout',$this->data);


}

//confirmation for product
public function productconfirmation($id)
{
    $this->data['category'] = Category::all();

    $this->data['review'] = Coupon::select('coupon.*','product.*','users.*')->leftJoin('product','product.id','=','coupon.product_id')
                            ->leftJoin('users','users.id','=','coupon.reviewer_id')
                            -> orderBy('review_submitted_date','desc')->take(3)->get();
 $this->data['frontData'] = Page::select('description','title','image')->where('title','=','Why Would Companies Give Away Free Product?')->first(); 

    $this->data['productData'] = Product::orderBy('created_at','desc')->limit(5)->get();


  Session::set('product_id',base64_decode($id));
  return view('layouts.frontlayout',$this->data);
}

 public function forget()
 {
          return view('layouts.forgetpassword',$this->data);
 }

     public function tokenSend(Request $request)
    {
      
            

            $resetpassword = User::where('email','=',$request->input('email'))->first();
            if($resetpassword == '')
            {
                 return [
                'status'    => 'fail',
                'message'   => 'Enter Your valid email id',
                //'action'    => 'reload'
                ];

             }

             else
             {
             
            $data['confirmed_token']  = rand(1000,999999);
            $resetpassword->password_token = $data['confirmed_token'] ;
            $resetpassword->password_status = 0;
            $resetpassword->save();
            $email = $request->input('email');
            


               \Mail::send('emails.resetpassword', $data,function ($message)  use ($email)
              {
                  $message->from('rankandreview@gmail.com', 'Rank and Review');
                  $message->to($email);

              });
              
                return [
                'status'    => 'success',
                'message'   => 'You will receive an email with instructions about how to confirm your account in a few minutes.',
                //'action'    => 'reload'
                ];
             }
            
            
          
        
       
    }

     public function passwordReset($token)
    {


        if($token != '')
        {

            $data = User::where('password_token','=',$token)->first();
          
            $data->password_status = 1;
            $data->save();
            $this->data['email'] = $data->email;
            $this->data['message'] = 'enter your old and new password';
        }
        else
        {
            $this->data['message'] = 'click on link';

        }
         return view('layouts.passwordreset',$this->data);

        

    }

    public function passwordSet(Request $request)
    {
      $email = $request->input('email');
      $data = User::where('email','=',$email)->first();

      $validator = Validator::make($request->all(),User::rules('change'));
        if ($validator->fails()) {
            return [
                'status'    => 'errors',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
            $password = $data->password;

            $oldpassword =$request->input('old_password');
            $newpassword =$request->input('new_password');
            if(  Hash::check($oldpassword,$password)) {
              
           
                $user = User::where('email','=',$email)->first();
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
	
	public function productSearch(Request $request)
	{
		$id = $request->input('category_id');
		$product_name = $request->input('product_name');
		$this->data['productData'] = Product::select('product.*',DB::raw('AVG(coupon.rating) as rating'),DB::raw('COUNT(   NULLIF(review_title," ")) as review'))->leftjoin('coupon','coupon.product_id','=','product.id')->where('product.category','=',$id)->where('product.item_name','=',$product_name)->groupBy('coupon.product_id')->paginate(30);
		 $view = view('layouts.searchproduct',$this->data);
        $contents = $view->render();


        return $contents;

	}

    public function productShow($id)
    {
      $this->data['category'] = Category::find($id);
      $this->data['productData'] = Product::select('product.*',DB::raw('AVG(coupon.rating) as rating'),DB::raw('COUNT(   NULLIF(review_title," ")) as review'))->leftjoin('coupon','coupon.product_id','=','product.id')->where('product.category','=',$id)->groupBy('coupon.product_id')->paginate(30);
      return view('layouts.product',$this->data);
    }

    public function productDetailShow($id)
    {

      $this->data['review'] = Coupon::select('coupon.*','product.*','users.*')->leftJoin('product','product.id','=','coupon.product_id')
                            ->leftJoin('users','users.id','=','coupon.reviewer_id')
                            -> orderBy('review_submitted_date','desc')->where('coupon.product_id','=',$id)->
                            where('coupon.review_title','!=',' ')->get();
                            
      $this->data['reviewCount'] = Coupon::select('coupon.*','product.*','users.*')->leftJoin('product','product.id','=','coupon.product_id')
                            ->leftJoin('users','users.id','=','coupon.reviewer_id')
                            -> orderBy('review_submitted_date','desc')->where('coupon.product_id','=',$id)-> where('coupon.review_title','!=',' ')->count();

      $this->data['productData'] = Product::select('product.*','product.id as id','category.*','company.*','company.name as company_name','category.name as category_name',DB::raw('AVG(coupon.rating) as rating'))->leftJoin('category','category.id','=','product.category')->leftJoin('coupon','coupon.product_id','=','product.id')->
      leftJoin('company','company.id','=','product.company_id')
      ->where('product.id','=',$id)->first();
      return view('layouts.productdetail',$this->data);
    }

    //FUNCTION FOR CONTACT US PAGE
    public function contact()
    {
      return view('layouts.contactus',$this->data);
    }

    //ADD DATA IN CONTACT US TABLE

    public function contactadd(Request $request)
    {
       $name     = $request->input('name'); 
       $email    = $request->input('email');
       $message  = $request->input('message');
       $phone_no = $request->input('phone_no');
       $subject  = $request->input('subject');

      $validator = Validator::make($request->all(),Contact::rules('Add'));
        if ($validator->fails()) {
            return [
                'status'    => 'errors',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
          $contact = New Contact();
          $contact->name = $name;
          $contact->email = $email;
          $contact->message = $message;
          $contact->phone_no = $phone_no;
          $contact->subject = $subject;
          $contact->save();

          $adminMail = Admin::select('email')->first();
          $admin_mail = $adminMail->email;

              $data ="Here is some new query for you By user name :".$name ;
              \Mail::raw($data, function ($message)  use ($admin_mail) 
              {
                $message->from('kirti@blivesolutions.com', 'Blive Solution');
                            $message->to($admin_mail);
            
              });

              
            
        
        }
                Session::flash('toastrHeading', Lang::get("ContactQuery"));
                Session::flash('toastrMessage', Lang::get("Contact Us Form filled Successfully"));
                Session::flash('toastrType', 'success');
                return [
                    'status'    => 'success',
                    'message'   => Lang::get("Contact Us Form filled Successfully"),
                    'action'    => 'reload'
                ];
    }

    //FUNCTION FOR FAQ PAGE
    public function faq()
    {
      $this->data['faq'] = Faq::paginate(15);
      return view('layouts.faq',$this->data);
    }

    //FUNCTION FOR AMAZONSELLER
    public function amazonseller()
    {
      $this->data['testimonial'] = Testimonial::orderBy('created_at','desc')->limit(5)->get();
      return view('layouts.amazonseller',$this->data);
    }

      //FUNCTION FOR ABOUT PAGE
    public function about()
    {
      $this->data['about'] = Page::where('url','=','about')->first();
      return view('layouts.aboutus',$this->data);
    }
  //FUNCTION FOR PRIVACY PAGE
    public function privacy_policy()
    {
      $this->data['privacy_policy'] = Page::where('url','=','privacy_policy')->first();
      return view('layouts.privacypolicy',$this->data);
    }
      //FUNCTION FOR ABOUT PAGE
    public function term_condition()
    {
      $this->data['term_condition'] = Page::where('url','=','term_condition')->first();
      return view('layouts.termcondition',$this->data);
    }
    public function blog()
    {
      $this->data['category'] = BlogCategory::all();
      $this->data['blog'] = Blog::orderBy('created_at','desc')->limit(2)->get();
      return view('layouts.blog',$this->data);
    }
    public function blogDetail($id)
    {
      $this->data['blogdetail'] = Blog::where('blog_category_id','=',$id)->paginate(5);
      return view('layouts.blogdetail',$this->data);
    }
}
