<?php

namespace App\Http\Controllers\Company;

use App\Driver;
use App\Carrier;
use App\UserVendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class DriverController extends Controller
{
    public function __construct()
    {
        $this->middleware('company');

       /* $this->middleware(['auth:company','permission:read_drivers'])->only('index');
        $this->middleware(['auth:company','permission:create_drivers'])->only('create','store');
        $this->middleware(['auth:company','permission:update_drivers'])->only('edit','update');
        $this->middleware(['auth:company','permission:delete_drivers'])->only('status','delete');
   */
    }

    public function index()
    {
        try{
            if (\Auth::guard('company')->user())
            {
                $userId = \Auth::guard('company')->user()->id;

            }
            elseif (\Auth::guard('user_vendor')->user())
            {
                $userId = \Auth::guard('user_vendor')->user()->company_id;

            }
            $driver = Driver::where('company_id',$userId)
                ->orderBy('created_at','desc')->get();

            return view('backend.driver.index',compact('driver'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function create()
    {
        try{
            $userId = \Auth::guard('company')->user()->id;

            $Carrier =Carrier::where('company_id',$userId)
                ->orderBy('created_at','desc')->get();

            $userVendors = UserVendor::where('company_id',$userId)->get();


            return view('backend.driver.create',compact('Carrier' , 'userVendors'));

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try{
            $admin =  checkEmail('admins',$request->get('email'));
            $company = checkEmail('companies',$request->get('email'));
            $userVendor = checkEmail('user_vendors',$request->get('email'));
            $supervisors = checkEmail('supervisors',$request->get('email'));
            if (!empty($admin) || !empty($company) || !empty($userVendor) || !empty($supervisors))
            {
                session()->flash('warning',trans('admin.E-mail is required and must be is E-mail'));
                return redirect()->back()->withInput();
            }
            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [$locale . '.name' =>['required','string'],
                    'email'=>'required|email|unique:drivers',
                    'status'=>'required|in:1,0',
                    'password'=>'required|min:3',
                    'image'=>'required|'.validateImage(),

                    'birthday'=>'required|date',
                    'ssn'=>'required|unique:drivers',
                    'nationality'=>'required|string',
                    'attachments' => 'required',
                    'attachments.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ];

            }
            foreach (config('translatable.locales') as $locale)
            {
                $message += [$locale . '.name.required' =>trans('admin.'.$locale.'.nameRequired'),
                    'email.required' =>trans('admin.E-mail is required and must be is E-mail'),
                    'status.required' =>trans('admin.Status is required'),
                    'password.required' =>trans('admin.Password is required must be 3 character'),
                    'image.required' =>trans('admin.Image is required and must be is extension is jpg,jpeg,png,bmp'),

                    'birthday.required' =>trans('admin.Birthday is required'),
                    'ssn.required' =>trans('admin.SSN is required'),
                    'nationality.required' =>trans('admin.Nationality is required'),
                    'attachments.required' =>trans('admin.attachments  is required'),

                ];
            }

            $validator = \Validator::make($request->all(), $rules, $message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $birthday =  $request->birthday;
            $date = str_replace('/', '-', $birthday);
            $age = getAge($date);
            if ($age <= 20)
            {
                session()->flash('warning',trans('admin.Please check birthday must be big or equal 20 ').$age );
                return redirect()->back()->withInput();
            }

            $requestData = $request->except(['_token','image','password','attachments']);
            if($request->file('image'))
            {
                $filename = uploadImages($request->image,'driver/','');
            }
            $multiple_filenames = '' ; 

            if($request->file('attachments'))
            {
                $multiple_filenames = MultipleUploadImages($request->attachments,'driver/','');
            }
            $requestData['image'] = $filename;
            $requestData['user_token'] = Str::random(60);
            $requestData['password'] = \Hash::make($request->password);
            if (\Auth::guard('company')->user())
            {
                $requestData['company_id'] = \Auth::guard('company')->user()->id;

            }
            if ($request->carrier_id)
            {
                $requestData['carrier_id'] = $request->carrier_id;

            }
            elseif (\Auth::guard('user_vendor')->user())
            {
                $requestData['company_id'] = \Auth::guard('user_vendor')->user()->company_id;
                $requestData['userVendor'] = \Auth::guard('user_vendor')->user()->id; 
                // $requestData['userVendor'] = \Auth::guard('user_vendor')->user()->name.'| '
                //     .\Auth::guard('user_vendor')->user()->email;
            }

            $create = Driver::create($requestData);
            $update = Driver::where('id',$create->id)->update(['number'=>$create->id]); 
            // insert attachments
            foreach($multiple_filenames as $file_name  ){
                DB::table('attachments')->insert([
                    'user_id'=>$create->id,
                    'file_name'=> $file_name,
                    'type' =>'driver'
                    ]);
            }


            session()->flash('success',trans('admin.Data has been added successfully'));

            return redirect()->route('company.driver.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try{

            $driver = Driver::find($id);
            checkData($driver);
            return view('backend.driver.edit',compact('driver'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try{
            $driver = Driver::find($request->id);
            $admin =  checkEmail('admins',$request->get('email'));
            $company = checkEmail('companies',$request->get('email'));
            $userVendor = checkEmail('user_vendors',$request->get('email'));
            $supervisor = checkEmail('user_vendors',$request->get('email'));
            if (!empty($admin) || !empty($company) || !empty($userVendor) || !empty($supervisor) || empty($driver))
            {
                session()->flash('warning',trans('admin.E-mail is required and must be is E-mail'));
                return redirect()->back()->withInput();
            }
            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [$locale . '.name' =>['required','string'],
                    'email'=>'required|email|unique:drivers,email,'.$request->id,
                    'status'=>'required|in:1,0',
                    'image'=>validateImage(),

                    'birthday'=>'required|date',
                    'ssn'=>'required|unique:drivers,ssn,'.$request->id,
                    'nationality'=>'required|string',
                ];

            }
            foreach (config('translatable.locales') as $locale)
            {
                $message += [$locale . '.name.required' =>trans('admin.'.$locale.'.nameRequired'),
                    'email.required' =>trans('admin.E-mail is required and must be is E-mail'),
                    'status.required' =>trans('admin.Status is required'),
                    'image.required' =>trans('admin.Image must be is extension is jpg,jpeg,png,bmp'),

                    'birthday.required' =>trans('admin.Birthday is required'),
                    'ssn.required' =>trans('admin.SSN is required'),
                    'nationality.required' =>trans('admin.Nationality is required'),
                ];

            }

            $validator = \Validator::make($request->all(), $rules, $message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $birthday =  $request->birthday;
            $date = str_replace('/', '-', $birthday);
            $age = getAge($date);
            if ($age <= 20)
            {
                session()->flash('warning',trans('admin.Please check birthday must be big or equal 20 ').$age );
                return redirect()->back()->withInput();
            }

            $requestData = $request->except(['_token','image']);
            if($request->file('image'))
            {
                $filename = uploadImages($request->image,'driver/',$driver->image);
                $requestData['image'] = $filename;
            }
            else
            {
                $requestData['image'] = $driver->image;
            }
            if (\Auth::guard('company')->user())
            {
                $requestData['company_id'] = \Auth::guard('company')->user()->id;

            }
            elseif (\Auth::guard('user_vendor')->user())
            {
                $requestData['company_id'] = \Auth::guard('user_vendor')->user()->company_id;
                $requestData['userVendor'] = \Auth::guard('user_vendor')->user()->name.'| '
                    .\Auth::guard('user_vendor')->user()->email;

            }
            $driver->update($requestData);
            session()->flash('success',trans('admin.Data has been updated successfully'));

            return redirect()->route('company.driver.index');


        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        try{

            $driver = Driver::find($id);
            checkData($driver);
            DeleteImage(public_path('upload/driver/'.$driver->image));
            DeleteMultipleImage($id, 'driver'); 
            $driver->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('company.driver.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function status($id)
    {
        try{

            $driver = Driver::find($id);

            checkData($driver);
            if ($driver->status == 0)
            {
                \DB::table('drivers')->where('id','=', $id)
                    ->update(['status' => 1]);

            }
            else
            {
                \DB::table('drivers')->where('id','=', $id)
                    ->update(['status' => 0]);

            }
            session()->flash('success',trans('admin.Status changed successfully'));

            return redirect()->route('company.driver.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }
}
