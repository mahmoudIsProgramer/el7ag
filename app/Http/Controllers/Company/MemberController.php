<?php

namespace App\Http\Controllers\Company;

use App\Member;
use App\UserVendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('company');
        /*
        $this->middleware(['auth:company','permission:read_members'])->only('index');
        $this->middleware(['auth:company','permission:create_members'])->only('create','store');
        $this->middleware(['auth:company','permission:update_members'])->only('edit','update');
        $this->middleware(['auth:company','permission:delete_members'])->only('status','delete');
    */}

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
            $member = Member::where('company_id',$userId)
                ->orderBy('created_at','desc')->get();

            return view('backend.member.index',compact('member'));
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
            $userVendors = UserVendor::where('company_id',$userId)->get();

            return view('backend.member.create' , compact("userVendors"));

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        // dd($request->all()); 
        try{
            $admin =  checkEmail('admins',$request->get('email'));
            $company = checkEmail('companies',$request->get('email'));
            $userVendor = checkEmail('user_vendors',$request->get('email'));
            $supervisors = checkEmail('supervisors',$request->get('email'));
            $driver = checkEmail('drivers',$request->get('email'));
            if (!empty($admin) || !empty($company) || !empty($userVendor)
                || !empty($supervisors) || !empty($driver))
            {
                session()->flash('warning',trans('admin.E-mail is required and must be is E-mail'));
                return redirect()->back()->withInput();
            }
            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [$locale . '.name' =>['required','string'],
                    'email'=>'required|email|unique:members',
                    'status'=>'required|in:1,0',
                    'password'=>'required|min:3',
                    'image'=>'required|'.validateImage(),

                    'birthday'=>'required|date',
                    'ssn'=>'required|unique:members',
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
                $filename = uploadImages($request->image,'member/','');
            }
            $multiple_filenames = '' ;

            if($request->file('attachments'))
            {
                $multiple_filenames = MultipleUploadImages($request->attachments,'member/','');
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

            $create = Member::create($requestData);
            $update = Member::where('id',$create->id)->update(['number'=>$create->id]);

            // insert attachments
            foreach($multiple_filenames as $file_name  ){
                DB::table('attachments')->insert([
                    'user_id'=>$create->id,
                    'file_name'=> $file_name,
                    'type' =>'member'
                    ]);
            }



            session()->flash('success',trans('admin.Data has been added successfully'));

            return redirect()->route('company.member.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try{

            $member = Member::find($id);
            checkData($member);
            return view('backend.member.edit',compact('member'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try{
            $member = Member::find($request->id);
            $admin =  checkEmail('admins',$request->get('email'));
            $company = checkEmail('companies',$request->get('email'));
            $userVendor = checkEmail('user_vendors',$request->get('email'));
            $supervisor = checkEmail('user_vendors',$request->get('email'));
            $driver = checkEmail('drivers',$request->get('email'));
            if (!empty($admin) || !empty($company) || !empty($userVendor)
                || !empty($supervisor) || !empty($driver)  || empty($member))
            {
                session()->flash('warning',trans('admin.E-mail is required and must be is E-mail'));
                return redirect()->back()->withInput();
            }
            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [$locale . '.name' =>['required','string'],
                    'email'=>'required|email|unique:members,email,'.$request->id,
                    'status'=>'required|in:1,0',
                    'image'=>validateImage(),

                    'birthday'=>'required|date',
                    'ssn'=>'required|unique:members,ssn,'.$request->id,
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
                $filename = uploadImages($request->image,'member/',$member->image);
                $requestData['image'] = $filename;
            }
            else
            {
                $requestData['image'] = $member->image;
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
            $member->update($requestData);
            session()->flash('success',trans('admin.Data has been updated successfully'));

            return redirect()->route('company.member.index');


        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try{

            $member = Member::find($id);

            checkData($member);
            DeleteImage(public_path('upload/member/'.$member->image));
            DeleteMultipleImage($id, 'member');
            $member->delete();
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

            $member = Member::find($id);

            checkData($member);
            if ($member->status == 0)
            {
                \DB::table('members')->where('id','=', $id)
                    ->update(['status' => 1]);

            }
            else
            {
                \DB::table('members')->where('id','=', $id)
                    ->update(['status' => 0]);

            }
            session()->flash('success',trans('admin.Status changed successfully'));

            return redirect()->route('company.member.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }
}
