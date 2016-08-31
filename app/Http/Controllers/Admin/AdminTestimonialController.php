<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\AdminBaseController;
use Bllim\Datatables\Datatables;
use App\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;
use App\Setting;
use Illuminate\Support\Facades\File;
use App\Testimonial;

use Illuminate\Http\Request;

class AdminTestimonialController extends AdminBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

        $this->data['pageTitle']        = 'Testimonial';
        $this->data['dashboard']        = '';
        $this->data['pages']  = 'active';
        $this->data['Settings']        = '';
        return view('admin.testimonial.viewtestimonial', $this->data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $serviceEngineers = Testimonial::select('id',
            'name'
        );

        return Datatables::of($serviceEngineers)
            
            ->add_column('Action', function ($row) {

                return '
                        <a  href="'.route('admin.service.testimonial.edit', $row->id).'" class="btn btn-sm bg-blue-chambray margin-bottom-5">
                        <span class="fa fa-edit"></span> Edit</a>
                         <a class="btn btn-sm btn-danger margin-bottom-5" onclick="deleteUser('.$row->id.', \''. addslashes($row->name) .'\')">
                        <span class="fa fa-trash"></span> Delete</a>';
            })
            ->make();
    }


   
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        //
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
         $this->data['pageTitle']        = 'Edit Testimonial';
         $this->data['editpages']  = 'active';
         $this->data['Settings']        = '';
		 $this->data['testimonial'] = Testimonial::find($id);
         return view('admin.testimonial.edittestimonial', $this->data);

	}
    public function editData(Request  $request)
    {
        

        $validator = Validator::make($request->all(),Testimonial::rules('Edit',$request->input('id')));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
              $id =  $request->input('id');

              $company = Testimonial::where('id','=',$id)->first();
             
          
            $company->name         =$request->input('name');
            $company->content         =$request->input('content');
            
            $company->save();
             //Setting flash session message
                Session::flash('toastrHeading', Lang::get("TestimonialUpdated"));
                Session::flash('toastrMessage', Lang::get("Testimonial Updated Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'Testimonial Updated Successfully',
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
        $company = Testimonial::find($id);
        $company->destroy($id);
        Session::flash('toastrHeading', Lang::get("Testimonial Deleted"));
        Session::flash('toastrMessage', Lang::get("Testimonial Deleted Successfully"));
        Session::flash('toastrType', 'success');
        return[
            "status"      => "success",
            "message"     => "Testimonial Deleted Successfully",
            "action"      => "reload"
        ];
	}

    public function add()
    {

        $this->data['pageTitle']        = 'Add Testimonial';
        $this->data['pages']  = 'active';
        $this->data['addpages']          ='active';
        $this->data['Settings']        = '';
        return view('admin.testimonial.addtestimonial', $this->data);
        # code...
    }

    public function addData(Request  $request)
    {


       $validator = Validator::make($request->all(),Testimonial::rules('Add'));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
            $company = new Testimonial();
            
            $company->name         =$request->input('name');
            $company->content         =$request->input('content');

            $company->save();
            //Setting flash session message
                Session::flash('toastrHeading', Lang::get("TestimonialAdded"));
                Session::flash('toastrMessage', Lang::get("Testimonial Added Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'Testimonial Added Successfully',
                'action'    => 'reload'
            ];
        }
    }

    //SHOW COMPANY PROFILE PAGE
    public function view($id)
    {
        $this->data['pageTitle']        = 'View Company Profile';
        $this->data['ViewComapny']          ='active';
        $this->data['companyview']  = 'active';
        $this->data['Settings']        = '';
        $this->data['company'] = Company::find($id);
        $this->data['product'] = Product::where('company_id','=',$id)->get();
        return view('admin.company.companydetail',$this->data);
    }
    

   
}
