<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\DataTables\AdminDataTable;
use App\Http\Requests\AdminRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');

        $this->middleware(['auth:admin','permission:read_admins'])->only('index');
        $this->middleware(['auth:admin','permission:create_admins'])->only('create','store');
        $this->middleware(['auth:admin','permission:update_admins'])->only('edit','update');
        $this->middleware(['auth:admin','permission:delete_admins'])->only('delete');
    }

    public function index(AdminDataTable $adminDataTable)
    {
        try{

            return $adminDataTable->render('backend.admin.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function create()
    {
        try{
            return view('backend.admin.create');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function store(AdminRequest $adminRequest)
    {

       try{
            if($adminRequest->file('image'))
            {
                $filename = uploadImages($adminRequest->image,'admin/','');
            }
            $admin = new Admin();
            $admin->firstName = $adminRequest->get('firstName');
            $admin->lastName = $adminRequest->get('lastName');
            $admin->email = $adminRequest->get('email');
            $admin->password = \Hash::make($adminRequest->get('password'));
            $admin->phone = $adminRequest->get('phone');
            $admin->status = $adminRequest->get('status');
            $admin->address = $adminRequest->get('address');
            $admin->image = $filename;
            $admin->user_token = Str::random(60);
            $admin->save();

            if ($admin->save())
            {
                $admin->attachRole('admin');
                $admin->syncPermissions($adminRequest->get('permission'));
                session()->flash('success',trans('admin.Data has been added successfully'));

                return redirect()->route('admin.admin.index');
            }
            else
            {
                session()->flash('warning',trans('admin.Please try again'));
                return redirect()->back()->withInput();
            }

          /* $requestData = $adminRequest->except(['password','password_confirmation','permission','image']);



           if ($adminRequest->image)
           {
               $filename = uploadImages($adminRequest->image,'admin/','');

           }
           else
           {
               session()->flash('error',trans('admin.Please upload image'));
               return redirect()->back()->withInput();
           }
           $requestData['image'] = $filename;
           $requestData['password'] = bcrypt($adminRequest->get('password'));
           $requestData['user_token'] = Str::random(30);

           //dd($requestData);
           //dd($requestData,$adminRequest->all());
           $admin = Admin::create($requestData);
           $admin->attachRole('admin');
           $admin->syncPermissions($adminRequest->get('permission'));

           session()->flash('success',trans('admin.Data has been added successfully'));

           return redirect()->route('admin.admin.index');*/

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        try{
            $admin = Admin::find($id);
            checkData($admin);
            return view('backend.admin.edit',compact('admin'));


        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }


    public function update(Request $request)
    {
        try{
            $admin = Admin::find($request->id);


            checkData($admin);

            $rule = [
                    'firstName'=>'required|string|min:3',
                    'lastName'=>'required|string|min:3',
                    'email'=>'required|email|unique:admins,email,'.$request->id,
                    'phone'=>'required|numeric',
                    'status'=>'required|in:1,2',
                    'address'=>'required|string',
                    'permission'=>'required|min:1',
                    'image' => validateImage()
                ];

            $message = [
                        'firstName.required' => trans('admin.First name is required'),
                        'lastName.required' => trans('admin.Last name is required'),
                        'email.required' => trans('admin.E-mail is required and must be is E-mail'),
                        'Phone.required' => trans('admin.Phone is required'),
                        'status.required' => trans('admin.Status is required'),
                        'address.required' => trans('admin.Address is required'),
                        'permission.required' => trans('admin.Permission is required and select min one'),
                        'image' => trans('admin.Image must be jpg,jpeg,png,bmp'),
                    ];

            $validator = Validator::make($request->all(), $rule ,$message);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            if($request->file('image'))
            {
                $filename = uploadImages($request->image,'admin/',$admin->image);
            }
            else
            {
                $filename = $admin->image;
            }

            $admin->firstName = $request->get('firstName');
            $admin->lastName = $request->get('lastName');
            $admin->email = $request->get('email');
            $admin->phone = $request->get('phone');
            $admin->status = $request->get('status');
            $admin->address = $request->get('address');
            $admin->image = $filename;
            $admin->save();

            if ($admin->save())
            {
                $admin->syncPermissions($request->get('permission'));
                session()->flash('success',trans('admin.Data has been added successfully'));

                return redirect()->route('admin.admin.index');
            }
            else
            {
                session()->flash('warning',trans('admin.Please try again'));
                return redirect()->back()->withInput();
            }

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try{

            $admin = Admin::find($id);

            checkData($admin);

            $admin->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.admin.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function multiDelete()
    {
        try{

            $admin = Admin::find(\request('item'));
            checkData($admin);
            if (is_array(\request('item')))
            {
                Admin::destroy(\request('item'));
            }
            else
            {
                Admin::find(\request('item'))->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.admin.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }




}
