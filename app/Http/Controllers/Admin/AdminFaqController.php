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
use App\Faq;

use Illuminate\Http\Request;

class AdminFaqController extends AdminBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

        $this->data['pageTitle']        = 'FAQ';
        $this->data['dashboard']        = '';
        $this->data['pages']  = 'active';
        $this->data['Settings']        = '';
        return view('admin.faq.viewfaq', $this->data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $serviceEngineers = Faq::select('id',
            'question'
        );

        return Datatables::of($serviceEngineers)
            
            ->add_column('Action', function ($row) {

                return '
                        <a  href="'.route('admin.service.faq.edit', $row->id).'" class="btn btn-sm bg-blue-chambray margin-bottom-5">
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
         $this->data['pageTitle']        = 'Edit FAQ';
         $this->data['editpages']  = 'active';
         $this->data['Settings']        = '';
		 $this->data['faq'] = Faq::find($id);
         return view('admin.faq.editfaq', $this->data);

	}
    public function editData(Request  $request)
    {
        

        $validator = Validator::make($request->all(),Faq::rules('Edit',$request->input('id')));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
              $id =  $request->input('id');

              $company = Faq::where('id','=',$id)->first();
             
          
            $company->question         =$request->input('question');
            $company->answer         =$request->input('answer');
            
            $company->save();
             //Setting flash session message
                Session::flash('toastrHeading', Lang::get("FaqUpdated"));
                Session::flash('toastrMessage', Lang::get("Faq Updated Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'Faq Updated Successfully',
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
        $company = Faq::find($id);
        $company->destroy($id);
        Session::flash('toastrHeading', Lang::get("Faq Deleted"));
        Session::flash('toastrMessage', Lang::get("Faq Deleted Successfully"));
        Session::flash('toastrType', 'success');
        return[
            "status"      => "success",
            "message"     => "Faq Deleted Successfully",
            "action"      => "reload"
        ];
	}

    public function add()
    {

        $this->data['pageTitle']        = 'Add Faq';
        $this->data['pages']  = 'active';
        $this->data['addpages']          ='active';
        $this->data['Settings']        = '';
        return view('admin.faq.addfaq', $this->data);
        # code...
    }

    public function addData(Request  $request)
    {


       $validator = Validator::make($request->all(),Faq::rules('Add'));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
            $company = new Faq();
            
            $company->question         =$request->input('question');
            $company->answer         =$request->input('answer');

            $company->save();
            //Setting flash session message
                Session::flash('toastrHeading', Lang::get("QuestionAdded"));
                Session::flash('toastrMessage', Lang::get("Qusestion Added Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'Qusestion Added Successfully',
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
