<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\AdminBaseController;
use Bllim\Datatables\Datatables;
use App\ServiceEngineer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;
use App\Setting;

use Illuminate\Http\Request;

class AdminServiceController extends AdminBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

        $this->data['pageTitle']        = 'ServiceEngineer';
        $this->data['dashboard']        = '';
        $this->data['ServiceEngineer']  = 'active';
        $this->data['Settings']        = '';
        return view('admin.serviceengineers', $this->data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $serviceEngineers = ServiceEngineer::select('serviceEngineers.id',
            'serviceEngineers.employee_number',
            'serviceEngineers.name',
            'serviceEngineers.email',
            'serviceEngineers.created_at'
        );

        return Datatables::of($serviceEngineers)
            ->edit_column('created_at', function($row){
                return $row->created_at->format('d-m-Y');
            })
            ->add_column('Action', function ($row) {
                return '<a onclick="editThis('.$row->id.')" class="btn btn-sm bg-blue-chambray margin-bottom-5">
                        <span class="fa fa-edit"></span> View & Edit</a>
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
		//
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
        $serviceEngineer = ServiceEngineer::find($id);
        $serviceEngineer->destroy($id);
        Session::flash('toastrHeading', Lang::get("messages.serviceEngineerDeleted"));
        Session::flash('toastrMessage', Lang::get("messages.serviceEngineerSuccessDeleted"));
        Session::flash('toastrType', 'success');
        return[
            "status"      => "success",
            "message"     => Lang::get("messages.serviceEngineerSuccessDeleted"),
            "action"      => "reload"
        ];
	}

    public function addOrEdit(Request $request)
    {

        $actionType      = $request->input('actionType');
        $id              = $request->input('id');
        $name            = $request->input('name');
        $email           = $request->input('email');
        $employee_number = $request->input('employee_number');
        $password        = $request->input('password');

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
}
