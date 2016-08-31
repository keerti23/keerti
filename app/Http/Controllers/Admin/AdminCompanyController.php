<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\AdminBaseController;
use Bllim\Datatables\Datatables;
use App\Company;
use App\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;
use App\Setting;
use Illuminate\Support\Facades\File;
use App\Note;
use Illuminate\Http\Request;

class AdminCompanyController extends AdminBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

        $this->data['pageTitle']        = 'Comapny';
        $this->data['dashboard']        = '';
        $this->data['company']  = 'active';
        $this->data['Settings']        = '';
        $this->data['allTimeSeller']  = Company::all()->count();
        $this->data['lastweekSeller'] = Company::where('created_at','<=', \Carbon\Carbon::now())->where('created_at','>=', \Carbon\Carbon::now()->subDays(7))->count();
        $this->data['lastmonthSeller'] = Company::where('created_at','<=', \Carbon\Carbon::now())->where('created_at','>=', \Carbon\Carbon::now()->subDays(30))->count();
        
         $this->data['lasthourProduct'] = Product::where('created_at','<=', \Carbon\Carbon::now())->where('created_at','>=', \Carbon\Carbon::now()->subHours(24))->count();

        $this->data['lastweekProduct'] = Product::where('created_at','<=', \Carbon\Carbon::now())->where('created_at','>=', \Carbon\Carbon::now()->subDays(7))->count();
       
        $this->data['lastmonthProduct'] = Product::where('created_at','<=', \Carbon\Carbon::now())->where('created_at','>=', \Carbon\Carbon::now()->subDays(30))->count();
        return view('admin.company.viewcompany', $this->data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $serviceEngineers = Company::select('company.id',
            'company.contact_name',
            'company.name',
            'company.email',
            'company.created_at'
        );

        return Datatables::of($serviceEngineers)
            ->edit_column('created_at', function($row){
                return $row->created_at->format('d-m-Y');
            })
            ->add_column('Action', function ($row) {

                return '<a  href="'.route('admin.service.company.view', $row->id).'" class="btn btn-sm bg-blue-chambray margin-bottom-5">
                        <span class="fa fa-eye"></span> View </a>
                        <a  href="'.route('admin.service.company.edit', $row->id).'" class="btn btn-sm bg-blue-chambray margin-bottom-5">
                        <span class="fa fa-edit"></span> Edit</a>
                         <a class="btn btn-sm btn-danger margin-bottom-5" onclick="deleteUser('.$row->id.', \''. addslashes($row->name) .'\')">
                        <span class="fa fa-trash"></span> Delete</a>';
            })
            ->make();
    }


    public function serviceEngineerDetails($id)
    {
        $user = ServiceEngineer::findOrFail($id);

        return $user;
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
         $this->data['pageTitle']        = 'Edit Company';
         $this->data['editcompany']  = 'active';
         $this->data['Settings']        = '';
		 $this->data['company'] = Company::find($id);
         return view('admin.company.editcompany', $this->data);

	}
    public function editData(Request  $request)
    {
        

        $validator = Validator::make($request->all(),Company::rules('Edit',$request->input('id')));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
              $id =  $request->input('id');

              $company = Company::where('id','=',$id)->first();
             
            if ($request->hasFile('logo')) {

                $file = $request->file('logo');
                $oldImage = $company->logo;
                File::delete(public_path() . '/assets/admin/layout/img/' . $oldImage);

                $destination = public_path() . '/assets/admin/layout/img/';
                $extension = $file->getClientOriginalExtension();
                $filename = rand(1111,9999).'.'.$extension;
                $file->move($destination, $filename);

                $company->logo= $filename;
            }
            $company->name         =$request->input('name');
            $company->email        =$request->input('email');
            $company->contact_name =$request->input('contact_name');
            $company->phone_no     =$request->input('phone_no');

            $company->save();
             //Setting flash session message
                Session::flash('toastrHeading', Lang::get("CompanyUpdated"));
                Session::flash('toastrMessage', Lang::get("Company Updated Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'Company Updated Successfully',
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
        $company = Company::find($id);
        $company->destroy($id);
        Session::flash('toastrHeading', Lang::get("Comapny Deleted"));
        Session::flash('toastrMessage', Lang::get("Comapny Deleted Successfully"));
        Session::flash('toastrType', 'success');
        return[
            "status"      => "success",
            "message"     => "Company Deleted Successfully",
            "action"      => "reload"
        ];
	}

    public function add()
    {
        $this->data['pageTitle']        = 'Add Company';
        $this->data['company']  = 'active';
        $this->data['addComapny']          ='active';
        $this->data['Settings']        = '';
        return view('admin.company.addcompany', $this->data);
        # code...
    }

    public function addData(Request  $request)
    {


       $validator = Validator::make($request->all(),Company::rules('Add'));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
            $company = new Company();
            if ($request->hasFile('logo')) {

                $file = $request->file('logo');
                $oldImage = $company->logo;
                File::delete(public_path() . '/assets/admin/layout/img/' . $oldImage);

                $destination = public_path() . '/assets/admin/layout/img/';
                $extension = $file->getClientOriginalExtension();
                $filename = rand(1111,9999).'.'.$extension;
                $file->move($destination, $filename);

                $company->logo= $filename;
            }
            $company->name         =$request->input('name');
            $company->email        =$request->input('email');
            $company->contact_name =$request->input('contact_name');
            $company->phone_no     =$request->input('phone_no');

            $company->save();
            //Setting flash session message
                Session::flash('toastrHeading', Lang::get("CompanyAdded"));
                Session::flash('toastrMessage', Lang::get("Company Added Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'Company Added Successfully',
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
        $this->data['productTotal'] = Product::where('company_id','=',$id)->count();
         $this->data['note'] = Note::select('notes.id','notes.created_at','notes.reviewer_id','notes.note','users.name')->leftjoin('users','users.id','=','notes.reviewer_id')->leftJoin('product','product.id','=','notes.product_id')
->leftjoin('company','company.id','=','product.company_id')->where('notes.company_id','=',$id)
         ->get();

        return view('admin.company.companydetail',$this->data);
    }
    

    public function addOrEdit(Request $request)
    {

        $actionType      = $request->input('actionType');
        $id              = $request->input('id');
        $name            = $request->input('name');
        $email           = $request->input('email');
        $contact_name    = $request->input('contact_name');
        $phone_no        = $request->input('phone_no');

    

        if($actionType == 'Edit' && $id != '')
        {
            $validationRules = ServiceEngineer::rules('Edit',$id);

        }else{
            $validationRules = ServiceEngineer::rules('Add');
        }

        //Validation for inputs
        $validator = Validator::make($request->all(), $validationRules);

        //Check validation
        if ($validator->fails()) {
            return [
                "status" => "error",
                "message" => $validator->getMessageBag()->toArray()
            ];
        } else {

            $addedArray = array(
                'email'           => $email,
                'name'            => $name,
                'employee_number' => $employee_number,

            );

            if(($actionType == 'Edit' && $id != '' && $password != '') || $actionType == 'Add')
            {
                $addedArray['password'] = Hash::make($password);
            }

            if($actionType == 'Add')
            {

                ServiceEngineer::create($addedArray);

                //Setting flash session message
                Session::flash('toastrHeading', Lang::get("messages.serviceEngineerAdded"));
                Session::flash('toastrMessage', Lang::get("messages.serviceEngineerSuccessAdded"));
                Session::flash('toastrType', 'success');
                return[
                    "status"      => "success",
                    "message"     => Lang::get("messages.serviceEngineerSuccessAdded"),
                    "action"      =>    "reload"
                ];
            }else if($actionType == 'Edit'){
                $user = ServiceEngineer::find($id);

                $user->update($addedArray);

                //Setting flash session message
                Session::flash('toastrHeading', Lang::get("messages.serviceEngineerUpdated"));
                Session::flash('toastrMessage', Lang::get("messages.serviceEngineerSuccessUpdated"));
                Session::flash('toastrType', 'success');

                return [
                    "status"      => "success",
                    "message"     => Lang::get("messages.serviceEngineerSuccessUpdated"),
                    "action"      => "reload"
                ];
            }

        }
    }

     //function for add notes
    public function addNotes(Request $request)
    {
            $company_id = $request->input('id');
            $notes = $request->input('note');
            $noteData = new Note();
            $noteData->company_id = $company_id;
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
