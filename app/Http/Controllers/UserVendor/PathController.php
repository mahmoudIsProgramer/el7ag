<?php

namespace App\Http\Controllers\UserVendor;

use App\Destination;
use App\Path;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PathController extends Controller
{
    public function __construct()
    {
        $this->middleware('user_vendor');

        $this->middleware(['auth:company','permission:read_path'])->only('index');
        $this->middleware(['auth:company','permission:create_path'])->only('create','store');
        $this->middleware(['auth:company','permission:update_path'])->only('edit','update');
        $this->middleware(['auth:company','permission:delete_path'])->only('status','delete');
    }

    public function index()
    {
        try{
            $userId = \Auth::guard('company')->user()->id;

            $path = Path::where('company_id',$userId)
                ->orderBy('created_at','desc')->get();

            return view('backend.path.index',compact('path'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function create()
    {
        try{
            if (\request()->ajax())
            {
                if (\request()->has('destination_id'))
                {
                    $select = \request()->has('select') ? \request('select') : '' ;

                    return \Form::select('to',
                        \App\DestinationTranslation::join('destinations','destination_translations.destination_id','=','destinations.id')
                            ->where('destinations.id','<>',\request('destination_id'))
                            ->where('destination_translations.locale',\App::getLocale())
                            ->pluck('destination_translations.name','destinations.id')
                        ,
                        $select,['class'=>'form-control select2','style'=>'width: 100%;','placeholder'=>trans('admin.Select destination to name')]) ;

                }
            }
            $userId = \Auth::guard('company')->user()->id;
            $destination = Destination::where('status',1)->where('company_id',$userId)->get();
            return view('backend.path.create',compact('destination'));

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

            $rules += [
                'status'=>'required|in:1,0',
                'price'=>'required|regex:/^\d*(\.\d{1,2})?$/|min:1',
                'from'=>['required','exists:destinations,id',],
                'to'=>['required','exists:destinations,id',],
            ];

            $message += [
                'status.required' =>trans('admin.Status is required'),
                'price.required' =>trans('admin.Price is required'),
                'from.required' =>trans('admin.From Destination is required'),
                'to.required' =>trans('admin.To Destination is required'),
            ];

            $validator = \Validator::make($request->all(), $rules, $message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $path = new Path();
            $path->company_id = \Auth::guard('company')->user()->id;
            $path->from = $request->from;
            $path->to = $request->to;
            $path->price = $request->price;
            $path->status = $request->status;
            $path->Save();
            if ($path->save())
            {
                session()->flash('success',trans('admin.Data has been added successfully'));

                return redirect()->route('company.path.index');
            }
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try{

            $path = Path::find($id);
            checkData($path);
            $userId = \Auth::guard('company')->user()->id;
            $destination = Destination::where('company_id',$userId)->get();
            return view('backend.path.edit',compact('path','destination'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try{
            $path = Path::find($request->id);
            $rules = $message = [];

            $rules += [
                'status'=>'required|in:1,0',
                'price'=>'required|regex:/^\d*(\.\d{1,2})?$/|min:1',
                'from'=>['required','exists:destinations,id',],
                'to'=>['required','exists:destinations,id',],
            ];

            $message += [
                'status.required' =>trans('admin.Status is required'),
                'price.required' =>trans('admin.Price is required'),
                'from.required' =>trans('admin.From Destination is required'),
                'to.required' =>trans('admin.To Destination is required'),
            ];


            $validator = \Validator::make($request->all(), $rules, $message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $path->from = $request->from;
            $path->to = $request->to;
            $path->price = $request->price;
            $path->status = $request->status;
            $path->Save();
            if ($path->Save())
            {
                session()->flash('success',trans('admin.Data has been updated successfully'));
                return redirect()->route('company.path.index');
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

            $path = Path::find($id);

            checkData($path);
            $path->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('company.path.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function status($id)
    {
        try{

            $path = Path::find($id);

            checkData($path);
            if ($path->status == 0)
            {
                \DB::table('paths')->where('id','=', $id)
                    ->update(['status' => 1]);

            }
            else
            {
                \DB::table('paths')->where('id','=', $id)
                    ->update(['status' => 0]);

            }
            session()->flash('success',trans('admin.Status changed successfully'));

            return redirect()->route('company.path.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }
}
