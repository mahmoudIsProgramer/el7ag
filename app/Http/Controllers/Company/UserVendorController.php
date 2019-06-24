<?php

namespace App\Http\Controllers\Company;

use App\UserVendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Permission; 
class UserVendorController extends Controller
{
    public function __construct()
    {

        $this->middleware('company');
       /* $this->middleware(['auth:company','permission:read_supervisors'])->only('index');
        $this->middleware(['auth:company','permission:create_supervisors'])->only('create','store');
        $this->middleware(['auth:company','permission:update_supervisors'])->only('edit','update');
        $this->middleware(['auth:company','permission:delete_supervisors'])->only('status','delete');
    */

    }

    public function index()
    {
        try{
            $userVendor = UserVendor::where('company_id',\Auth::guard('company')->user()->id)
                ->orderBy('created_at','desc')->get();

            return view('backend.company.userVendor.index',compact('userVendor'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function create()
    {
        try{

            return view('backend.company.userVendor.create');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        // dd($request->get('permission')); 


        try{
            $check =  checkEmail('admins',$request->get('email'));
            $company = checkEmail('companies',$request->get('email'));
            if (!empty($check) || !empty($company))
            {
                session()->flash('warning',trans('admin.E-mail is required and must be is E-mail'));
                return redirect()->back()->withInput();
            }
            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [$locale . '.name' =>['required','string'],
                    'email'=>'required|email|unique:user_vendors',
                    'number'=>'required|unique:user_vendors',
                    'status'=>'required|in:1,0',
                    'password'=>'required|min:3',
                    'image'=>'required|'.validateImage(),
                    'permission'=>'required|min:1',
                ];

            }
            foreach (config('translatable.locales') as $locale)
            {
                $message += [$locale . '.name.required' =>trans('admin.'.$locale.'.nameRequired'),
                    'email.required' =>trans('admin.E-mail is required and must be is E-mail'),
                    'number.required' =>trans('admin.Number is required and must be is Number'),
                    'status.required' =>trans('admin.Status is required'),
                    'password.required' =>trans('admin.Password is required must be 3 character'),
                    'image.required' =>trans('admin.Image is required and must be is extension is jpg,jpeg,png,bmp'),
                    'permission.required' =>trans('admin.Permission is required and must be min select one'),

                ];

            }

            $validator = \Validator::make($request->all(), $rules, $message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $requestData = $request->except(['_token','image','password','permission']);
            if($request->file('image'))
            {
                $filename = uploadImages($request->image,'userVendor/','');
            }

            $requestData['image'] = $filename;
            $requestData['user_token'] = Str::random(60);
            $requestData['password'] = \Hash::make($request->password);
            $requestData['company_id'] = \Auth::guard('company')->user()->id;



            $userVendor = UserVendor::create($requestData);

            $userVendor->attachRole('userVendor');
            // $userVendor->attachPermission($request->get('permission'));
            // $userVendor->syncPermissions($request->get('permission'));
            
            // dd('alksdjk'); 

            session()->flash('success',trans('admin.Data has been added successfully'));

            return redirect()->route('company.userVendor.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        try{

            $userVendor = UserVendor::find($id);
            checkData($userVendor);
            return view('backend.company.userVendor.edit',compact('userVendor'));


        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }


    public function update(Request $request)
    {
        try{

            $userVendor = UserVendor::find($request->id);
            $check =  checkEmail('admins',$request->get('email'));
            $company = checkEmail('companies',$request->get('email'));
            if (!empty($check) || !empty($company) || empty($userVendor))
            {
                session()->flash('warning',trans('admin.E-mail is required and must be is E-mail'));
                return redirect()->back()->withInput();
            }
            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [$locale . '.name' =>['required','string'],
                    'email'=>'required|email|unique:user_vendors,email,'.$request->id,
                    'number'=>'required|unique:user_vendors,number,'.$request->id,

                    'status'=>'required|in:1,0',
                    'image'=>validateImage(),
                    'permission'=>'required|min:1',

                ];

            }
            foreach (config('translatable.locales') as $locale)
            {
                $message += [$locale . '.name.required' =>trans('admin.'.$locale.'.nameRequired'),
                    'email.required' =>trans('admin.E-mail is required and must be is E-mail'),
                    'number.required' =>trans('admin.Number is required and must be is Number'),

                    'status.required' =>trans('admin.Status is required'),
                    'image.required' =>trans('admin.Image must be is extension is jpg,jpeg,png,bmp'),
                    'permission.required' =>trans('admin.Permission is required and must be min select one'),
                ];

            }

            $validator = \Validator::make($request->all(), $rules, $message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $requestData = $request->except(['_token','image','permission']);
            if($request->file('image'))
            {
                $filename = uploadImages($request->image,'userVendor/',$userVendor->image);
                $requestData['image'] = $filename;
            }
            else
            {
                $requestData['image'] = $userVendor->image;
            }
            $userVendor->update($requestData);
            $userVendor->syncPermissions($request->get('permission'));
            session()->flash('success',trans('admin.Data has been updated successfully'));

            return redirect()->route('company.userVendor.index');



        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try{

            $userVendor = UserVendor::find($id);

            checkData($userVendor);
            DeleteImage(public_path('upload/userVendor/'.$userVendor->image));
            $userVendor->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('company.userVendor.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function status($id)
    {
        try{

            $userVendor = UserVendor::find($id);

            checkData($userVendor);
            if ($userVendor->status == 0)
            {
                \DB::table('user_vendors')->where('id','=', $id)
                    ->update(['status' => 1]);

            }
            else
            {
                \DB::table('user_vendors')->where('id','=', $id)
                    ->update(['status' => 0]);

            }
            session()->flash('success',trans('admin.Status changed successfully'));

            return redirect()->route('company.userVendor.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }
}
