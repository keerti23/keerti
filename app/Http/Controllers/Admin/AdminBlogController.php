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

class AdminBlogController extends AdminBaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

        $this->data['pageTitle']        = 'Blog';
        $this->data['dashboard']        = '';
        $this->data['blog']  = 'active';
        $this->data['Settings']        = '';
        return view('admin.blog.viewblog', $this->data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $serviceEngineers = Blog::select('id',
            'title','author'
        );

        return Datatables::of($serviceEngineers)
            
            ->add_column('Action', function ($row) {

                return '
                        <a  href="'.route('admin.service.blog.edit', $row->id).'" class="btn btn-sm bg-blue-chambray margin-bottom-5">
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
         $this->data['pageTitle']        = 'Edit Blog';
         $this->data['editblog']  = 'active';
         $this->data['Settings']        = '';
		 $this->data['blog'] = Blog::find($id);
          $this->data['category']        = BlogCategory::all();
         return view('admin.blog.editblog', $this->data);

	}
    public function editData(Request  $request)
    {
       $validator = Validator::make($request->all(),Blog::rules('Add'));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
            $id = $request->input('id');
         

            $blog = Blog::find($id);
            if ($request->hasFile('image')) {

                $file = $request->file('image');
                $oldImage = $blog->image;
                File::delete(public_path() . '/assets/admin/upload/blog/' . $oldImage);

                $destination = public_path() . '/assets/admin/upload/blog/';
                $extension = $file->getClientOriginalExtension();
                $filename = rand(1111,9999).'.'.$extension;
                $file->move($destination, $filename);

                $blog->image = $filename;
            }

            

                //$product->product_image = $filename;
        
            $blog->title                          = $request->input('title');
            $blog->content                        = $request->input('content');
            $blog->author                      = $request->input('author');
            $blog->blog_category_id            = $request->input('blog_category_id');
            
            $blog->save();

           
          //  Setting flash session message
                Session::flash('toastrHeading', Lang::get("BlogUpdated"));
                Session::flash('toastrMessage', Lang::get("Blog Updated Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'Blog Added Successfully',
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
        $company = Blog::find($id);
        $company->destroy($id);
        Session::flash('toastrHeading', Lang::get("Blog Deleted"));
        Session::flash('toastrMessage', Lang::get("Blog Deleted Successfully"));
        Session::flash('toastrType', 'success');
        return[
            "status"      => "success",
            "message"     => "Blog Deleted Successfully",
            "action"      => "reload"
        ];
	}

    public function add()
    {

        $this->data['pageTitle']        = 'Add Blog';
        $this->data['blog']  = 'active';
        $this->data['addblog']          ='active';
        $this->data['Settings']        = '';
        $this->data['category']        = BlogCategory::all();
        return view('admin.blog.addblog', $this->data);
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
            $blog = new Blog();
            
            if ($request->hasFile('image')) {

                $file = $request->file('image');
                $oldImage = $blog->image;
                File::delete(public_path() . '/assets/admin/upload/blog/' . $oldImage);

                $destination = public_path() . '/assets/admin/upload/blog/';
                $extension = $file->getClientOriginalExtension();
                $filename = rand(1111,9999).'.'.$extension;
                $file->move($destination, $filename);

                $blog->image = $filename;
            }

            

                //$product->product_image = $filename;
        
            $blog->title                          = $request->input('title');
            $blog->content                        = $request->input('content');
            $blog->author                      = $request->input('author');
            $blog->blog_category_id            = $request->input('blog_category_id');
            
            $blog->save();

        
            //Setting flash session message
                Session::flash('toastrHeading', Lang::get("BlogAdded"));
                Session::flash('toastrMessage', Lang::get("Blog Added Successfully"));
                Session::flash('toastrType', 'success');
            return [
                'status'    => 'success',
                'message'   => 'Blog Added Successfully',
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
