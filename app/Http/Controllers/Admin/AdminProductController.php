<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\AdminBaseController;
use Bllim\Datatables\Datatables;
use App\Company;
use App\Product;
use App\Coupon;
use App\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;
use App\Setting;
use App\Note;
use Infusionsoft\Infusionsoft;
use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;
use Crypt;

class AdminProductController extends AdminBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

        $this->data['pageTitle']        = 'Product';
        $this->data['dashboard']        = '';
        $this->data['product']  = 'active';
        $this->data['Settings']        = '';
         $this->data['allTimeSeller']  = Company::all()->count();
        $this->data['lastweekSeller'] = Company::where('created_at','<=', \Carbon\Carbon::now())->where('created_at','>=', \Carbon\Carbon::now()->subDays(7))->count();
        $this->data['lastmonthSeller'] = Company::where('created_at','<=', \Carbon\Carbon::now())->where('created_at','>=', \Carbon\Carbon::now()->subDays(30))->count();
        
         $this->data['lasthourProduct'] = Product::where('created_at','<=', \Carbon\Carbon::now())->where('created_at','>=', \Carbon\Carbon::now()->subHours(24))->count();

        $this->data['lastweekProduct'] = Product::where('created_at','<=', \Carbon\Carbon::now())->where('created_at','>=', \Carbon\Carbon::now()->subDays(7))->count();
       
        $this->data['lastmonthProduct'] = Product::where('created_at','<=', \Carbon\Carbon::now())->where('created_at','>=', \Carbon\Carbon::now()->subDays(30))->count();
        return view('admin.product.viewproduct', $this->data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $serviceEngineers = Product::select('product.id',
            'product.item_name',
            'company.contact_name',
            'company.email',
            'product.product_upc','product.product_status as status'
        )->leftjoin('company','company.id','=','product.company_id');

        return Datatables::of($serviceEngineers)
        ->edit_column('status',function($row)
        {
        return $row->status.'<a class="btn btn-sm bg-green margin-bottom-5" onclick="urlStatus('.$row->id.', \''. addslashes($row->item_name) .'\')"> Status </a>';
        })
        
        // return Crypt::decrypt($id);
            ->add_column('Action', function ($row) {

                return '<a class="btn btn-sm bg-green margin-bottom-5" onclick="sendPromotion(\''.base64_encode($row->id).'\', \''. addslashes($row->item_name) .'\')"><span class="fa fa-send"></span> Send </a>

                        <a  href="'.route('admin.service.product.view', $row->id).'" class="btn btn-sm bg-blue-chambray margin-bottom-5">
                        <span class="fa fa-eye"></span> View </a>
                        <a  href="'.route('admin.service.product.edit', $row->id).'" class="btn btn-sm bg-blue-chambray margin-bottom-5">
                        <span class="fa fa-edit"></span> Edit</a>
                         <a class="btn btn-sm btn-danger margin-bottom-5" onclick="deleteUser('.$row->id.', \''. addslashes($row->name) .'\')">
                        <span class="fa fa-trash"></span> Delete</a>';
            })
            ->make();
    }

 //SHOW PRODUCT PROFILE PAGE
    public function view($id)
    {
        $this->data['pageTitle']        = 'View Product Profile';
        $this->data['ViewProduct']          ='active';
        $this->data['productDetail']    = 'active';
        $this->data['Settings']        = '';
        $this->data['product'] = Product::find($id);
        $this->data['company'] = Product::select('company.name','company.email','company.contact_name','company.phone_no')->leftjoin('company','company.id','=','product.company_id')->where('product.id','=',$id)->first();
        $this->data['coupon'] =  Coupon::where('product_id','=',$id)->get();
        $this->data['note'] = Note::select('notes.id','notes.created_at','notes.reviewer_id','notes.note','users.name')->leftjoin('users','users.id','=','notes.reviewer_id')->where('product_id','=',$id)->get();

        return view('admin.product.productdetail',$this->data);
    }
    

    
	public function edit($id)
    {
         $this->data['pageTitle']        = 'Edit Product';
         $this->data['Settings']        = '';
         $this->data['editproduct']     = 'active';
         $this->data['product'] = Product::find($id);
         $this->data['company']         = Company::select('id','name')->get();
         $this->data['category']         = Category::select('id','name')->get();
         return view('admin.product.editproduct', $this->data);

    }
    public function editData(Request  $request)
    {
      
       $validator = Validator::make($request->all(),Product::rules('Add'));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
            $id = $request->input('id');
            Session::set('SessionId',$id);

            $product = Product::find($id);
            if ($request->hasFile('product_image')) {

                $file = $request->file('product_image');
                $oldImage = $product->product_image;
                File::delete(public_path() . '/assets/admin/layout/img/' . $oldImage);

                $destination = public_path() . '/assets/admin/layout/img/';
                $extension = $file->getClientOriginalExtension();
                $filename = rand(1111,9999).'.'.$extension;
                $file->move($destination, $filename);

                $product->product_image = $filename;
            }

            

                //$product->product_image = $filename;
        
            $product->company_id                  = $request->input('company_name');
            $product->item_name                   = $request->input('item_name');
            $product->category                    = $request->input('category');
            $product->amazon_asin                 = $request->input('amazon_asin');
            $product->product_upc                 = $request->input('product_upc');
            $product->product_retail_value        = $request->input('product_retail_value');
            $product->discount_amount             = $request->input('discount_amount');
            $product->coupons                     = $request->input('coupons');
            $product->item_title                  = $request->input('item_title');
            $product->description                 = $request->input('description');
            $product->payment_status              = $request->input('payment_status');
            $product->payment_date                = $request->input('payment_date');
            $product->signed_contract             = $request->input('signed_contract');
            $product->review_due_days             = $request->input('review_due_days');
            $product->amazon_product_url          = $request->input('amazon_product_url');

            $product->save();

             if ($request->hasFile('coupon_code')) {

                 $productData = Product::select('id')->where('id','=',$request->input('id'))->first();
                 $id = $productData->id;

                $file = $request->file('coupon_code');
                
                $destination = public_path() . '/assets/admin/upload/coupon/';
                $extension = $file->getClientOriginalExtension();
                $filename = rand(1111,9999).'.'.$extension;
                $file->move($destination, $filename);
               

                  \Excel::load(public_path() . '/assets/admin/upload/coupon/'.$filename,function($reader) {
                    
                   $reader->ignoreEmpty();
                   $results = $reader->get()->toArray();
                   foreach($results as $key => $value){
                      $coupon = new Coupon();
                      $coupon->coupon_code = $value['coupon_code'];
                      $coupon->product_id = Session::get('SessionId');
                      $coupon->save();
                       //....
                        }
                    })->get();
                 }
          //  Setting flash session message
                Session::flash('toastrHeading', Lang::get("ProductUpdated"));
                Session::flash('toastrMessage', Lang::get("Product Updated Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'Product Added Successfully',
                'action'    => 'reload'
            ];
        }
    }



	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $product = Product::find($id);
        $product->destroy($id);
        Session::flash('toastrHeading', Lang::get("Product Deleted"));
        Session::flash('toastrMessage', Lang::get("Product Deleted Successfully"));
        Session::flash('toastrType', 'success');
        return[
            "status"      => "success",
            "message"     => "Product Deleted Successfully",
            "action"      => "reload"
        ];
	}

    public function add()
    {
        $this->data['pageTitle']        = 'Add Product';
        $this->data['product']  = 'active';
        $this->data['addProduct']       ='active';
        $this->data['Settings']        = '';
        $this->data['company']         = Company::select('id','name')->get();
        $this->data['category']         = Category::select('id','name')->get();
        return view('admin.product.addproduct', $this->data);
        # code...
    }

    public function addData(Request  $request)
    {

      

       $validator = Validator::make($request->all(),Product::rules('Add'));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
            $product = new Product();
            if ($request->hasFile('product_image')) {

                $file = $request->file('product_image');
                $oldImage = $product->product_image;
                File::delete(public_path() . '/assets/admin/layout/img/' . $oldImage);

                $destination = public_path() . '/assets/admin/layout/img/';
                $extension = $file->getClientOriginalExtension();
                $filename = rand(1111,9999).'.'.$extension;
                $file->move($destination, $filename);

                $product->product_image = $filename;
            }

            

                //$product->product_image = $filename;
        
            $product->company_id                  = $request->input('company_name');
            $product->item_name                   = $request->input('item_name');
            $product->category                    = $request->input('category');
            $product->amazon_asin                 = $request->input('amazon_asin');
            $product->product_upc                 = $request->input('product_upc');
            $product->product_retail_value        = $request->input('product_retail_value');
            $product->discount_amount             = $request->input('discount_amount');
            $product->coupons                     = $request->input('coupons');
            $product->item_title                  = $request->input('item_title');
            $product->description                 = $request->input('description');
            $product->payment_status              = $request->input('payment_status');
            $product->payment_date                = $request->input('payment_date');
            $product->signed_contract             = $request->input('signed_contract');
            $product->review_due_days             = $request->input('review_due_days');
            $product->amazon_product_url          = $request->input('amazon_product_url');
            $product->product_status              = 'Scheduled';

            $product->save();

             if ($request->hasFile('coupon_code')) {
               

                $file = $request->file('coupon_code');
                
                $destination = public_path() . '/assets/admin/upload/coupon/';
                $extension = $file->getClientOriginalExtension();
                $filename = rand(1111,9999).'.'.$extension;
                $file->move($destination, $filename);

                  \Excel::load(public_path() . '/assets/admin/upload/coupon/'.$filename, function($reader) {
                     $productData = Product::select('id')->orderBy('id','desc')->first();
                   $reader->ignoreEmpty();
                   $results = $reader->get()->toArray();
                   foreach($results as $key => $value){
                      $coupon = new Coupon();
                      $coupon->coupon_code = $value['coupon_code'];
                      $coupon->product_id = $productData->id;
                      $coupon->save();
                       //....
                        }
                    })->get();
                 }

           

            //Setting flash session message
                Session::flash('toastrHeading', Lang::get("ProductAdded"));
                Session::flash('toastrMessage', Lang::get("Product Added Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'Product Added Successfully',
                'action'    => 'reload'
            ];
        }
    }


    public function sendPromotion($id)
    {
       $infusionsoft = new \Infusionsoft\Infusionsoft(array(
    'clientId'     => 'XXXXXXXXXXXXXXXXXXXXXXXX',
    'clientSecret' => 'XXXXXXXXXX',
    'redirectUri'  => 'http://example.com/',
));
       return $infusionsoft;
    }


 //function for chage status
    public function amazonreviewstatus(Request $request)
    {
        $couponId = $request->input('couponId');
        $data = coupon::find($couponId);
        $data->amazon_review_url_status = 1;
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
    public function verification(Request $request)
    {
        $coupon = Coupon::whereIn('id',$request->input('couponId'))->get();
        foreach($coupon as $data)
        {
            $data->manual_verified = $request->input('amazon_url_status');
            $data->manual_verification_date = \Carbon\Carbon::now();
            $data->verified_by = $request->input('verifiedBy');
            $data->save();
        }
        //Setting flash session message
                Session::flash('toastrHeading', Lang::get("manuallyVerification"));
                Session::flash('toastrMessage', Lang::get("Manually Verification  Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'Manually Verification  Successfully',
                'action'    => 'reload'
            ];
    }

    //function for add notes
    public function addNotes(Request $request)
    {
            $product_id = $request->input('id');
            $notes = $request->input('note');
            $noteData = new Note();
            $noteData->product_id = $product_id;
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
    

    
}
