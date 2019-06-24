<?php

namespace App\Http\Controllers\Supervisor;

use App\Bus;
use App\Driver;
use App\Guide;
use App\Member;
use App\Path;
use App\Supervisor;
use App\Trip;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class TripController extends Controller
{
    public function __construct()
    {
        $this->middleware('supervisor');

    }

    public function index()
    {
        try{
            if (\Auth::guard('supervisor')->user())
            {
                $userId = \Auth::guard('supervisor')->user();

            }
            $trip = Trip::where('supervisor_id',$userId->id)
                ->orderBy('created_at','desc')->get();

            return view('supervisor.trip.index',compact('trip'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function create()
    {
        try{

            if (\Auth::guard('supervisor')->user())
            {
                $userId = \Auth::guard('supervisor')->user();

            }
            if (\request()->ajax())
            {
                if (\request()->has('driver_id'))
                {
                    $select = \request()->has('select') ? \request('select') : '' ;

                    return \Form::select('bus_id',
                        \App\BusTranslation::join('buses','bus_translations.bus_id','=','buses.id')
                            ->where('buses.driver_id',\request('driver_id'))
                            ->where('bus_translations.locale',\App::getLocale())
                            ->pluck('bus_translations.name','buses.id')
                        ,
                        $select,['class'=>'form-control select2','style'=>'width: 100%;','placeholder'=>trans('admin.Select bus name')]) ;

                }
                if (\request()->has('path_id'))
                {
                    return \Form::number('price',
                        \App\Path::find(\request('path_id'))->price,
                        ['id'=>'exampleInputEmail1','class'=>'form-control','min'=>0,'step'=>0.1]) ;
                }
            }

            $guide = Guide::where('status',1)->where('company_id',$userId->company_id)->orderBy('created_at','desc')->get();
            $driver = Driver::where('status',1)->where('company_id',$userId->company_id)->orderBy('created_at','desc')->get();
            $bus = Bus::where('status',1)->where('company_id',$userId->company_id)->orderBy('created_at','desc')->get();
            $path = Path::where('status',1)->where('company_id',$userId->company_id)->orderBy('created_at','desc')->get();
            return view('supervisor.trip.create',compact('guide','driver','bus','path'));

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $date_now =  date('Y-m-d H:i:s');
        try{

            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [$locale . '.name' =>['required','string'],
                    'guide_id'=>['required','exists:guides,id',],
                    'path_id'=>'required|exists:paths,id',
                    'driver_id'=>'required|exists:drivers,id',
                    'bus_id'=>'required|exists:buses,id',
                    'number_passenger'=>'required|integer',
                    // 'start_time'=>'required|date_format:H:i',
                    // 'end_time'=>'required|date_format:H:i',
                    'start_date'=>'required|after:yesterday|before_or_equal:end_date|after:'.$date_now,
                    'end_date'=>'required|after:yesterday|after:start_date|after:'.$date_now,
                    'status'=>'required|in:1,2,3,7,10',
                    'price'=>'required|',
                ];

            }

            foreach (config('translatable.locales') as $locale)
            {
                $message += [$locale . '.name.required' =>trans('admin.'.$locale.'.nameRequired'),
                    'guide_id.required' =>trans('admin.Guide name is required'),
                    'path_id.required' =>trans('admin.Path name is required'),
                    'driver_id.required' =>trans('admin.Driver name is required'),
                    'bus_id.required' =>trans('admin.Bus name is required'),
                    'number_passenger.required' =>trans('admin.Number passenger is required'),
                    'start_time.required' =>trans('admin.Start time trip is required'),
                    'end_time.required' =>trans('admin.End time trip is required'),
                    'start_date.required' =>trans('admin.Start date trip is required'),
                    'end_date.required' =>trans('admin.End date trip is required'),
                    'status.required' =>trans('admin.Status is required'),
                ];

            }

            $validator = \Validator::make($request->all(), $rules, $message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $buses = cdataEmpty('buses','id',$request->bus_id);

            if ($buses->number_chairs < $request->number_passenger)
            {
                session()->flash('warning',trans('admin.Number passenger bigger then number chairs bus is equal '.$buses->number_chairs));
                return redirect()->back()->withInput();
            }

            $dateStart = date('Y-m-d  h:i:s',strtotime($request->start_date));
            $dateEnd = date('Y-m-d h:i:s',strtotime($request->end_date));


            $guideTrips = CheckIfBusy($dateStart , $dateEnd  ,  'guide_id', $request->guide_id  );
            $driverTrips = CheckIfBusy($dateStart , $dateEnd  ,  'driver_id', $request->driver_id  );
            $busesTrips = CheckIfBusy($dateStart , $dateEnd  ,  'bus_id', $request->bus_id  );

            if ($guideTrips == true)
            {
                session()->flash('warning',trans('admin.this guide in trip now select date different') .date('d-m-Y',strtotime($request->end_date)));
                return redirect()->back()->withInput();
            }

            if ($driverTrips == true)
            {
                session()->flash('warning',trans('admin.this driver in trip now select date different') .date('d-m-Y',strtotime($request->end_date)));
                return redirect()->back()->withInput();
            }
            if ($busesTrips == true)
            {
                session()->flash('warning',trans('admin.this bus in trip now select date different') .date('d-m-Y',strtotime($request->end_date)));
                return redirect()->back()->withInput();
            }


            $requestData = $request->except(['_token']);


            if (\Auth::guard('supervisor')->user())
            {
                $requestData['company_id'] = \Auth::guard('supervisor')->user()->company_id;
                $requestData['supervisor_id'] = \Auth::guard('supervisor')->user()->id;

            }
            Trip::create($requestData);

            session()->flash('success',trans('admin.Data has been added successfully'));

            return redirect()->route('supervisor.trip.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        try{
            if (\Auth::guard('supervisor')->user())
            {
                $userId = \Auth::guard('supervisor')->user();

            }

            $trip = Trip::find($id);
            checkData($trip);
            $path = Path::where('company_id',$userId->company_id)->orderBy('created_at','desc')->get();
            $guide = Guide::where('company_id',$userId->company_id)->orderBy('created_at','desc')->get();
            $driver = Driver::where('company_id',$userId->company_id)->orderBy('created_at','desc')->get();
            $bus = Bus::where('company_id',$userId->company_id)->orderBy('created_at','desc')->get();
            return view('supervisor.trip.edit',
                compact('guide','path','driver','bus','trip'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request)
    {
        try{
            $trip = Trip::find($request->id);

            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [$locale . '.name' =>['required','string'],
                    'guide_id'=>['required','exists:guides,id',],
                    'path_id'=>'required|exists:paths,id',
                    'driver_id'=>'required|exists:drivers,id',
                    'bus_id'=>'required|exists:buses,id',
                    'number_passenger'=>'required|integer',
                    'start_time'=>'required',
                    'end_time'=>'required',
                    'start_date'=>'required|date_format:Y-m-d|before_or_equal:end_date',
                    'end_date'=>'required|date_format:Y-m-d|after_or_equal:start_date',
                    'status'=>'required|in:1,2,3,4,5,6,7,10',
                    'price'=>'required|',
                ];

            }
            foreach (config('translatable.locales') as $locale)
            {
                $message += [$locale . '.name.required' =>trans('admin.'.$locale.'.nameRequired'),
                    'guide_id.required' =>trans('admin.Guide name is required'),
                    'path_id.required' =>trans('admin.Path name is required'),
                    'driver_id.required' =>trans('admin.Driver name is required'),
                    'bus_id.required' =>trans('admin.Bus name is required'),
                    'number_passenger.required' =>trans('admin.Number passenger is required'),
                    'start_time.required' =>trans('admin.Start time trip is required'),
                    'end_time.required' =>trans('admin.End time trip is required'),
                    'start_date.required' =>trans('admin.Start date trip is required'),
                    'end_date.required' =>trans('admin.End date trip is required'),
                    'status.required' =>trans('admin.Status is required'),
                ];

            }

            $validator = \Validator::make($request->all(), $rules, $message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $buses = cdataEmpty('buses','id',$request->bus_id);

            if ($buses->number_chairs < $request->number_passenger)
            {
                session()->flash('warning',trans('admin.Number passenger bigger then number chairs bus is equal '.$buses->number_chairs));
                return redirect()->back()->withInput();
            }

            $guideTrips = checkTripsUpdate('trips','id',[$request->id]);

            $memberTrips = checkTripsUpdate('trips','id',[$request->id]);
            $driverTrips = checkTripsUpdate('trips','id',[$request->id]);
            $busesTrips = checkTripsUpdate('trips','id',[$request->id]);


            $getGuide = checkTripsForLoop($guideTrips,$request->end_date);
            if ($getGuide == true)
            {
                session()->flash('warning',trans('admin.this guide in trip now select date different') .date('d-m-Y',strtotime($request->end_date)));
                return redirect()->back()->withInput();
            }
            $getMember = checkTripsForLoop($memberTrips,$request->end_date);

            if ($getMember == true)
            {
                session()->flash('warning',trans('admin.this guide in trip now select date different') .date('d-m-Y',strtotime($request->end_date)));
                return redirect()->back()->withInput();
            }
            $getDriver = checkTripsForLoop($driverTrips,$request->end_date);
            if ($getDriver == true)
            {
                session()->flash('warning',trans('admin.this guide in trip now select date different') .date('d-m-Y',strtotime($request->end_date)));
                return redirect()->back()->withInput();
            }
            $getBus = checkTripsForLoop($busesTrips,$request->end_date);
            if ($getBus == true)
            {
                session()->flash('warning',trans('admin.this guide in trip now select date different') .date('d-m-Y',strtotime($request->end_date)));
                return redirect()->back()->withInput();
            }

            $requestData = $request->except(['_token']);

            $trip->update($requestData);
            session()->flash('success',trans('admin.Data has been updated successfully'));

            return redirect()->route('supervisor.trip.index');


        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function destroy($id)
    {
        try{

            $trip = Trip::find($id);

            checkData($trip);
            $trip->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('supervisor.trip.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function status($id)
    {
        try{

            $trip = Trip::find($id);

            checkData($trip);
            if ($trip->status == 0)
            {
                \DB::table('trips')->where('id','=', $id)
                    ->update(['status' => 1]);

            }
            else
            {
                \DB::table('trips')->where('id','=', $id)
                    ->update(['status' => 0]);

            }
            session()->flash('success',trans('admin.Status changed successfully'));

            return redirect()->route('supervisor.trip.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
