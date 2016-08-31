<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\AdminBaseController;
use Bllim\Datatables\Datatables;
use App\User;
use  App\Company;
use  App\Product;
use App\Coupon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use DB;
use App\Note;

use Illuminate\Http\Request;

class AdminUserController extends AdminBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

        $this->data['pageTitle']        = 'Reviewers';
        $this->data['dashboard']        = '';
        $this->data['user']         = 'active';
        $this->data['Settings']        = '';
        $this->data['allTimeSeller']  = Company::all()->count();
        $this->data['lastweekSeller'] = Company::where('created_at','<=', \Carbon\Carbon::now())->where('created_at','>=', \Carbon\Carbon::now()->subDays(7))->count();
        $this->data['lastmonthSeller'] = Company::where('created_at','<=', \Carbon\Carbon::now())->where('created_at','>=', \Carbon\Carbon::now()->subDays(30))->count();
        
         $this->data['lasthourProduct'] = Product::where('created_at','<=', \Carbon\Carbon::now())->where('created_at','>=', \Carbon\Carbon::now()->subHours(24))->count();

        $this->data['lastweekProduct'] = Product::where('created_at','<=', \Carbon\Carbon::now())->where('created_at','>=', \Carbon\Carbon::now()->subDays(7))->count();
       
        $this->data['lastmonthProduct'] = Product::where('created_at','<=', \Carbon\Carbon::now())->where('created_at','>=', \Carbon\Carbon::now()->subDays(30))->count();



        return view('admin.user.viewuser', $this->data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $serviceEngineers = User::select('users.id',
            'users.name',
            'users.url',
            'users.email',
            'users.created_at',
            'users.status',
            'users.amazon_url_status'
        );

        return Datatables::of($serviceEngineers)
        ->edit_column('url', function($row){
            if($row->amazon_url_status == 1)
            {
                 return $row->url." ".'<a  class="btn btn-sm bg-green margin-bottom-5" onclick="urlStatus('.$row->id.', \''. addslashes($row->url) .'\')">
                        <span class="fa fa-"></span> Verified </a>';
            }
            else
            {
                return  $row->url." ".'<a  class="btn btn-sm bg-red margin-bottom-5" onclick="urlStatus('.$row->id.', \''. addslashes($row->url) .'\')">
                        <span class="fa fa-"></span> Not Verified </a>';
            }
             })
            ->edit_column('created_at', function($row){
                return $row->created_at->format('d-m-Y');
            })
             ->edit_column('status', function($row){
                if($row->status == 'good' )
                {
                return '<a class="btn btn-sm bg-green margin-bottom-5">
                        <span class="fa fa-smile-o"></span>'." Good ".'</a>';    
                }
                
               
                else if($row->status == 'suspended')
                {
                    return '<a class="btn btn-sm bg-yellow margin-bottom-5">
                        <span class="fa fa-meh-o"></span>'." Suspended ".'</a>';    
                }
                else
                {
                   return '<a class="btn btn-sm bg-red margin-bottom-5">
                        <span class="fa fa-meh-o"></span>'." Banned ".'</a>'; 
                }
                
            })
            ->add_column('Action', function ($row) {

                return '<a  onclick="changeStatus('.$row->id.', \''. addslashes($row->name) .'\')" class="btn btn-sm bg-blue-chambray margin-bottom-5">
                        <span class="fa fa-edit"></span> Status</a>
                        <a  href="'.route('admin.service.user.view', $row->id).'" class="btn btn-sm bg-blue-chambray margin-bottom-5">
                        <span class="fa fa-eye"></span> View </a>
                        <a  href="'.route('admin.service.user.edit', $row->id).'" class="btn btn-sm bg-blue-chambray margin-bottom-5">
                        <span class="fa fa-edit"></span> Edit</a>';
            })
            ->remove_column('amazon_url_status')
            ->make();
    }

    public function edit($id)
    {
         $this->data['pageTitle']        = 'Edit Reviewers';
         $this->data['Settings']        = '';
         $this->data['edituser']     = 'active';
         $this->data['user'] = User::find($id);
         return view('admin.user.edituser', $this->data);

    }

      public function editData(Request  $request)
    {
       $validator = Validator::make($request->all(),User::rules('edit',$request->input('id')));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
            $id = $request->input('id');
            Session::set('SessionId',$id);

            $product = User::find($id);
            if ($request->hasFile('image')) {

                $file = $request->file('image');
                $oldImage = $product->image;
                File::delete(public_path() . '/assets/admin/upload/' . $oldImage);

                $destination = public_path() . '/assets/admin/upload/';
                $extension = $file->getClientOriginalExtension();
                $filename = rand(1111,9999).'.'.$extension;
                $file->move($destination, $filename);

                $product->image = $filename;
            }
            if($request->input('password') != '')
            {
                  $product->password  = Hash::make($request->input('password'));
            }

            

                //$product->product_image = $filename;
        
            $product->name                    = $request->input('name');
            $product->email                   = $request->input('email');
            $product->url                     = $request->input('url');
            $product->phone_no                = $request->input('phone_no');
            $product->address                 = $request->input('address');
            $product->city                    = $request->input('city');
            $product->state                   = $request->input('state');
            $product->zip_code                = $request->input('zip_code');
             $product->ip_log           = $request->ip();

            $product->save();

            $data = '
<contact id="'.$product->ontraport_id.'">
    <Group_Tag name="Contact Information">
      <field name="First Name"> '.$request->input('name').'</field>
        <field name="Last Name"> </field>
        <field name="Office Phone"> '.$request->input('phone_no').'</field>
        <field name="Email">'.$request->input('email').'</field>
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

  

            
          //  Setting flash session message
                Session::flash('toastrHeading', Lang::get("UserUpdated"));
                Session::flash('toastrMessage', Lang::get("User Updated Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'User Updated Successfully',
                'action'    => 'reload'
            ];
        }
    }

   

	
	
  

    //SHOW USER PROFILE PAGE
    public function view($id)
    {
        $this->data['pageTitle']        = 'View Reviewers Profile';
        $this->data['users']        = 'active';
        $this->data['viewUser']          ='active';
        $this->data['Settings']        = '';
        $this->data['user'] = User::find($id);
        $this->data['coupon'] = Coupon::where('reviewer_id','=',$id)->get();
        $this->data['couponTotal'] = Coupon::where('reviewer_id','=',$id)->count();
        $this->data['reviewTotal'] = Coupon::where('reviewer_id','=',$id)->where('review_description','!=',' ')->count();
        $this->data['reviewLeft'] = Coupon::where('reviewer_id','=',$id)->where('review_description','=',' ')->count();
        $this->data['reviewLate'] = Coupon::where('reviewer_id','=',$id)->where('review_description','!=',' ')->where('review_submitted_date','<','review_submitted_due_date')->count();
        $this->data['couponProduct'] = Coupon::select('coupon.coupon_recieved_date','product.id as id ','product.item_name as name')
        ->leftjoin('product','product.id','=','coupon.product_id')
        ->where('coupon.reviewer_id','=',$id)->get();


 $this->data['note'] = Note::select('notes.id','notes.created_at','notes.reviewer_id','notes.note','users.name')->leftjoin('users','users.id','=','notes.reviewer_id')->where('reviewer_id','=',$id)->get();
        $this->data['totalRetailValue'] = Coupon::select(DB::raw('SUM(product.product_retail_value ) As value'))->where('reviewer_id','=',$id)->
        leftJoin('product','product.id','=','coupon.product_id')->first();

       

        //$this->data['product'] = Product::where('company_id','=',$id)->get();
        return view('admin.user.userdetail',$this->data);
    }

    //CHANGE STATUS
    public function amazonUrl($id)
    {
        $userData = User::where('id','=',$id)->first();
        $userData->amazon_url_status = 1;
        $userData->save();

        Session::flash('toastrHeading', Lang::get("AmazonUrlStatus"));
        Session::flash('toastrMessage', Lang::get("Amazon Url Status Update"));
        Session::flash('toastrType', 'success');
        return[
            "status"      => "success",
            "message"     => "Amazon Url Status Successfully Changed",
            "action"      => "reload"
        ];

    }

     public function add()
    {
        $this->data['pageTitle']        = 'Add Reviewers';
        $this->data['user']  = 'active';
        $this->data['addUser']          ='active';
        $this->data['Settings']        = '';
        return view('admin.user.adduser', $this->data);
        # code...
    }

    public function addData(Request $request)
    {
         $validator = Validator::make($request->all(),User::rules('add'));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
            $product = new User();
            if ($request->hasFile('image')) {

                $file = $request->file('image');
                $oldImage = $product->product_image;
                File::delete(public_path() . '/assets/admin/upload/' . $oldImage);

                $destination = public_path() . '/assets/layout/img/';
                $extension = $file->getClientOriginalExtension();
                $filename = rand(1111,9999).'.'.$extension;
                $file->move($destination, $filename);

                $product->image = $filename;
            }

            
            $product->name                  = $request->input('name');
            $product->email                   = $request->input('email');
            $product->password                    = Hash::make($request->input('password'));
            $product->url                 = $request->input('url');
            $product->phone_no                 = $request->input('phone_no');
            $product->address        = $request->input('address');
            $product->city             = $request->input('city');
            $product->state                     = $request->input('state');
            $product->zip_code                  = $request->input('zip_code');
             $product->ip_log           = $request->ip();
             $product->status           = 'good';


            

            //ADD DATA IN ONTRAPORT API

            // Construct contact data in XML format
//$data = '
//<contact>
//    <Group_Tag name="Contact Information">
//        <field name="First Name"> '.$request->input('name').'</field>
//        <field name="Last Name"> </field>
//        <field name="Office Phone"> '.$request->input('phone_no').'</field>
//        <field name="Email">'.$request->input('email').'</field>
//        <field name="status">good</field>
//        <field name="Number Of Promotion Granted">0</field>
//        <field name="Total no of reviews left">0</field>
//        <field name="Number of Review Panding">0</field>
//        <field name="Late Review">0</field>
//    </Group_Tag>
//    <Group_Tag name="Sequences and Tags">
//        <field name="Contact Tags">Test</field>
//        <field name="Sequences">*/*3*/*8*/*</field>
//    </Group_Tag>
//</contact>
//';
//$data = urlencode(urlencode($data));
//// Replace the strings with your API credentials located in Admin > OfficeAutoPilot API Instructions and Key Manager
//$appid = "2_106720_4XRgsX85H ";
//$key = "gALmxmhEFRmnqoe";
////Set your request type and construct the POST request
//$reqType= "add";
//$postargs = "appid=".$appid."&key=".$key."&return_id=1&reqType=".$reqType."&data=".$data;
//$request = "http://api.ontraport.com/cdata.php";
////Start the curl session and send the data
//$session = curl_init($request);
//curl_setopt ($session, CURLOPT_POST, true);
//curl_setopt ($session, CURLOPT_POSTFIELDS, $postargs);
//curl_setopt($session, CURLOPT_HEADER, false);
//curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
////Store the response from the API for confirmation or to process return data
//$response = curl_exec($session);
////Close the session
//curl_close($session);
//$xml=simplexml_load_string($response) or die("Error: Cannot create object");
//$id = (int)$xml->contact['id'];
//
//$product->ontraport_id = $id;
$product->save();

                //$product->product_image = $filename;
        

             
           

            //Setting flash session message
                Session::flash('toastrHeading', Lang::get("UserAdded"));
                Session::flash('toastrMessage', Lang::get("User Added Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'User Added Successfully',
                'action'    => 'reload'
            ];
        }
    }

    public function status(Request $request)
    {
            $id      = $request->input('id');
            $status  = $request->input('status');
            $user    = User::find($id);
            $user->status = $status;
            $user->save();

             $data = '
<contact id="'.$user->ontraport_id.'">
    <Group_Tag name="Contact Information">
      <field name="status"> '.$status.'</field>
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




        Session::flash('toastrHeading', Lang::get("UserStatus"));
        Session::flash('toastrMessage', Lang::get("User Status Update"));
        Session::flash('toastrType', 'success');
        return[
            "status"      => "success",
            "message"     => "User Status Successfully Changed",
            "action"      => "reload"
        ];

    }
    
 public function addNotes(Request $request)
    {
            $reviewer_id = $request->input('id');
            $notes = $request->input('note');
            $noteData = new Note();
            $noteData->reviewer_id = $reviewer_id;
            $noteData->note = $notes;
            $noteData->save();
             Session::flash('toastrHeading', Lang::get("NoteAdded"));
                Session::flash('toastrMessage', Lang::get("Note Added  Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'Note Added  Successfully',
                'action'    => 'reload'
            ];

    }

    //function for edit notes
    public function editNotes(Request $request)
    {
        $id = $request->input('id');
        $note = $request->input('note');
        $noteData = Note::find($id);
        $noteData->note = $note;
        $noteData->save();

                Session::flash('toastrHeading', Lang::get("NoteUpdated"));
                Session::flash('toastrMessage', Lang::get("Note Updated  Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'Note Updated  Successfully',
                'action'    => 'reload'
            ];

    }

    //function for chage status
    public function amazonreviewstatus(Request $request)
    {
        $couponId = $request->input('couponId');
        $data = coupon::find($couponId);
        $data->amazon_review_url_status = $request->input('amazon_url_status');
        $data->manual_verification_date = \Carbon\Carbon::now();
        $data->verified_by = $request->input('verifiedBy');
        $data->save();

          Session::flash('toastrHeading', Lang::get("ManuallyVerified"));
                Session::flash('toastrMessage', Lang::get("Manually Status Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'Manually Status Successfully',
                'action'    => 'reload'
            ];

    }
   
}
