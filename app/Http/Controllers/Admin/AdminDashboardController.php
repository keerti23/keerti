<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\AdminBaseController;
use App\Setting;
use Illuminate\Support\Facades\Validator;
use App\Admin;
use App\Company;
use App\Product;
use App\User;
use App\Contact;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Bllim\Datatables\Datatables;


class AdminDashboardController extends AdminBaseController {

    public function dashboard()
    {


        $setting =Setting::find(1);

        $this->data['pageTitle']        = 'Dashboard';
        $this->data['dashboard']        = 'active';
        $this->data['ServiceEngineer']  = '';
        $this->data['Settings']        = '';
        $this->data['totalCompany']    = Company::all()->count();
        $this->data['totalProduct']    = Product::all()->count();
        $this->data['totalUser']    = User::all()->count();
        return view('admin.dashboard', $this->data);
    }

    public function changePasswordView()
    {
        $this->data['pageTitle']        = 'Change Password';
        $this->data['dashboard']        = '';
        $this->data['ServiceEngineer']  = '';
        $this->data['Settings']        = '';
        return view('admin.changepasswordform', $this->data);
    }

    public function changePassword(Request $request)
    {

        $validator = Validator::make($request->all(),Admin::rules('passwordReset'));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];

        }else{
            $password = $this->data['admin']->password;
            $oldpassword =$request->input('oldpassword');
            $newpassword =$request->input('newpassword');

            if(Hash::check($oldpassword,$password)) {
                $id = $this->data['admin']->id;
                $user = Admin::find($id);
                $user->password = bcrypt($newpassword);
                $user->save();

                Session::flash('toastrHeading', Lang::get("messages.passwordChanged"));
                Session::flash('toastrMessage', Lang::get("messages.passwordSuccessChange"));
                Session::flash('toastrType', 'success');
                return [
                    'status'    => 'success',
                    'message'   => Lang::get("messages.passwordSuccessChange"),
                    'url'       => route('admin.dashboard'),
                    'action'    => 'redirect'
                ];
            }else{
                return[
                    'status' => 'error',
                    'message' => 'Old Password Not correct'
                ];
            }
        }
    }
    public function generalSettings()
    {


        $this->data['pageTitle']        = 'Settings';
        $this->data['general']          ='active';
        $this->data['Settings']        = 'active';
        return view('admin.generalsettings', $this->data);
    }
    public function updateGeneralSettings(Request $request)
    {
        $validator = Validator::make($request->all(),Setting::rules('update'));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
            if ($request->hasFile('logo')) {

                $file = $request->file('logo');
                $oldImage = $this->data['settings']->logo;
                File::delete(public_path() . '/assets/admin/layout/img/' . $oldImage);

                $destination = public_path() . '/assets/admin/layout/img/';
                $extension = $file->getClientOriginalExtension();
                $filename = rand(1111,9999).'.'.$extension;
                $file->move($destination, $filename);

                $this->data['settings']->logo= $filename;
            }
            $this->data['settings']->name         =$request->input('name');
            $this->data['settings']->email        =$request->input('email');
            $this->data['settings']->address      =$request->input('address');
            $this->data['settings']->phone_number =$request->input('phone_number');

            $this->data['settings']->save();
            return [
                'status'    => 'success',
                'message'   => Lang::get("messages.settingsSuccessChange"),
                'action'    => 'reload'
            ];
        }
    }
    public function profileSettings()
    {


        $this->data['pageTitle']        = 'Settings';
        $this->data['Settings']        = 'active';
        $this->data['profile']          = 'active';
        return view('admin.profilesettings', $this->data);
    }
    public function updateProfileSettings(Request $request)
    {
        $id =$this->data['admin']->id;
        $validator = Validator::make($request->all(),Admin::rules('update',$id));
        if ($validator->fails()) {
            return [
                'status'    => 'error',
                'message'   => $validator->getMessageBag()->toArray()
            ];
        }else
        {
            $this->data['admin']->name                         = $request->input('name');
            $this->data['admin']->email                        = $request->input('email');
            
            $this->data['admin']->save();
            return [
                'status'    => 'success',
                'message'   => Lang::get("messages.settingsSuccessChange"),
                'action'    => 'reload'
            ];
        }
    }

    public function viewContact()
    {
        $this->data['pageTitle']        = 'Contact';
        $this->data['dashboard']        = '';
        $this->data['contact']  = 'active';
        $this->data['Settings']        = '';
        return view('admin.contact.viewcontact', $this->data);
    }

    public function create()
    {
$serviceEngineers = Contact::select('contact.id',
            'contact.name','contact.email','contact.message'
        );

        return Datatables::of($serviceEngineers)
            
           
            ->make();
    }

   
}
