<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\DataTables\CompanyDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Permission; 

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');

        $this->middleware(['auth:admin','permission:read_company'])->only('index');
        $this->middleware(['auth:admin','permission:create_company'])->only('create','store');
        $this->middleware(['auth:admin','permission:update_company'])->only('edit','update');
        $this->middleware(['auth:admin','permission:delete_company'])->only('delete');
    }


    public function index()
    {

        try{
            $company = Company::orderBy('created_at','desc')->get();

            return view('backend.company.index',compact('company'));

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function create()
    {
        try{
            return view('backend.company.create');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try{
           $check =  checkEmail('admins',$request->get('email'));
           if (!empty($check))
           {
               session()->flash('warning',trans('admin.E-mail is required and must be is E-mail'));
               return redirect()->back()->withInput();
           }
            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [$locale . '.name' =>['required','string'],
                    'email'=>'required|email|unique:companies',
                    'number'=>'required|unique:companies',
                    'status'=>'required|in:1,0',
                    'password'=>'required|min:3',
                    'image'=>'required|'.validateImage(),
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

                ];

            }

            $validator = \Validator::make($request->all(), $rules, $message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $requestData = $request->except(['_token','image','password']);
            if($request->file('image'))
            {
                $filename = uploadImages($request->image,'company/','');
            }
            $requestData['image'] = $filename;
            $requestData['user_token'] = Str::random(60);
            $requestData['password'] = \Hash::make($request->password);
            // dd($request->all()); 


           $company = Company::create($requestData);

            $company->attachRole('vendor');
            $company->syncPermissions(
                [
                    0 => 'create_vendors',
                    1 => 'read_vendors',
                    2 => 'update_vendors',
                    3 => 'delete_vendors',

                    4 => 'create_guides',
                    5 => 'read_guides',
                    6 => 'update_guides',
                    7 => 'delete_guides',

                    8 => 'create_drivers',
                    9 => 'read_drivers',
                    10 => 'update_drivers',
                    11 => 'delete_drivers',

                    12 => 'create_supervisors',
                    13 => 'read_supervisors',
                    14 => 'update_supervisors',
                    15 => 'delete_supervisors',

                    16 => 'create_members',
                    17 => 'read_members',
                    18 => 'update_members',
                    19 => 'delete_members',
                ]
            );

            session()->flash('success',trans('admin.Data has been added successfully'));

            return redirect()->route('admin.company.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        try{

            $company = Company::find($id);
            checkData($company);
            return view('backend.company.edit',compact('company'));


        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }


    public function update(Request $request)
    {
        try{
            $company = Company::find($request->id);
            checkData($company);

            $check =  checkEmail('admins',$request->get('email'));
            if (!empty($check))
            {
                session()->flash('warning',trans('admin.E-mail is required and must be is E-mail'));
                return redirect()->back()->withInput();
            }
            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [$locale . '.name' =>['required','string'],
                    'email'=>'required|email|unique:companies,email,'.$request->id,
                    'number'=>'required|unique:companies,number,'.$request->id,
                    'status'=>'required|in:1,0',
                    'image'=>validateImage(),
                ];

            }
            foreach (config('translatable.locales') as $locale)
            {
                $message += [$locale . '.name.required' =>trans('admin.'.$locale.'.nameRequired'),
                    'email.required' =>trans('admin.E-mail is required and must be is E-mail'),
                    'number.required' =>trans('admin.Number is required and must be is Number'),

                    'status.required' =>trans('admin.Status is required'),
                    'image.required' =>trans('admin.Image must be is extension is jpg,jpeg,png,bmp'),
                    ];

            }

            $validator = \Validator::make($request->all(), $rules, $message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $requestData = $request->except(['_token','image']);
            if($request->file('image'))
            {
                $filename = uploadImages($request->image,'company/',$company->image);
                $requestData['image'] = $filename;
            }
            else
            {
                $requestData['image'] = $company->image;
            }
            $company->update($requestData);
            session()->flash('success',trans('admin.Data has been added successfully'));

            return redirect()->route('admin.company.index');



        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try{

            $company = Company::find($id);

            checkData($company);
            if ($company->status == 0)
            {
                \DB::table('companies')->where('id','=', $id)
                    ->update(['status' => 1]);

            }
            else
            {
                 \DB::table('companies')->where('id','=', $id)
                    ->update(['status' => 0]);

            }
            session()->flash('success',trans('admin.Status changed successfully'));

            return redirect()->route('admin.company.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

}
