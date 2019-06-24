<?php

namespace App\Http\Controllers\UserVendor;

use App\Carrier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarrierController extends Controller
{
    public function __construct()
    {
        $this->middleware('user_vendor');

        $this->middleware(['auth:company','permission:read_carrier'])->only('index');
        $this->middleware(['auth:company','permission:create_carrier'])->only('create','store');
        $this->middleware(['auth:company','permission:update_carrier'])->only('edit','update');
        $this->middleware(['auth:company','permission:delete_carrier'])->only('status','delete');

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
            $carrier = Carrier::where('company_id',$userId)
                ->orderBy('created_at','desc')->get();

            return view('backend.carrier.index',compact('carrier'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function create()
    {
        try{
            return view('backend.carrier.create');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try{

            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [$locale . '.name' =>['required','string'],
                    'status'=>'required|in:1,0',
                ];

            }
            foreach (config('translatable.locales') as $locale)
            {
                $message += [$locale . '.name.required' =>trans('admin.'.$locale.'.nameRequired'),
                    'status.required' =>trans('admin.Status is required'),
                ];
            }

            $validator = \Validator::make($request->all(), $rules, $message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $requestData = $request->except(['_token']);
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

            Carrier::create($requestData);

            session()->flash('success',trans('admin.Data has been added successfully'));

            return redirect()->route('user.vendor.carrier.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try{

            $carrier = Carrier::find($id);
            checkData($carrier);
            return view('backend.carrier.edit',compact('carrier'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try{
            $carrier = Carrier::find($request->id);

            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [$locale . '.name' =>['required','string'],
                    'status'=>'required|in:1,0',
                ];

            }
            foreach (config('translatable.locales') as $locale)
            {
                $message += [$locale . '.name.required' =>trans('admin.'.$locale.'.nameRequired'),
                    'status.required' =>trans('admin.Status is required'),
                ];

            }

            $validator = \Validator::make($request->all(), $rules, $message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
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

            $requestData = $request->except(['_token']);
            $carrier->update($requestData);
            session()->flash('success',trans('admin.Data has been updated successfully'));

            return redirect()->route('user.vendor.carrier.index');


        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        try{

            $carrier = Carrier::find($id);

            checkData($carrier);
            $carrier->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('user.vendor.carrier.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function status($id)
    {
        try{

            $carrier = Carrier::find($id);

            checkData($carrier);
            if ($carrier->status == 0)
            {
                \DB::table('carriers')->where('id','=', $id)
                    ->update(['status' => 1]);

            }
            else
            {
                \DB::table('carriers')->where('id','=', $id)
                    ->update(['status' => 0]);

            }
            session()->flash('success',trans('admin.Status changed successfully'));

            return redirect()->route('user.vendor.carrier.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }
}
