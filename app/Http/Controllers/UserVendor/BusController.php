<?php

namespace App\Http\Controllers\UserVendor;

use App\Bus;
use App\Carrier;
use App\Driver;
use App\User;
use App\Guide;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class BusController extends Controller
{
    public function __construct()
    {
        $this->middleware('user_vendor');
        $this->middleware(['auth:user_vendor','permission:read_buses'])->only('index');
        $this->middleware(['auth:user_vendor','permission:create_buses'])->only('create','store');
        $this->middleware(['auth:user_vendor','permission:update_buses'])->only('edit','update');
        $this->middleware(['auth:user_vendor','permission:delete_buses'])->only('status','delete');
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
            $bus = Bus::where('company_id',$userId)
                ->orderBy('created_at','desc')->get();

            return view('backend.bus.index',compact('bus'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function create()
    {
        try{
            $userId = \Auth::guard('user_vendor')->user()->company_id;
            $driver = Driver::where('status',1)->where('company_id',$userId)->get();
            $carrier = Carrier::where('status',1)->where('company_id',$userId)->get();
            $guides = Guide::where('status',1)->where('company_id',$userId)->get();
            return view('backend.bus.create',compact('driver','carrier','guides'));


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
                    'number_bus'=>'required|numeric|unique:buses',
                    'plate_number'=>'required|numeric|unique:buses',
                    'number_chairs'=>'required|numeric',
                    'status'=>'required|in:1,0',
                    'driver_id'=>['required','exists:drivers,id',],
                    'carrier_id'=>['required','exists:carriers,id',],
                ];

            }
            foreach (config('translatable.locales') as $locale)
            {
                $message += [$locale . '.name.required' =>trans('admin.'.$locale.'.nameRequired'),
                    'number_bus.required' =>trans('admin.The number bus is required'),
                    'plate_number.required' =>trans('admin.The plate number bus is required'),
                    'number_chairs.required' =>trans('admin.The number chairs is required'),
                    'status.required' =>trans('admin.Status is required'),
                    'driver_id.required' =>trans('admin.Driver name is required'),
                    'carrier_id.required' =>trans('admin.Carrier name is required'),
                ];

            }

            $validator = \Validator::make($request->all(), $rules, $message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $requestData = $request->except(['_token']);
            $guide = Guide::find(request('guide_id'));
            $requestData['company_id'] = $guide->company_id ; 
            $requestData['userVendor'] = $guide->userVendor ; 
            

            // if (\Auth::guard('company')->user())
            // {
            //     $requestData['company_id'] = \Auth::guard('company')->user()->id;

            // }
            // elseif (\Auth::guard('user_vendor')->user())
            // {
            //     $requestData['company_id'] = \Auth::guard('user_vendor')->user()->company_id;
            //     $requestData['userVendor'] = \Auth::guard('user_vendor')->user()->name.'| '
            //         .\Auth::guard('user_vendor')->user()->email;

            // }

            Bus::create($requestData);

            session()->flash('success',trans('admin.Data has been added successfully'));

            return redirect()->route('user.vendor.bus.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try{

            $userId = \Auth::guard('user_vendor')->user()->company_id;
            $driver = Driver::where('company_id',$userId)->get();
            $carrier = Carrier::where('company_id',$userId)->get();
            $bus = Bus::find($id);
            checkData($bus);
            return view('backend.bus.edit',compact('bus','driver','carrier'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try{
            $bus = Bus::find($request->id);

            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [$locale . '.name' =>['required','string'],
                    'number_bus'=>'required|numeric|unique:buses,number_bus,'.$request->id,
                    'plate_number'=>'required|numeric|unique:buses,plate_number,'.$request->id,
                    'number_chairs'=>'required|numeric',
                    'status'=>'required|in:1,0',
                    'driver_id'=>['required','exists:drivers,id',],
                    'carrier_id'=>['required','exists:carriers,id',],
                ];

            }
            foreach (config('translatable.locales') as $locale)
            {
                $message += [$locale . '.name.required' =>trans('admin.'.$locale.'.nameRequired'),
                    'number_bus.required' =>trans('admin.The number bus is required'),
                    'plate_number.required' =>trans('admin.The plate number bus is required'),
                    'number_chairs.required' =>trans('admin.The number chairs is required'),
                    'status.required' =>trans('admin.Status is required'),
                    'driver_id.required' =>trans('admin.Driver name is required'),
                    'carrier_id.required' =>trans('admin.Carrier name is required'),
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
            $bus->update($requestData);
            session()->flash('success',trans('admin.Data has been updated successfully'));

            return redirect()->route('user.vendor.bus.index');


        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        try{

            $bus = Bus::find($id);

            checkData($bus);
            $bus->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('user.vendor.bus.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function status($id)
    {
        try{

            $bus = Bus::find($id);

            checkData($bus);
            if ($bus->status == 0)
            {
                \DB::table('buses')->where('id','=', $id)
                    ->update(['status' => 1]);

            }
            else
            {
                \DB::table('buses')->where('id','=', $id)
                    ->update(['status' => 0]);

            }
            session()->flash('success',trans('admin.Status changed successfully'));

            return redirect()->route('user.vendor.bus.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }
}
