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
use App\Blog;
use App\BlogCategory;
use Illuminate\Http\Request;

class AdminBlogCategoryController extends AdminBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

        $this->data['pageTitle']        = 'Blog Category';
        $this->data['dashboard']        = '';
        $this->data['blog']  = 'active';
        $this->data['Settings']        = '';
        return view('admin.blog.viewblogcategory', $this->data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $serviceEngineers = BlogCategory::select('id',
            'name'
        );

        return Datatables::of($serviceEngineers)
            
            ->add_column('Action', function ($row) {

                return '
                        <a  href="'.route('admin.service.blogcategory.edit', $row->id).'" class="btn btn-sm bg-blue-chambray margin-bottom-5">
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
         $this->data['pageTitle']        = 'Edit Blog Category';
         $this->data['editblogcategory']  = 'active';
         $this->data['Settings']        = '';
		 $this->data['blogcategory'] = BlogCategory::find($id);
         return view('admin.blog.editblogcategory', $this->data);

	}
    public function editData(Request  $request)
    {
        

        $validator = Validator::make($request->all(),BlogCategory::rules('Edit',$request->input('id')));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
              $id =  $request->input('id');

              $company = BlogCategory::where('id','=',$id)->first();
             
          
            $company->name         =$request->input('name');
            
            $company->save();
             //Setting flash session message
                Session::flash('toastrHeading', Lang::get("BlogCategoryUpdated"));
                Session::flash('toastrMessage', Lang::get("Blog Category Updated Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'Blog Category Updated Successfully',
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
        $company = BlogCategory::find($id);
        $company->destroy($id);
        Session::flash('toastrHeading', Lang::get("Blog Category Deleted"));
        Session::flash('toastrMessage', Lang::get("Blog Category Deleted Successfully"));
        Session::flash('toastrType', 'success');
        return[
            "status"      => "success",
            "message"     => "Blog Category Deleted Successfully",
            "action"      => "reload"
        ];
	}

    public function add()
    {

        $this->data['pageTitle']        = 'Add Blog Category';
        $this->data['blog']  = 'active';
        $this->data['addblogcategory']          ='active';
        $this->data['Settings']        = '';
        return view('admin.blog.addblogcategory', $this->data);
        # code...
    }

    public function addData(Request  $request)
    {


       $validator = Validator::make($request->all(),Blog::rules('Add'));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
            $company = new BlogCategory();
            
            $company->name         = $request->input('name');

            $company->save();
            //Setting flash session message
                Session::flash('toastrHeading', Lang::get("BlogCategoryAdded"));
                Session::flash('toastrMessage', Lang::get("Blog Category Added Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'Blog Category Added Successfully',
                'action'    => 'reload'
            ];
        }
    }

    
    

   
}
