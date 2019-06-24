<?php

namespace App\Http\Controllers\Company;

use App\Bus;
use App\Driver;
use App\Guide;
use App\Member;
use App\Path;
use App\Supervisor;
use App\Trip;
use App\User;
use App\Carrier;
use App\CarrierPath;
use Edujugon\PushNotification\PushNotification;



// use Calendar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class TripController extends Controller
{
    public function __construct()
    {
        $this->middleware('company');

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
            $trip = Trip::where('company_id',$userId)
                ->orderBy('created_at','desc')->get();

            return view('backend.trip.index',compact('trip'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function create()
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

            if (\request()->ajax())
            {
                // start drivers 
                if ( \request()->has('bus_id') &&  \request()->get('get_users') == 'drivers' )   
                {
                    $driver_id  = Bus::where('id',request('bus_id'))->where('company_id',$userId)->value('driver_id');
                    $select_driver  = ( \request()->has('select') && \request()->get('select') != '' ) ? \request()->get('select')  :  $driver_id ;  

                    return \Form::select('driver_id',
                    \App\DriverTranslation::join('drivers','driver_translations.driver_id','=','drivers.id')
                        ->where('driver_translations.locale',\App::getLocale())
                        ->where('company_id',$userId)
                        ->pluck('driver_translations.name','drivers.id')
                    ,
                    $select_driver ,['required' => true,'class'=>'form-control select2 driver_id','style'=>'width: 100%;' ,'placeholder'=>trans('admin.Select bus name')]) ;
                }

                // start carriers 
                if( \request()->has('bus_id')  &&  \request()->get('get_users') == 'carriers'  ){
                   
                    $carrier_id  = Bus::where('id',request('bus_id'))->where('company_id',$userId)->value('carrier_id');
                    $select_carrier  = ( \request()->has('select') && \request()->get('select') != '' ) ? \request()->get('select')  :  $carrier_id ;  
                    return \Form::select('carrier_id',
                    \App\CarrierTranslation::join('carriers','carrier_translations.carrier_id','=','carriers.id')
                        ->where('carrier_translations.locale',\App::getLocale())
                        ->where('company_id',$userId)
                        ->where('carriers.id' , $carrier_id  )
                        ->pluck('carrier_translations.name','carriers.id')
                    ,
                    $select_carrier ,['required' => true,'class'=>'form-control select2 carrier_id','style'=>'width: 100%;' ,'placeholder'=>trans('admin.Select bus name')]) ;
                }
                    
                // start  guides 
                if( \request()->has('bus_id') &&  \request()->get('get_users') == 'guides' ){
                    $guide_id  = Bus::where('id',request('bus_id'))->where('company_id',$userId)->value('guide_id');
                    $select_guide  = ( \request()->has('select') && \request()->get('select') != '' ) ? \request()->get('select') :  $guide_id ;  

                    return \Form::select('guide_id',
                    \App\GuideTranslation::join('guides','guide_translations.guide_id','=','guides.id')
                        ->where('guide_translations.locale',\App::getLocale())
                        ->where('company_id',$userId)
                        ->pluck('guide_translations.name','guides.id')
                    ,
                    $select_guide ,['required' => true,'class'=>'form-control select2 guide_id','style'=>'width: 100%;' ,'placeholder'=>trans('admin.Select bus name') ]) ;

                }
                // start supervisors 
                if(  \request()->has('bus_id')   && \request()->get('get_users') == 'supervisors' ){
                    $guide_id  = Bus::where('id',request('bus_id'))->where('company_id',$userId)->value('guide_id');
                    $supervisor_id   = Guide::where('id', $guide_id )->value('supervisor_id');
                    $select_supervisor  = ( \request()->has('select') && \request()->get('select') != '' ) ? \request()->get('select')  :  $supervisor_id ;  
                    return \Form::select('supervisor_id',
                    \App\SupervisorTranslation::join('supervisors','supervisor_translations.supervisor_id','=','supervisors.id')
                        ->where('supervisor_translations.locale',\App::getLocale())
                        ->where('company_id',$userId)
                        ->where('supervisors.id' , $supervisor_id   )
                        ->pluck('supervisor_translations.name','supervisors.id')
                    ,
                    $select_supervisor ,['required' => true,'class'=>'form-control select2 supervisor_id','style'=>'width: 100%;' ,'placeholder'=>trans('admin.Select bus name') ]) ;
                }
                //  start paths  
                if(  \request()->has('bus_id')   && \request()->get('get_users') == 'paths' ){

                    $carrier_id  = Bus::where('id',request('bus_id'))->where('company_id',$userId)->value('carrier_id');
                    // $CarrierPath_ids = CarrierPath::where('carrier_id', $carrier_id  )->pluck('path_id');

                    $paths = Path::where('company_id', $userId )->get(); 
                    if(count($paths)>0){
                        $html_select = '<select class="form-control select2 path_id" style="width: 100%;" name="path_id" required >' ; 
                        $html_select .='<option> ---select---</option>' ; 
                        foreach($paths as $value  ){
                            $html_select .= "<option  value='$value->id' >" ; 
                            
                            $html_select .=  \App\Destination::find($value->from)->translate(\App::getLocale())->name . ' | '.\App\Destination::find($value->to)->translate(\App::getLocale())->name ; 
                            $html_select .= '</option>'; 
                        }
                        $html_select .="</select>";
                        return  $html_select ;
                    }else{
                        return '' ; 
                    }
                }
                // get price 
                if (  \request()->has('path_id')    &&  \request()->has('carrier_id')  && \request()->get('get_users') == 'price'  )
                {
                    
                    $price = CarrierPath::where('carrier_id', \request()->get('carrier_id'))->where('path_id',\request()->get('path_id'))->value('price'); 
                    return \Form::number('price',
                        $price,
                        [ 'required' => true, 'id'=>'exampleInputEmail1','class'=>'form-control','min'=>0,'step'=>0.1]) ;
                }

                //  change driver and get the carrier beloing to it    
                if(  \request()->has('driver_id')   && \request()->get('get_users') == 'carrier' ){

                    $carrier_id  = Driver::where('id',request('driver_id'))->where('company_id',$userId)->value('carrier_id');
                    
                    if(isset($carrier_id)){
                        return \Form::select('carrier_id',
                    \App\CarrierTranslation::join('carriers','carrier_translations.carrier_id','=','carriers.id')
                        ->where('carrier_translations.locale',\App::getLocale())
                        ->where('company_id',$userId)
                        ->where('carriers.id' , $carrier_id  )
                        ->pluck('carrier_translations.name','carriers.id')
                    ,
                    '' ,['required' => true,'class'=>'form-control select2 carrier_id','style'=>'width: 100%;' ,'placeholder'=>trans('admin.Select bus name')]) ;

                    }else{
                        return '' ; 
                    }
                }

                //  change guides and get the suprvisor  beloing to it    
                if(  \request()->has('guide_id')   && \request()->get('get_users') == 'supervisor' ){

                    $suprvisor_id  = Guide::where('id',request('guide_id'))->where('company_id',$userId)->value('supervisor_id');
                    if(isset($suprvisor_id)){
                        return \Form::select('supervisor_id',
                    \App\SupervisorTranslation::join('supervisors','supervisor_translations.supervisor_id','=','supervisors.id')
                        ->where('supervisor_translations.locale',\App::getLocale())
                        ->where('company_id',$userId)
                        ->where('supervisors.id' ,$suprvisor_id  )
                        ->pluck('supervisor_translations.name','supervisors.id')
                    ,
                    '' ,['required' => true,'class'=>'form-control select2 supervisor_id','style'=>'width: 100%;' ,'placeholder'=>trans('admin.Select bus name')]) ;

                    }else{
                        return '' ; 
                    }
                }


                

            }
            $bus = Bus::where('status',1)->where('company_id',$userId)->orderBy('created_at','desc')->get();
            return view('backend.trip.create',compact('guide','supervisor','driver','bus','path'));

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {


        try{
            $date_now =  date('Y-m-d H:i:s');

            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [$locale . '.name' =>['required','string'],
                    'guide_id'=>['required','exists:guides,id',],
                    'path_id'=>'required|exists:paths,id',
                    'driver_id'=>'required|exists:drivers,id',
                    'supervisor_id'=>'required|exists:supervisors,id',
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
                    'supervisor_id.required' =>trans('admin.Supervisor name is required'),
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

            $requestData = $request->except(['_token','carrier_id']);


            if (\Auth::guard('company')->user())
            {
                $requestData['company_id'] = \Auth::guard('company')->user()->id;

            }
            elseif (\Auth::guard('user_vendor')->user())
            {
                $requestData['company_id'] = \Auth::guard('user_vendor')->user()->company_id;
                $requestData['user_vendor_id'] = \Auth::guard('user_vendor')->user()->id ; 
                    
            }

            Trip::create($requestData);
            $driver = Driver::where('id',$request->driver_id)->first(['firebaseToken','deviceType']);
            $guide = Guide::where('id',$request->driver_id)->first(['firebaseToken','deviceType']);
            $supervisor = Supervisor::where('id',$request->driver_id)->first(['firebaseToken','deviceType']);
            $member = Member::where('id',$request->driver_id)->first(['firebaseToken','deviceType']);
            if( $driver->firebaseToken != null ){
                $txt = 'تمت  اضافة  رحله  الي  السجل  الخاص  بك ' ; 
                send_notification( $txt  , $driver->firebaseToken , $driver->deviceType ); 
            } 
            if( $guide->firebaseToken != null ){
                $txt = 'تمت  اضافة  رحله  الي  السجل  الخاص  بك ' ; 
                send_notification( $txt  , $guide->firebaseToken , $guide->deviceType ); 
            } 
            if( $supervisor->firebaseToken != null ){
                $txt = 'تمت  اضافة  رحله  الي  السجل  الخاص  بك ' ; 
                send_notification( $txt  , $supervisor->firebaseToken , $supervisor->deviceType ); 
            } 
            

            


            session()->flash('success',trans('admin.Data has been added successfully'));

            return redirect()->route('company.trip.index');

        }catch (\Exception $exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
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

            $trip = Trip::find($id);
            // dd($trip->start_date);
            // dd($trip->end_date);
            // checkData($trip);
            $supervisor = Supervisor::where('company_id',$userId)->orderBy('created_at','desc')->get();
            $path = Path::where('company_id',$userId)->orderBy('created_at','desc')->get();
            $guide = Guide::where('company_id',$userId)->orderBy('created_at','desc')->get();
            $driver = Driver::where('company_id',$userId)->orderBy('created_at','desc')->get();
            $bus = Bus::where('company_id',$userId)->orderBy('created_at','desc')->get();
            return view('backend.trip.edit',
                compact('guide','supervisor','path','driver','bus','trip'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request)
    {

        try{
            $date_now =  date('Y-m-d H:i:s');

            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [$locale . '.name' =>['required','string'],
                    'id'=>['required','exists:trips,id',],
                    'guide_id'=>['required','exists:guides,id',],
                    'path_id'=>'required|exists:paths,id',
                    'driver_id'=>'required|exists:drivers,id',
                    'supervisor_id'=>'required|exists:supervisors,id',
                    'bus_id'=>'required|exists:buses,id',
                    'number_passenger'=>'required|integer',
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
                    'supervisor_id.required' =>trans('admin.Supervisor name is required'),
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

            $trip = Trip::find( request('id') ) ; 
            $trip_id = request('id') ; 
            $dateStart = date('Y-m-d  h:i:s',strtotime($request->start_date));
            $dateEnd = date('Y-m-d h:i:s',strtotime($request->end_date));


            $guideTrips = CheckIfBusyUpdate($trip_id  ,$dateStart , $dateEnd  ,  'guide_id', $request->guide_id  );
            $driverTrips = CheckIfBusyUpdate($trip_id ,$dateStart , $dateEnd  ,  'driver_id', $request->driver_id  );
            $busesTrips = CheckIfBusyUpdate($trip_id  , $dateStart , $dateEnd  ,  'bus_id', $request->bus_id  );
           
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

            $trip->update($requestData);
            session()->flash('success',trans('admin.Data has been updated successfully'));

            return redirect()->route('company.trip.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit_by_calendar($id)
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

            $trip = Trip::find($id);
            checkData($trip);
            $supervisor = Supervisor::where('company_id',$userId)->orderBy('created_at','desc')->get();
            $path = Path::where('company_id',$userId)->orderBy('created_at','desc')->get();
            $guide = Guide::where('company_id',$userId)->orderBy('created_at','desc')->get();
            $driver = Driver::where('company_id',$userId)->orderBy('created_at','desc')->get();
            $bus = Bus::where('company_id',$userId)->orderBy('created_at','desc')->get();
            return view('backend.trip.edit_by_calendar',
                compact('guide','supervisor','path','driver','bus','trip'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function update_by_calendar(Request $request)
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
                    'supervisor_id'=>'required|exists:supervisors,id',
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
                    'supervisor_id.required' =>trans('admin.Supervisor name is required'),
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

            return redirect()->route('company.trip.management');

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

            return redirect()->route('company.trip.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }
    public function destroy_by_calendar($id)
    {
        try{

            $trip = Trip::find($id);

            checkData($trip);
            $trip->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('company.trip.management');
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

            return redirect()->route('company.trip.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }
    // show trips on calendar
    public function trip_management(){

        try{
            if(  get_guard() == 'supervisor'  ){
                $trips = Trip::where('supervisor_id', \Auth::guard('supervisor')->user()->id);
            }else{
                $trips = Trip::all();
            }
            return view('backend.trip.trip_management' ,compact('trips'));
        }catch (\Exception $exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }



}
