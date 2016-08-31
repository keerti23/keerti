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
use App\Page;
use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;

class AdminPagesController extends AdminBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

        $this->data['pageTitle']        = 'Pages';
        $this->data['dashboard']        = '';
        $this->data['pages']  = 'active';
        $this->data['Settings']        = '';
        return view('admin.pages.viewpages', $this->data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $serviceEngineers = Page::select('id',
            'title','sub_title','description'
        );

        return Datatables::of($serviceEngineers)
            
            ->add_column('Action', function ($row) {

                return '
                        <a  href="'.route('admin.service.pages.edit', $row->id).'" class="btn btn-sm bg-blue-chambray margin-bottom-5">
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
         $this->data['pageTitle']        = 'Edit Pages';
         $this->data['editpages']  = 'active';
         $this->data['Settings']        = '';
		 $this->data['pages'] = Page::find($id);
         return view('admin.pages.editpages', $this->data);

	}
    public function editData(Request  $request)
    {
        

        $validator = Validator::make($request->all(),Page::rules('Edit',$request->input('id')));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
              $id =  $request->input('id');

              $company = Page::where('id','=',$id)->first();
              if ($request->hasFile('image')) {

                $file = $request->file('image');
                $oldImage = $company->image;
                File::delete(public_path() . '/assets/admin/upload/pages/' . $oldImage);

                $destination = public_path() . '/assets/admin/upload/pages/';
                $extension = $file->getClientOriginalExtension();
                $filename = rand(1111,9999).'.'.$extension;
                $file->move($destination, $filename);

                $company->image = $filename;
            }
             
          
            $company->title        =$request->input('title');
            $company->sub_title         =$request->input('sub_title');
            $company->description         =$request->input('description');
            $company->meta_keyword         =$request->input('meta_keyword');
            $company->meta_title         =$request->input('meta_title');
            $company->meta_description         =$request->input('meta_description');
            
            $company->save();
           $this->data['pageTitle']        = 'Pages';
           $this->data['dashboard']        = '';
           $this->data['pages']  = 'active';
           $this->data['Settings']        = '';
           return view('admin.pages.viewpages', $this->data);
             
      
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
        $company = Page::find($id);
        $company->destroy($id);
        Session::flash('toastrHeading', Lang::get("Page Deleted"));
        Session::flash('toastrMessage', Lang::get("Page Deleted Successfully"));
        Session::flash('toastrType', 'success');
        return[
            "status"      => "success",
            "message"     => "Page Deleted Successfully",
            "action"      => "reload"
        ];
	}

    public function add()
    {

        $this->data['pageTitle']        = 'Add Page';
        $this->data['pages']  = 'active';
        $this->data['addPages']          ='active';
        $this->data['Settings']        = '';
        return view('admin.pages.addpages', $this->data);
        # code...
    }

    public function addData(Request  $request)
    {


       $validator = Validator::make($request->all(),Page::rules('Add'));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
            $company = new Page();
            
            $company->title        =$request->input('title');
             $company->sub_title         =$request->input('sub_title');
              $company->description         =$request->input('description');
               $company->meta_keyword         =$request->input('meta_keyword');
                $company->meta_title         =$request->input('meta_title');
                 $company->meta_description         =$request->input('meta_description');

            $company->save();
            //Setting flash session message
                Session::flash('toastrHeading', Lang::get("PagesAdded"));
                Session::flash('toastrMessage', Lang::get("Pages Added Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'Pages Added Successfully',
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
