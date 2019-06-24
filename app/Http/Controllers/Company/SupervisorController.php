<?php

namespace App\Http\Controllers\Company;

use App\Member;
use App\Supervisor;
use App\UserVendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware('company');

        // $this->middleware(['auth:company','permission:read_supervisors'])->only('index');
        // $this->middleware(['auth:company','permission:create_supervisors'])->only('create','store');
        // $this->middleware(['auth:company','permission:update_supervisors'])->only('edit','update');
        // $this->middleware(['auth:company','permission:delete_supervisors'])->only('status','delete');

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
            $supervisor = Supervisor::
            where('company_id',$userId)->
            orderBy('created_at','desc')->get();

            return view('backend.supervisor.index',compact('supervisor'));
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
            $member = Member::where('status',1)->where('company_id',$userId)->get();
            $userVendors = UserVendor::where('company_id',$userId)->get();
            

            return view('backend.supervisor.create',compact('member','userVendors'));

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
            if (!empty($admin) || !empty($company) || !empty($userVendor))
            {
                session()->flash('warning',trans('admin.E-mail is required and must be is E-mail'));
                return redirect()->back()->withInput();
            }
            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [$locale . '.name' =>['required','string'],
                    'email'=>'required|email|unique:supervisors',
                    'member_id'=>['required','exists:members,id',],
                    'userVendor'=>['required','exists:user_vendors,id',],
                    'status'=>'required|in:1,0',
                    'password'=>'required|min:3',
                    'image'=>'required|'.validateImage(),

                    'birthday'=>'required|date',
                    'ssn'=>'required|unique:supervisors',
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
                    'member_id.required' =>trans('admin.Member name is required'),
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
                $filename = uploadImages($request->image,'supervisor/','');
            }
            $multiple_filenames = '' ; 
            if($request->file('attachments'))
            {
                $multiple_filenames = MultipleUploadImages($request->attachments,'supervisor/');
            }

            $requestData['image'] = $filename;
            $requestData['user_token'] = Str::random(60);
            $requestData['password'] = \Hash::make($request->password);
            if (\Auth::guard('company')->user())
            {
                $requestData['company_id'] = \Auth::guard('company')->user()->id;

            }
            elseif (\Auth::guard('user_vendor')->user())
            {
                $requestData['company_id'] = \Auth::guard('user_vendor')->user()->company_id;
                $requestData['userVendor'] = \Auth::guard('user_vendor')->user()->id; 
                // $requestData['userVendor'] = \Auth::guard('user_vendor')->user()->name.'| '
                //     .\Auth::guard('user_vendor')->user()->email;
            }

            $create = Supervisor::create($requestData);
            $update = Supervisor::where('id',$create->id)->update(['number'=>$create->id]);
            // insert attachments
            foreach($multiple_filenames as $file_name  ){
                DB::table('attachments')->insert([
                    'user_id'=>$create->id,
                    'file_name'=> $file_name,
                    'type' =>'supervisor'
                    ]);
            }

            session()->flash('success',trans('admin.Data has been added successfully'));

            return redirect()->route('company.supervisor.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try{
            $userId = \Auth::guard('company')->user()->id;
            $member = Member::where('company_id',$userId)->get();

            $supervisor = Supervisor::find($id);
            checkData($supervisor);
            return view('backend.supervisor.edit',compact('supervisor','member'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try{
            $supervisor = Supervisor::find($request->id);
            $admin =  checkEmail('admins',$request->get('email'));
            $company = checkEmail('companies',$request->get('email'));
            $userVendor = checkEmail('user_vendors',$request->get('email'));
            if (!empty($admin) || !empty($company) || !empty($userVendor) || empty($supervisor))
            {
                session()->flash('warning',trans('admin.E-mail is required and must be is E-mail'));
                return redirect()->back()->withInput();
            }
            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [$locale . '.name' =>['required','string'],
                    'email'=>'required|email|unique:supervisors,email,'.$request->id,
                    'status'=>'required|in:1,0',
                    'image'=>validateImage(),
                    'member_id'=>['required','exists:members,id',],
                    'birthday'=>'required|date',
                    'ssn'=>'required|unique:supervisors,ssn,'.$request->id,
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
                    'member_id.required' =>trans('admin.Member name is required'),
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
                $filename = uploadImages($request->image,'supervisor/',$supervisor->image);
                $requestData['image'] = $filename;
            }
            else
            {
                $requestData['image'] = $supervisor->image;
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
            $supervisor->update($requestData);
            session()->flash('success',trans('admin.Data has been updated successfully'));

            return redirect()->route('company.supervisor.index');


        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        try{

            $supervisor = Supervisor::find($id);

            checkData($supervisor);
            DeleteImage(public_path('upload/supervisor/'.$supervisor->image));
            DeleteMultipleImage($id, 'supervisor'); 

            $supervisor->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('company.supervisor.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function status($id)
    {
        try{

            $supervisor = Supervisor::find($id);

            checkData($supervisor);
            if ($supervisor->status == 0)
            {
                \DB::table('supervisors')->where('id','=', $id)
                    ->update(['status' => 1]);

            }
            else
            {
                \DB::table('supervisors')->where('id','=', $id)
                    ->update(['status' => 0]);

            }
            session()->flash('success',trans('admin.Status changed successfully'));

            return redirect()->route('company.supervisor.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }
}
