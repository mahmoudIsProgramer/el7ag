<?php

namespace App\Http\Controllers\UserVendor;

use App\Destination;
use App\Guide;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DestinationController extends Controller
{
    public function __construct()
    {
        $this->middleware('user_vendor');

        $this->middleware(['auth:company','permission:read_destination'])->only('index');
        $this->middleware(['auth:company','permission:create_destination'])->only('create','store');
        $this->middleware(['auth:company','permission:update_destination'])->only('edit','update');
        $this->middleware(['auth:company','permission:delete_destination'])->only('status','delete');
    }

    public function index()
    {
        try{
            $userId = \Auth::guard('company')->user()->id;

            $destination = Destination::where('company_id',$userId)
                ->orderBy('created_at','desc')->get();

            return view('backend.destination.index',compact('destination'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function create()
    {
        try{
            return view('backend.destination.create');

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
                $rules += [
                    $locale . '.name' =>['required','string'],
                    'status'=>'required|in:1,0',
                    'lat'=>['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
                    'lng'=>['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
                ];

                $message += [
                    $locale . '.name.required' =>trans('admin.'.$locale.'.DesName'),
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
            Destination::create($request->except('_token'));

            session()->flash('success',trans('admin.Data has been added successfully'));

            return redirect()->route('user.vendor.destination.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try{

            $destination = Destination::find($id);
            checkData($destination);
            return view('backend.destination.edit',compact('destination'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try{
            $destination = Destination::find($request->id);
            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [
                    $locale . '.name' =>['required','string'],
                    'status'=>'required|in:1,0',
                    'lat'=>['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
                    'lng'=>['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],

                ];

                $message += [
                    $locale . '.from_name.required' =>trans('admin.'.$locale.'.DesName'),
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
            $destination->update($request->except('_token'));
            session()->flash('success',trans('admin.Data has been updated successfully'));

            return redirect()->route('user.vendor.destination.index');


        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try{

            $destination = Destination::find($id);

            checkData($destination);
            $destination->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('user.vendor.destination.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function status($id)
    {
        try{

            $destination = Destination::find($id);

            checkData($destination);
            if ($destination->status == 0)
            {
                \DB::table('destinations')->where('id','=', $id)
                    ->update(['status' => 1]);

            }
            else
            {
                \DB::table('destinations')->where('id','=', $id)
                    ->update(['status' => 0]);

            }
            session()->flash('success',trans('admin.Status changed successfully'));

            return redirect()->route('user.vendor.destination.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }
}
