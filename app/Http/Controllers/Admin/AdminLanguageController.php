<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\AdminBaseController;
use Bllim\Datatables\Datatables;
use App\Language;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;
use App\Setting;
use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;

class AdminLanguageController extends AdminBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

        $this->data['pageTitle']        = 'Language';
        $this->data['dashboard']        = '';
        $this->data['lang']  = 'active';
        $this->data['Settings']        = '';
        return view('admin.language.viewlanguage', $this->data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $serviceEngineers = Language::select('id',
            'name'
        );

        return Datatables::of($serviceEngineers)
            
            ->add_column('Action', function ($row) {

                return '
                        <a  href="'.route('admin.service.lang.edit', $row->id).'" class="btn btn-sm bg-blue-chambray margin-bottom-5">
                        <span class="fa fa-edit"></span> Edit</a>
                         ';
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
         $this->data['pageTitle']        = 'Edit Language';
         $this->data['editlang']  = 'active';
         $this->data['Settings']        = '';
		 $this->data['language'] = Language::find($id);
         return view('admin.language.editlanguage', $this->data);

	}
    public function editData(Request  $request)
    {
        

        $validator = Validator::make($request->all(),Language::rules('Edit',$request->input('id')));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
              $id =  $request->input('id');

              $company = Language::where('id','=',$id)->first();
              $company->name         = $request->input('name');
              $company->value         = $request->input('value');

            
            $company->save();
             //Setting flash session message
                Session::flash('toastrHeading', Lang::get("LanguageUpdated"));
                Session::flash('toastrMessage', Lang::get("Language Updated Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'Language Updated Successfully',
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
	

    public function add()
    {

        $this->data['pageTitle']        = 'Add Language';
        $this->data['lang']  = 'active';
        $this->data['addlanguage']          ='active';
        $this->data['Settings']        = '';
        return view('admin.language.addlanguage', $this->data);
        # code...
    }

    public function addData(Request  $request)
    {


       $validator = Validator::make($request->all(),Language::rules('Add'));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
            $company = new Language();
            
            $company->name         = $request->input('name');
            $company->value         = $request->input('value');


            $company->save();
            //Setting flash session message
                Session::flash('toastrHeading', Lang::get("LanguageAdded"));
                Session::flash('toastrMessage', Lang::get("Language Added Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'Language Added Successfully',
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
