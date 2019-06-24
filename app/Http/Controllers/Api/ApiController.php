<?php

namespace App\Http\Controllers\Api;

use App\Admin;
use App\Bus;
use App\Company;
use App\Destination;
use App\Driver;
use App\EndTrip;
use App\Guide;
use App\Member;
use App\Notification;
use App\Path;
use App\RequestPauseTrip;
use App\Supervisor;
use App\SupervisorTranslation;
use App\Trip;
use App\TripMemeber;
use App\TripPause;
use App\TripStart;
use App\TripTranslation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Edujugon\PushNotification\PushNotification;



class ApiController extends Controller
{
    use ApiResponseTrait;

    public function test(){
        
        $push = new PushNotification('fcm');
        $push->setMessage([
            'data' => [
                    'title'=>'This is the title',
                    'message'=>'This is the message',
                    ]
            ])
            ->setApiKey('AAAAK8lWUHM:APA91bGEYlrZ0ZO_siwuvjUHPW1RKafnXslecIYEAt9bXVZ0qUmedFSgCNKXo96QHyFMvxZnUB6Q23ZY66s2cFP40rFqibmYb0NR5UDq7hhZ1ZGdIHiVNm8QwjxnrKN0umDFsQvX-Ev9')
            ->setDevicesToken('f7oS5PoHEyo:APA91bHypaAaJ2vpOGoJdpCDcxG464qUgERG8mRv0no141lzJZ6yC7QtW5Q8gfvSRZRXdpXyGcC-OwIZ7G6egY9c9ZFfFN6L7OHOXFug-0iBXfr9xEoEehatB9Qib0rREjt0CBZK3NHk')
            ->send()->getFeedback();
            // dd($push);
            return true ;
    }

    #login
    public function login(Request $request)
    {
        try{
            $messages = [
                'email.required' => 'We need to know your e-mail address!',
                'password.required' => 'We need to know your password address!',
            ];
            $validator = \Validator::make($request->all(), [
                'email'=>'required|email|string',
                'password'=>'required|min:3',
                'remember' =>'sometime|nullable'
            ], $messages);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }


            $tables = array('driver' => 'Driver',
                'supervisor' => 'Supervisor',
                'guide' => 'Guide' ,'member'=>'Member','company'=>'Company');

            $remember =  \request()->has('remember') == 1 ? true : false ;

            $email = \request('email');
            $password = \request('password');
            $isin = array();
            $nkey = array();
            foreach ($tables as $key => $table) {
                $get = "App\\".$table;

                $user = $get::where('email',$email)->where('status',1);


                if ($user->count() > 0) {
                    $isin[] = 'yes';
                    $nkey = $key;
                }
            }


            if (!$isin) {
                return $this->apiResponse('','Login failed please try again',400);
            }
            if ($nkey == 'driver')
            {
                $data = Driver::where('email',\request('email'))->where('status',1)->first();

                if (empty($data))
                {
                    return $this->apiResponse('','Login failed please try again',400);
                }
                else
                {
                    if(\Auth::guard($nkey)->attempt(array('email'=> $email, 'password' => $password),$remember))
                    {
                        return $this->apiResponse(
                            ['name'=>$data->translate($request->lang)->name,
                                'userToken'=>$data->user_token,
                                'role'=>'drivers',
                                'phone'=>$data->phone == null ? '' : $data->phone,
                                'address'=>$data->address == null ? '' : $data->address,
                                'image'=>$data->image == null ? 'public/upload/images/default.png' :'public/upload/driver/'.$data->image,
                                'companyId'=>$data->company_id,
                                'ssn'=>$data->ssn,
                                'nationality'=>$data->nationality,
                                'player_ids'=>$data->player_ids ? $data->player_ids : '' ,

                            ],
                            '',
                            200);
                    }
                    else
                    {
                        return $this->apiResponse('','Login failed please try again',400);
                    }

                }

            }

            elseif ($nkey == 'supervisor')
            {
                $data = Supervisor::where('email',\request('email'))->where('status',1)->first();


                if (empty($data))
                {
                    return $this->apiResponse('','Login failed please try again',400);
                }
                else
                {
                    if(\Auth::guard($nkey)->attempt(array('email'=> $email, 'password' => $password),$remember))
                    {
                        return $this->apiResponse(
                            ['name'=>$data->translate($request->lang)->name,
                                'userToken'=>$data->user_token,
                                'role'=>'supervisors',
                                'phone'=>$data->phone == null ? '' : $data->phone,
                                'address'=>$data->address == null ? '' : $data->address,
                                'image'=>$data->image == null ? 'public/upload/images/default.png'
                                    :'public/upload/supervisor/'.$data->image,
                                'companyId'=>$data->company_id,
                                'ssn'=>$data->ssn,
                                'nationality'=>$data->nationality,
                                'player_ids'=>$data->player_ids ? $data->player_ids : '' ,
                            ],
                            '',
                            200);
                    }
                    else
                    {
                        return $this->apiResponse('','Login failed please try again',400);
                    }

                }

            }

            elseif ($nkey == 'company')
            {
                $data = Company::where('email',\request('email'))->where('status',1)->first();


                if (empty($data))
                {
                    return $this->apiResponse('','Login failed please try again',400);
                }
                else
                {
                    if(\Auth::guard($nkey)->attempt(array('email'=> $email, 'password' => $password),$remember))
                    {
                        return $this->apiResponse(
                            ['name'=>$data->translate($request->lang)->name,
                                'userToken'=>$data->user_token,
                                'role'=>'companies',
                                'phone'=>$data->phone == null ? '' : $data->phone,
                                'address'=>$data->address == null ? '' : $data->address,
                                'image'=>$data->image == null ? 'public/upload/images/default.png'
                                    :'public/upload/company/'.$data->image,
                                'companyId'=>$data->id,
                                'player_ids'=>$data->player_ids ? $data->player_ids : '' ,

                            ],
                            '',
                            200);
                    }
                    else
                    {
                        return $this->apiResponse('','Login failed please try again',400);
                    }

                }

            }

            elseif ($nkey == 'guide')
            {
                $data = Guide::where('email',\request('email'))->where('status',1)->first();
                if (empty($data))
                {
                    return $this->apiResponse('','Login failed please try again',400);
                }
                else
                {
                    if(\Auth::guard($nkey)->attempt(array('email'=> $email, 'password' => $password),$remember))
                    {
                        return $this->apiResponse(
                            ['name'=>$data->translate($request->lang)->name,
                                'userToken'=>$data->user_token,
                                'role'=>'guides',
                                'phone'=>$data->phone == null ? '' : $data->phone,
                                'address'=>$data->address == null ? '' : $data->address,
                                'image'=>$data->image == null ? 'public/upload/images/default.png'
                                    :'public/upload/guide/'.$data->image,
                                'companyId'=>$data->company_id,
                                'ssn'=>$data->ssn,
                                'nationality'=>$data->nationality,
                                'player_ids'=>$data->player_ids ? $data->player_ids : '' ,

                            ],
                            '',
                            200);
                    }
                    else
                    {
                        return $this->apiResponse('','Login failed please try again',400);
                    }

                }

            }

            elseif ($nkey == 'member')
            {
                $data = Member::where('email',\request('email'))->where('status',1)->first();
                if (empty($data))
                {
                    return $this->apiResponse('','Login failed please try again',400);
                }
                else
                {
                    if(\Auth::guard($nkey)->attempt(array('email'=> $email, 'password' => $password),$remember))
                    {
                        return $this->apiResponse(
                            ['name'=>$data->translate($request->lang)->name,
                                'userToken'=>$data->user_token,
                                'role'=>'members',
                                'phone'=>$data->phone == null ? '' : $data->phone,
                                'address'=>$data->address == null ? '' : $data->address,
                                'image'=>$data->image == null ? 'public/upload/images/default.png'
                                    :'public/upload/member/'.$data->image,
                                'companyId'=>$data->company_id,
                                'ssn'=>$data->ssn,
                                'nationality'=>$data->nationality,
                                'player_ids'=>$data->player_ids ? $data->player_ids : '' ,

                            ],
                            '',
                            200);
                    }
                    else
                    {
                        return $this->apiResponse('','Login failed please try again',400);
                    }

                }

            }

            else
            {
                return $this->apiResponse('','Login failed please try again',400);
            }
        }catch (\Exception $exception )
        {
            return $this->apiResponse('',$exception->getMessage(),400);
        }
    }
    // get firebaseToken

    public function getFirebaseToken(Request $request){
        $validator = \Validator::make($request->all(),
            [
                'user_token'=>'required',
                'firebaseToken'=>'required',
                'deviceType'=>"required|in:ios,android",
                'role'=>"required|in:guides,members,companies,supervisors,drivers",

            ]);
        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }

        $user = DB::table(request('role'))->where('user_token', $request->user_token)->update([
            'firebaseToken'=> $request->firebaseToken,
            'deviceType'=>    $request->deviceType
        ]);

        return $this->apiResponse('done  success','',200);
    }

    #playerIds
    public function playerIds(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_token'=>'required',
                'role'=>'required|in:supervisors,drivers,members,guides',
                'player_ids' => 'required|string',
            ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors()->first(),'',200);
        }

        $data = \DB::table($request->role)->where('user_token','=',$request->user_token)->first();
        if (empty($data))
        {
           return $this->apiResponse('','not found this users',404);
        }
        else
        {
            $save = \DB::table($request->role)->where('user_token','=',$request->user_token)
                ->update(['player_ids'=>$request->player_ids]);
            if ($save)
            {
                return $this->apiResponse('success','',200);
            }
            else
            {
                return $this->apiResponse('failed','',200);
            }
        }

    }

    public function storeFirebase(Request $request)
    {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/ApiFirebase.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://al7ag-49058.firebaseio.com/')
            ->create();

        $database = $firebase->getDatabase();
        $ref = $database->getReference('buses');

        $key = $ref->push()->getKey(); // generate key increment by default
        $ref->getChild($request->user_token)->set([
            'busId'=>[
                'lat'=>'23.0520852052',
                'lng'=>'26.0520852052',
                'speed'=>'235',
                'status'=>'active',
            ],
        ]);

        return $this->apiResponse($key,'',200);



    }

    #getGuide
    public function getGuide(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'lang'=>'required|in:en,ar'
            ]);

        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }
        $guideId[]='';

        $guide = \DB::table('guides')
            ->join('guide_translations','guide_translations.guide_id','=','guides.id')
            ->where('guide_translations.locale','=',$request->lang)
            ->where('guides.status','=',1)
            ->select('guides.id as id','guide_translations.name as name','guides.email as email'
            ,'guides.phone as phone','guides.mobile as mobile','guides.address as address'
                ,'guides.ssn as ssn','guides.nationality as nationality','guides.image as image'
                ,'guides.birthday as birthday','guides.user_token as user_token','guides.company_id as company_id')
            ->get();

        if ($guide->isEmpty())
        {
            return $this->apiResponse('','not found data',404);
        }
        else
        {
            for ($i=0; $i<count($guide); $i++)
            {
                $details[] =
                    [
                        'id'=>$guide[$i]->id,
                        'name'=>$guide[$i]->name,
                        'email'=>$guide[$i]->email,
                        'phone'=>$guide[$i]->phone,
                        'mobile'=>$guide[$i]->mobile,
                        'address'=>$guide[$i]->address,
                        'ssn'=>$guide[$i]->ssn,
                        'nationality'=>$guide[$i]->nationality,
                        'image'=>'public/upload/guide/'.$guide[$i]->image,
                        'birthday'=>date('d-m-Y',strtotime($guide[$i]->birthday)),
                        'user_token'=>$guide[$i]->user_token,
                        'company_id'=>$guide[$i]->company_id,
                        'userTokenCompany'=>\DB::table('companies')->where('id','=',$guide[$i]->company_id)->value('user_token'),
                    ];
            }
            return $this->apiResponse($details,'',200);
        }
    }

    #getDriver
    public function getDriver(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'lang'=>'required|in:en,ar'
            ]);

        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }


        $guide = \DB::table('drivers')
            ->join('driver_translations','driver_translations.driver_id','=','drivers.id')
            ->where('driver_translations.locale','=',$request->lang)
            ->where('drivers.status','=',1)
            ->select('drivers.id as id','driver_translations.name as name','drivers.email as email'
                ,'drivers.phone as phone','drivers.mobile as mobile','drivers.address as address'
                ,'drivers.ssn as ssn','drivers.nationality as nationality','drivers.image as image'
                ,'drivers.birthday as birthday','drivers.user_token as user_token','drivers.company_id as company_id')
            ->get();

        if ($guide->isEmpty())
        {
            return $this->apiResponse('','not found data',404);
        }
        else
        {
            for ($i=0; $i<count($guide); $i++)
            {
                $details[] =
                    [
                        'id'=>$guide[$i]->id,
                        'name'=>$guide[$i]->name,
                        'email'=>$guide[$i]->email,
                        'phone'=>$guide[$i]->phone == null ? '' : $guide[$i]->phone,
                        'mobile'=>$guide[$i]->mobile == null ? '' : $guide[$i]->mobile,
                        'address'=>$guide[$i]->address == null ? '' : $guide[$i]->address,
                        'ssn'=>$guide[$i]->ssn,
                        'nationality'=>$guide[$i]->nationality,
                        'image'=>'public/upload/driver/'.$guide[$i]->image,
                        'birthday'=>date('d-m-Y',strtotime($guide[$i]->birthday)),
                        'user_token'=>$guide[$i]->user_token,
                        'company_id'=>$guide[$i]->company_id,
                        'userTokenCompany'=>\DB::table('companies')->where('id','=',$guide[$i]->company_id)->value('user_token'),
                    ];
            }
            return $this->apiResponse($details,'',200);
        }
    }

    #getBus
    public function getBus(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'lang'=>'required|in:en,ar',
                'driver_id'=>'required|exists:drivers,id'
            ]);

        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }

        $guide = \DB::table('buses')
            ->join('bus_translations','bus_translations.bus_id','=','buses.id')
            ->where('bus_translations.locale','=',$request->lang)
            ->where('buses.status','=',1)
            ->where('buses.driver_id','=',$request->driver_id)
            ->select('buses.id as id','bus_translations.name as name','buses.number_bus as number_bus'
                ,'buses.plate_number as plate_number','buses.number_chairs as number_chairs',
                'buses.company_id as company_id')
            ->get();

        if ($guide->isEmpty())
        {
            return $this->apiResponse('','not found data',404);
        }
        else
        {
            for ($i=0; $i<count($guide); $i++)
            {
                $details[] =
                    [
                        'id'=>$guide[$i]->id,
                        'name'=>$guide[$i]->name,
                        'numberBus'=>$guide[$i]->number_bus,
                        'plateNumber'=>$guide[$i]->plate_number,
                        'numberChairs'=>$guide[$i]->number_chairs,
                        'company_id'=>$guide[$i]->company_id,
                    ];
            }
            return $this->apiResponse($details,'',200);
        }
    }

    #getPath
    public function getPath(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'lang'=>'required|in:en,ar'
            ]);

        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }


        $guide = \DB::table('paths')
            ->where('status','=',1)
            ->get();
        if ( $guide->isEmpty())
        {
            return $this->apiResponse('','not found data',404);
        }
        else
        {
            for ($i=0; $i<count($guide); $i++)
            {
                $details[] =
                    [
                        'id'=>$guide[$i]->id,
                        'from_to'=>Destination::find($guide[$i]->from)->name .' | '. Destination::find($guide[$i]->to)->name,
                        'price'=>$guide[$i]->price,
                        'userTokenCompany'=>\DB::table('companies')->where('id','=',$guide[$i]->company_id)->value('user_token'),
                    ];
            }
            return $this->apiResponse($details,'',200);
        }
    }


    #addTrip  by the supervisor
    public function addTrip(Request $request)
    {
        try{

            $date_now =  date('Y-m-d H:i:s');

            $rules =[
                'user_token'=>'required',
                'name_en'=>'required|string',
                'name_ar'=>'required|string',
                'guide_id'=>'required|exists:guides,id',
                'driver_id'=>'required|exists:drivers,id',
                'bus_id'=>'required|integer|exists:buses,id',
                'price'=>'required|',
                'start_date'=>"required|date_format:Y-m-d H:i:s|before:end_date|after:$date_now",
                'end_date'=>"required|date_format:Y-m-d H:i:s|after:start_date|after:$date_now",
                'company_id'=>'required|integer|exists:companies,id',
                'status'=>'required|in:1,2,3,4,5,6,7,10',
                'path_id'=>'required|exists:paths,id',
            ];
            $message = [
                'name_en.required' =>trans('admin.Trip name english is required'),
                'name_ar.required' =>trans('admin.Trip name arabic is required'),
                'guide_id.required' =>trans('admin.Guide name is required'),
                'member_id.required' =>trans('admin.Member name is required'),
                'driver_id.required' =>trans('admin.Driver name is required'),
                'bus_id.required' =>trans('admin.Bus name is required'),
                'date_time_start.required' =>trans('admin.Start time trip is required'),
                'date_time_end.required' =>trans('admin.End time trip is required'),
                'path_id.required' =>trans('admin.Path name is required'),
                'status.required' =>trans('admin.Status is required'),
            ];

            $validator = \Validator::make($request->all(), $rules, $message);

            if ($validator->fails())
            {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }

            $dateStart = date('Y-m-d  h:i:s',strtotime($request->start_date));
            $dateEnd = date('Y-m-d h:i:s',strtotime($request->end_date));

            $buses = cdataEmpty('buses','id',$request->bus_id);

            $guideTrips = CheckIfBusy($dateStart , $dateEnd  ,  'guide_id', $request->guide_id  );
            $driverTrips = CheckIfBusy($dateStart , $dateEnd  ,  'driver_id', $request->driver_id  );
            $busesTrips = CheckIfBusy($dateStart , $dateEnd  ,  'bus_id', $request->bus_id  );


            if ( $guideTrips == true )
            {
                return $this->apiResponse('','this guide in trip now select date different '.date('d-m-Y',strtotime($dateEnd)),400);
            }

            if ( $driverTrips == true)
            {

                return $this->apiResponse('','this driver in trip now select date different '.date('d-m-Y',strtotime($dateEnd)),400);
            }
            if ( $busesTrips == true )
            {
                return $this->apiResponse('','this bus in trip now select date different '.date('d-m-Y',strtotime($dateEnd)),400);
            }

            $supervisor = \DB::table('supervisors')
                ->where('user_token','=',$request->user_token)->first();

            $trip = new Trip();
            $trip->company_id = $request->company_id;
            $trip->supervisor_id = $supervisor->id;
            $trip->guide_id = $request->guide_id;
            $trip->driver_id = $request->driver_id;
            $trip->bus_id = $request->bus_id;
            $trip->number_passenger = $buses->number_chairs;
            // $trip->start_time = $timeStart;
            // $trip->end_time = $timeEnd;
            $trip->start_date = $dateStart;
            $trip->end_date = $dateEnd;
            $trip->status = $request->status;
            $trip->path_id = $request->path_id;
            $trip->save();
            if ($trip->save())
            {

                $trips = new TripTranslation();
                $trips->trip_id = $trip->id;
                $trips->name = $request->name_en;
                $trips->locale = 'en';
                $trips->save();
                if ($trips->save())
                {
                    $tripsAr = new TripTranslation();
                    $tripsAr->trip_id = $trip->id;
                    $tripsAr->name = $request->name_ar;
                    $tripsAr->locale = 'ar';
                    $tripsAr->save();
                }
                if ($tripsAr->save())
                {
                    $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/ApiFirebase.json');
                    $firebase = (new Factory)
                        ->withServiceAccount($serviceAccount)
                        ->withDatabaseUri('https://al7ag-49058.firebaseio.com/')
                        ->create();

                    $database = $firebase->getDatabase();
                    $ref = $database->getReference('buses');

                    $ref->getChild($trip->company_id)->set([
                        $trip->bus_id => [
                            'lat'=> 0,// Destination::find(Path::find($trip->path_id)->from)->lat,
                            'lng'=> 0,
                            'speed'=> 0,
                            'status'=>'off',
                        ],
                    ]);
                    // dd('before');
                    // start send notification by firebase
                    $msg_member = " قام المشرف  باضافة رحله  الي  السجل الخاص به" ;
                    $this->send_notifcation('members' ,$request->member_id   , $msg_member );

                    $msg = "قام المشرف  باضافة رحله  الي  السجل " ;
                    $this->send_notifcation('drivers' , $request->driver_id  , $msg );
                    $this->send_notifcation('guides'  , $request->guide_id   ,  $msg );
                    // end send notification by firebase

                    // dd('after');


                    return $this->apiResponse(trans('admin.Data has been added successfully'),'',200);

                }
            }
        }catch (\Exception$exception )
        {
            return $this->apiResponse('',$exception->getMessage(),520);
        }
    }
    //  select some user to send him notification
    public function send_notifcation(   $role , $user_id , $msg  ){

        $user = \DB::table($role)->where('id','=',$user_id)
                ->first();

        if($user->deviceType =='android'){
            $this->notification_to_android( $user->firebaseToken , $msg );
        }
        if($user->deviceType =='ios'){
            $this->notification_to_ios( $user->firebaseToken , $msg );
        }
    }

    public function notification_to_android( $tokens , $msg )
    {
        dd("notification_to_android");
        $push = new PushNotification('fcm');
        $push->setMessage([ 'msg' => $msg ])
        ->setApiKey('AAAAWb5tYFE:APA91bH5k0wMJdGzk0vEqfnl6JyT4LUBT5vbAYutgAyBAnUarnLZhyNjMtX9CMODsvQ638X6k3PYS1ic9yEoXRhkeIZqAoNzRWBtx8HDWQiK6SwJW4UgQG7g3MtHBl7erYWuX7rWDTzF')
        ->setDevicesToken($tokens)
        ->send()
        ->getFeedback();

        return true;
    }
    /*
    **  we add the certificate in the folder
    ** vendor\edujugon\push-notification\src\Config\iosCertificates\Certificates1.pem
    **
    */
    public function notification_to_ios( $tokens , $msg )
    {
        // apn : not working  for ios
        // fcm : working for  both ios and android
        $push = new PushNotification('fcm');

        $push->setMessage([

            //  must be in this formate
            'data'=> [ 'msg'=> $msg   ] ,
            'notification' => [
                'title' => 'Notification',
                'body'  => $msg ,
                'sound' => true
            ],

        ])
        ->setApiKey('AAAAWb5tYFE:APA91bH5k0wMJdGzk0vEqfnl6JyT4LUBT5vbAYutgAyBAnUarnLZhyNjMtX9CMODsvQ638X6k3PYS1ic9yEoXRhkeIZqAoNzRWBtx8HDWQiK6SwJW4UgQG7g3MtHBl7erYWuX7rWDTzF')
        ->setDevicesToken($tokens)
        ->send()
        ->getFeedback();

        return true;
    }

    #getTrip
    public function getTrip(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'lang'=>'required|in:en,ar',
                'user_token'=>'required|exists:supervisors,user_token',
                'type'=>'required'
            ]);

        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }
        $supervisor = \DB::table('supervisors')
            ->where('user_token','=',$request->user_token)->first();

        $trip = \DB::table('trips')
            ->join('trip_translations','trip_translations.trip_id','=','trips.id')
            /*->where('trips.status','=',1)*/
            ->where('trips.supervisor_id','=',$supervisor->id)
            ->where('trip_translations.locale','=',$request->lang)
            ->select('trips.id as id','trip_translations.name as name','trips.company_id as companyId'
                ,'trips.guide_id as guideId','trips.driver_id as driverId',
                'trips.bus_id as busId','trips.start_time as startTime','trips.number_passenger as numberPassenger'
                ,'trips.end_time as endTime','trips.start_date as startDate','trips.end_date as endDate'
                ,'trips.path_id as pathID','trips.price as price','trips.status as status')
            ->get();

        if ($trip->isEmpty())
        {
            return $this->apiResponse('','not found data',404);
        }
        else
        {
            for ($i=0; $i<count($trip); $i++)
            {
                $status = '';
                if ($trip[$i]->status == 1)
                {
                    $status = 'مسندة';
                }
                elseif ($trip[$i]->status == 2)
                {
                    $status = 'قيد التنفيذ';
                }
                elseif ($trip[$i]->status == 3)
                {
                    $status = 'معلق';
                }
                elseif ($trip[$i]->status == 4)
                {
                    $status = 'ملغية';
                }
                elseif ($trip[$i]->status == 5)
                {
                    $status = 'مقفل نهائى';
                }
                elseif ($trip[$i]->status == 6)
                {
                    $status = 'مقفل جزئى';
                }
                elseif ($trip[$i]->status == 7)
                {
                    $status = 'مجدولة';
                }
                elseif ($trip[$i]->status == 10)
                {
                    $status = 'فورية';
                }
                else
                {
                    $status = 'لا توجد هذا الرحلة';
                }
                $details[] =
                    [

                        'tripId'=>$trip[$i]->id,
                        'tripName'=>$trip[$i]->name,
                        'companyId'=>$trip[$i]->companyId,
                        'companyName'=>\DB::table('company_translations')
                            ->where('company_id','=',$trip[$i]->companyId)->where('locale','=',$request->lang)
                            ->value('name'),
                        /* 'guideId'=>$trip[$i]->guideId,*/
                        'guideName'=>\DB::table('guide_translations')
                            ->where('guide_id','=',$trip[$i]->guideId)->where('locale','=',$request->lang)
                            ->value('name'),
                        /* 'driverId'=>$trip[$i]->driverId,*/
                        'driverName'=>\DB::table('driver_translations')
                            ->where('driver_id','=',$trip[$i]->driverId)->where('locale','=',$request->lang)
                            ->value('name'),
                        /*'busId'=>$trip[$i]->busId,*/
                        'busName'=>\DB::table('bus_translations')
                            ->where('bus_id','=',$trip[$i]->busId)->where('locale','=',$request->lang)
                            ->value('name'),
                        'numberPassenger'=>$trip[$i]->numberPassenger,
                        'dateStart'=>date('d-m-Y',strtotime($trip[$i]->startDate)).' '.date('h:m A',strtotime($trip[$i]->startTime)),
                        'dateEnd'=>date('d-m-Y',strtotime($trip[$i]->endDate)).' '.date('h:m A',strtotime($trip[$i]->endTime)),
                        'from'=>Destination::find(Path::find($trip[$i]->pathID)->from)->translate($request->lang)->name,
                        'to'=>Destination::find(Path::find($trip[$i]->pathID)->to)->translate($request->lang)->name,
                        'latStart'=>Destination::find(Path::find($trip[$i]->pathID)->from)->lat,
                        'lngStart'=>Destination::find(Path::find($trip[$i]->pathID)->from)->lng,
                        'latEnd'=>Destination::find(Path::find($trip[$i]->pathID)->to)->lat,
                        'lngEnd'=>Destination::find(Path::find($trip[$i]->pathID)->to)->lng,
                        'price'=>$trip[$i]->price,
                        'status'=>$status,
                        'statusId'=>$trip[$i]->status,
                    ];
            }
            return $this->apiResponse($details,'',200);
        }



    }

    #filterByStatus
    public function filterByStatus(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'lang'=>'required|in:en,ar',
                'user_token'=>'required|exists:supervisors,user_token',
                'status'=>'required|in:1,2,3,4,5,6,7,10',
            ]);

        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }
        $supervisor = \DB::table('supervisors')
            ->where('user_token','=',$request->user_token)->first();

        $trip = \DB::table('trips')
            ->join('trip_translations','trip_translations.trip_id','=','trips.id')
            ->where('trips.status','=',$request->get('status'))
            ->where('trips.supervisor_id','=',$supervisor->id)
            ->where('trip_translations.locale','=',$request->lang)
            ->select('trips.id as id','trip_translations.name as name','trips.company_id as companyId'
                ,'trips.guide_id as guideId','trips.driver_id as driverId',
                'trips.bus_id as busId','trips.start_time as startTime','trips.number_passenger as numberPassenger'
                ,'trips.end_time as endTime','trips.start_date as startDate','trips.end_date as endDate'
                ,'trips.path_id as pathID','trips.price as price','trips.status as status')
            ->get();

        if ($trip->isEmpty())
        {
            return $this->apiResponse('','not found data',404);
        }
        else
        {
            for ($i=0; $i<count($trip); $i++)
            {
                $status = '';
                if ($trip[$i]->status == 1)
                {
                    $status = 'مسندة';
                }
                elseif ($trip[$i]->status == 2)
                {
                    $status = 'قيد التنفيذ';
                }
                elseif ($trip[$i]->status == 3)
                {
                    $status = 'معلق';
                }
                elseif ($trip[$i]->status == 4)
                {
                    $status = 'ملغية';
                }
                elseif ($trip[$i]->status == 5)
                {
                    $status = 'مقفل نهائى';
                }
                elseif ($trip[$i]->status == 6)
                {
                    $status = 'مقفل جزئى';
                }
                elseif ($trip[$i]->status == 7)
                {
                    $status = 'مجدولة';
                }
                elseif ($trip[$i]->status == 10)
                {
                    $status = 'فورية';
                }
                else
                {
                    $status = 'لا توجد هذا الرحلة';
                }
                $details[] =
                    [

                        'tripId'=>$trip[$i]->id,
                        'tripName'=>$trip[$i]->name,
                        'companyId'=>$trip[$i]->companyId,
                        'companyName'=>\DB::table('company_translations')
                            ->where('company_id','=',$trip[$i]->companyId)->where('locale','=',$request->lang)
                            ->value('name'),
                        /* 'guideId'=>$trip[$i]->guideId,*/
                        'guideName'=>\DB::table('guide_translations')
                            ->where('guide_id','=',$trip[$i]->guideId)->where('locale','=',$request->lang)
                            ->value('name'),
                        /* 'driverId'=>$trip[$i]->driverId,*/
                        'driverName'=>\DB::table('driver_translations')
                            ->where('driver_id','=',$trip[$i]->driverId)->where('locale','=',$request->lang)
                            ->value('name'),
                        /*'busId'=>$trip[$i]->busId,*/
                        'busName'=>\DB::table('bus_translations')
                            ->where('bus_id','=',$trip[$i]->busId)->where('locale','=',$request->lang)
                            ->value('name'),
                        'numberPassenger'=>$trip[$i]->numberPassenger,
                        'dateStart'=>date('d-m-Y',strtotime($trip[$i]->startDate)).' '.date('h:m A',strtotime($trip[$i]->startTime)),
                        'dateEnd'=>date('d-m-Y',strtotime($trip[$i]->endDate)).' '.date('h:m A',strtotime($trip[$i]->endTime)),
                        'from'=>Destination::find(Path::find($trip[$i]->pathID)->from)->translate($request->lang)->name,
                        'to'=>Destination::find(Path::find($trip[$i]->pathID)->to)->translate($request->lang)->name,
                        'latStart'=>Destination::find(Path::find($trip[$i]->pathID)->from)->lat,
                        'lngStart'=>Destination::find(Path::find($trip[$i]->pathID)->from)->lng,
                        'latEnd'=>Destination::find(Path::find($trip[$i]->pathID)->to)->lat,
                        'lngEnd'=>Destination::find(Path::find($trip[$i]->pathID)->to)->lng,
                        'price'=>$trip[$i]->price,
                        'status'=>$status,
                        'statusId'=>$trip[$i]->status,

                    ];
            }
            return $this->apiResponse($details,'',200);
        }
    }

    #filterByDate
    public function filterByDate(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'lang'=>'required|in:en,ar',
                'user_token'=>'required|exists:supervisors,user_token',
                'status'=>'required|in:new,old',
            ]);

        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }
        $supervisor = \DB::table('supervisors')
            ->where('user_token','=',$request->user_token)->first();

        if ($request->status == 'new')
        {
            $trip = \DB::table('trips')
                ->join('trip_translations','trip_translations.trip_id','=','trips.id')
                ->where('trips.supervisor_id','=',$supervisor->id)
                ->where('trip_translations.locale','=',$request->lang)
                ->select('trips.id as id','trip_translations.name as name','trips.company_id as companyId'
                    ,'trips.guide_id as guideId','trips.driver_id as driverId',
                    'trips.bus_id as busId','trips.start_time as startTime','trips.number_passenger as numberPassenger'
                    ,'trips.end_time as endTime','trips.start_date as startDate','trips.end_date as endDate'
                    ,'trips.path_id as pathID','trips.price as price','trips.status as status')
                ->orderBy('trips.created_at','DESC')->get();
        }
        elseif ($request->status == 'old')
        {
            $trip = \DB::table('trips')
                ->join('trip_translations','trip_translations.trip_id','=','trips.id')
                ->where('trips.supervisor_id','=',$supervisor->id)
                ->where('trip_translations.locale','=',$request->lang)
                ->select('trips.id as id','trip_translations.name as name','trips.company_id as companyId'
                    ,'trips.guide_id as guideId','trips.driver_id as driverId',
                    'trips.bus_id as busId','trips.start_time as startTime','trips.number_passenger as numberPassenger'
                    ,'trips.end_time as endTime','trips.start_date as startDate','trips.end_date as endDate'
                    ,'trips.path_id as pathID','trips.price as price','trips.status as status')
                ->orderBy('trips.created_at','ASC')->get();
        }
        else
        {
            return $this->apiResponse('','please select status new or old',400);
        }


        if ($trip->isEmpty())
        {
            return $this->apiResponse('','not found data',404);
        }
        else
        {
            for ($i=0; $i<count($trip); $i++)
            {
                $status = '';
                if ($trip[$i]->status == 1)
                {
                    $status = 'مسندة';
                }
                elseif ($trip[$i]->status == 2)
                {
                    $status = 'قيد التنفيذ';
                }
                elseif ($trip[$i]->status == 3)
                {
                    $status = 'معلق';
                }
                elseif ($trip[$i]->status == 4)
                {
                    $status = 'ملغية';
                }
                elseif ($trip[$i]->status == 5)
                {
                    $status = 'مقفل نهائى';
                }
                elseif ($trip[$i]->status == 6)
                {
                    $status = 'مقفل جزئى';
                }
                elseif ($trip[$i]->status == 7)
                {
                    $status = 'مجدولة';
                }
                elseif ($trip[$i]->status == 10)
                {
                    $status = 'فورية';
                }
                else
                {
                    $status = 'لا توجد هذا الرحلة';
                }
                $details[] =
                    [

                        'tripId'=>$trip[$i]->id,
                        'tripName'=>$trip[$i]->name,
                        'companyId'=>$trip[$i]->companyId,
                        'companyName'=>\DB::table('company_translations')
                            ->where('company_id','=',$trip[$i]->companyId)->where('locale','=',$request->lang)
                            ->value('name'),
                        /* 'guideId'=>$trip[$i]->guideId,*/
                        'guideName'=>\DB::table('guide_translations')
                            ->where('guide_id','=',$trip[$i]->guideId)->where('locale','=',$request->lang)
                            ->value('name'),
                        /* 'driverId'=>$trip[$i]->driverId,*/
                        'driverName'=>\DB::table('driver_translations')
                            ->where('driver_id','=',$trip[$i]->driverId)->where('locale','=',$request->lang)
                            ->value('name'),
                        /*'busId'=>$trip[$i]->busId,*/
                        'busName'=>\DB::table('bus_translations')
                            ->where('bus_id','=',$trip[$i]->busId)->where('locale','=',$request->lang)
                            ->value('name'),
                        'numberPassenger'=>$trip[$i]->numberPassenger,
                        'dateStart'=>date('d-m-Y',strtotime($trip[$i]->startDate)).' '.date('h:m A',strtotime($trip[$i]->startTime)),
                        'dateEnd'=>date('d-m-Y',strtotime($trip[$i]->endDate)).' '.date('h:m A',strtotime($trip[$i]->endTime)),
                        'from'=>Destination::find(Path::find($trip[$i]->pathID)->from)->translate($request->lang)->name,
                        'to'=>Destination::find(Path::find($trip[$i]->pathID)->to)->translate($request->lang)->name,
                        'latStart'=>Destination::find(Path::find($trip[$i]->pathID)->from)->lat,
                        'lngStart'=>Destination::find(Path::find($trip[$i]->pathID)->from)->lng,
                        'latEnd'=>Destination::find(Path::find($trip[$i]->pathID)->to)->lat,
                        'lngEnd'=>Destination::find(Path::find($trip[$i]->pathID)->to)->lng,
                        'price'=>$trip[$i]->price,
                        'status'=>$status,
                        'statusId'=>$trip[$i]->status,

                    ];
            }
            return $this->apiResponse($details,'',200);
        }
    }

    #edit trip
    public function editTrip(Request $request)
    {
        try{

            $dateStart = date('Y-m-d',strtotime($request->date_time_start));
            $dateEnd = date('Y-m-d',strtotime($request->date_time_end));

            $timeStart =  date('h:i:s',strtotime($request->date_time_start));
            $timeEnd = date('h:i:s',strtotime($request->date_time_end));

            $trip =Trip::find($request->trip_id);
            if (empty($trip))
            {
                return $this->apiResponse('','not found this trips',400);
            }
            else
            {
                $rules =[
                    'user_token'=>'required',
                    'name_en'=>'required|string',
                    'name_ar'=>'required|string',
                    'guide_id'=>'required|exists:guides,id',
                    'driver_id'=>'required|exists:drivers,id',
                    'bus_id'=>'required|integer|exists:buses,id',
                    'price'=>'required|',
                    'date_time_start'=>'required|before_or_equal:'.$dateEnd,
                    'date_time_end'=>'required|after_or_equal:'.$dateStart,
                    'company_id'=>'required|integer|exists:companies,id',
                    'status'=>'required|in:1,2,3,7',
                    'path_id'=>'required|exists:paths,id',
                    'trip_id'=>'required|exists:trips,id',

                ];
                $message = [
                    'name_en.required' =>trans('admin.Trip name english is required'),
                    'name_ar.required' =>trans('admin.Trip name arabic is required'),
                    'guide_id.required' =>trans('admin.Guide name is required'),
                    'member_id.required' =>trans('admin.Member name is required'),
                    'driver_id.required' =>trans('admin.Driver name is required'),
                    'bus_id.required' =>trans('admin.Bus name is required'),
                    'date_time_start.required' =>trans('admin.Start time trip is required'),
                    'date_time_end.required' =>trans('admin.End time trip is required'),
                    'path_id.required' =>trans('admin.Path name is required'),
                    'status.required' =>trans('admin.Status is required'),

                ];

                $validator = \Validator::make($request->all(), $rules, $message);

                if ($validator->fails())
                {
                    return $this->apiResponse('',$validator->errors()->first(),400);
                }

                $buses = cdataEmpty('buses','id',$request->bus_id);

                $guideTrips = checkTrips('trips','guide_id',$request->guide_id);
                $driverTrips = checkTrips('trips','guide_id',$request->driver_id);
                $busesTrips = checkTrips('trips','guide_id',$request->bus_id);



                $getGuide = checkTripsForLoop($guideTrips,$dateEnd);
                if ($getGuide == true)
                {
                    return $this->apiResponse('','this guide in trip now select date different '.date('d-m-Y',strtotime($dateEnd)),400);
                }

                $getDriver = checkTripsForLoop($driverTrips,$dateEnd);
                if ($getDriver == true)
                {

                    return $this->apiResponse('','this driver in trip now select date different '.date('d-m-Y',strtotime($dateEnd)),400);
                }
                $getBus = checkTripsForLoop($busesTrips,$dateEnd);
                if ($getBus == true)
                {
                    return $this->apiResponse('','this bus in trip now select date different '.date('d-m-Y',strtotime($dateEnd)),400);
                }
                $supervisor = \DB::table('supervisors')
                    ->where('user_token','=',$request->user_token)->first();

                $trip->supervisor_id = $supervisor->id;
                $trip->guide_id = $request->guide_id;
                $trip->driver_id = $request->driver_id;
                $trip->bus_id = $request->bus_id;
                $trip->number_passenger = $buses->number_chairs;
                $trip->start_time = $timeStart;
                $trip->end_time = $timeEnd;
                $trip->start_date = $dateStart;
                $trip->end_date = $dateEnd;
                $trip->status = $request->status;
                $trip->path_id = $request->path_id;
                $trip->save();
                if ($trip->save())
                {
                    TripTranslation::where('locale','en')->where('trip_id',$trip->id)
                        ->update(['name'=>$request->name_en]);
                    TripTranslation::where('locale','ar')->where('trip_id',$trip->id)
                        ->update(['name'=>$request->name_ar]);
                    $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/ApiFirebase.json');
                    $firebase = (new Factory)
                        ->withServiceAccount($serviceAccount)
                        ->withDatabaseUri('https://al7ag-49058.firebaseio.com/')
                        ->create();

                    $database = $firebase->getDatabase();
                    $ref = $database->getReference('buses');

                    $ref->getChild($trip->company_id)->set([
                        $trip->bus_id => [
                            'lat'=> 0,//Destination::find(Path::find($trip->path_id)->from)->lat,
                            'lng'=> 0,
                            'speed'=> 0,
                            'status'=>'off',
                        ],
                    ]);
                    return $this->apiResponse(trans('admin.Data has been updated successfully'),'',200);
                }
            }


        }catch (\Exception$exception )
        {
            return $this->apiResponse('',$exception->getMessage(),520);
        }
    }

    #edit trip status
    public function editTripStatus(Request $request)
    {
        try{

            $trip =Trip::find($request->trip_id);
            if (empty($trip))
            {
                return $this->apiResponse('','not found this trips',400);
            }
            else
            {
                $rules =[
                    'user_token'=>'required',
                    'status'=>'required|in:1,2,3,7',
                    'trip_id'=>'required|exists:trips,id',

                ];
                $message = [
                    'status.required' =>trans('admin.Status is required'),
                ];

                $validator = \Validator::make($request->all(), $rules, $message);

                if ($validator->fails())
                {
                    return $this->apiResponse('',$validator->errors()->first(),400);
                }

                $supervisor = \DB::table('supervisors')
                    ->where('user_token','=',$request->user_token)->first();
                if (empty($supervisor))
                {
                    return $this->apiResponse('',trans('admin.supervisor not found in trips'),400);
                }
                $trip->status = $request->status;
                $trip->save();
                if ($trip->save())
                {
                    return $this->apiResponse(trans('admin.Status has been updated successfully'),'',200);

                }
            }
        }catch (\Exception$exception )
        {
            return $this->apiResponse('',$exception->getMessage(),520);
        }
    }

    #startTrip
    public function startTrip(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_token'=>'required', // user token supervisor
                'trip_id'=>'required|exists:trips,id',
                'headings'=>'required',
                'message'=>'required',
            ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors()->first(),'',200);
        }
        $supervisors = Supervisor::where('user_token',$request->user_token)->first();

        if (empty($supervisors))
        {
            return $this->apiResponse('','please check supervisors',404);
        }
        else
        {
            $trips = Trip::find($request->trip_id);

            if (empty($trips))
            {
                return $this->apiResponse('','not found this trips',404);
            }
            else
            {
                $tripStart = new TripStart();
                $tripStart->trip_id = $trips->id;
                $tripStart->company_id = $trips->company_id;
                $tripStart->supervisor_id = $trips->supervisor_id;
                $tripStart->driver_id = $trips->driver_id;
                $tripStart->guide_id = $trips->guide_id;
                $tripStart->bus_id = $trips->bus_id;
                $tripStart->path_id = $trips->path_id;
                $tripStart->start = Carbon::now();
                $tripStart->save();
                if ($tripStart->save())
                {
                    $playerDriver = Driver::find($trips->driver_id);
                    $playerGuide = Guide::find($trips->guide_id);
                   /* $content = array(
                        "en" => $request->message,
                    );

                    $headings = array(
                        "en" => $request->headings,
                    );
                    $fields = array(
                        'app_id' =>"ffba68ac-bdcc-4dbd-80ce-cd47738d8173",
                        'include_player_ids' => array("$playerDriver->player_ids","$playerGuide->player_ids"),
                        'data' => array("foo" => "bar"),
                        'contents' => $content,
                        'headings'=>$headings,
                    );

                    $fields = json_encode($fields);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_HEADER, FALSE);
                    curl_setopt($ch, CURLOPT_POST, TRUE);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

                    $response = curl_exec($ch);
                    curl_close($ch);*/
                    $notification = new Notification();
                    $notification->trip_id = $tripStart->trip_id;
                    $notification->company_id = $tripStart->company_id;
                    $notification->supervisor_id = $tripStart->supervisor_id;
                    $notification->driver_id = $tripStart->driver_id;
                    $notification->guide_id = $tripStart->guide_id;
                    $notification->message = $request->message;
                    $notification->headings = $request->headings;
                    $notification->save();
                    if ($notification->save())
                    {
                        $trips->status =  2; // لم تبد الرحلة
                        $trips->save();
                        if ($trips->save())
                        {
                            return $this->apiResponse('success trips start','',200);
                        }
                        else
                        {
                            return $this->apiResponse('please try again','',500);
                        }

                    }
                    else
                    {
                        return $this->apiResponse('please try again','',500);
                    }

                }
                else
                {
                    return $this->apiResponse('please try again','',500);
                }
            }
        }
    }


    #startTripGuide
    public function startTripGuide(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_token'=>'required', // user token guide
                'trip_id'=>'required|exists:trips,id',
                'headings'=>'required',
                'message'=>'required',
            ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors()->first(),'',200);
        }
        $guide = Guide::where('user_token',$request->user_token)->first();

        if (empty($guide))
        {
            return $this->apiResponse('','please check guide',404);
        }
        else
        {
            $trips = Trip::find($request->trip_id);

            if (empty($trips))
            {
                return $this->apiResponse('','not found this trips',404);
            }
            else
            {
                $tripStart = new TripStart();
                $tripStart->trip_id = $trips->id;
                $tripStart->company_id = $trips->company_id;
                $tripStart->supervisor_id = $trips->supervisor_id;
                $tripStart->driver_id = $trips->driver_id;
                $tripStart->guide_id = $trips->guide_id;
                $tripStart->bus_id = $trips->bus_id;
                $tripStart->path_id = $trips->path_id;
                $tripStart->start = Carbon::now();
                $tripStart->save();
                if ($tripStart->save())
                {
                    $playerDriver = Driver::find($trips->driver_id);
                    $playerSupervisor = Supervisor::find($trips->superviosr);
                    /* $content = array(
                         "en" => $request->message,
                     );

                     $headings = array(
                         "en" => $request->headings,
                     );
                     $fields = array(
                         'app_id' =>"ffba68ac-bdcc-4dbd-80ce-cd47738d8173",
                         'include_player_ids' => array("$playerDriver->player_ids","$playerSupervisor->player_ids"),
                         'data' => array("foo" => "bar"),
                         'contents' => $content,
                         'headings'=>$headings,
                     );

                     $fields = json_encode($fields);
                     $ch = curl_init();
                     curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                     curl_setopt($ch, CURLOPT_HEADER, FALSE);
                     curl_setopt($ch, CURLOPT_POST, TRUE);
                     curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

                     $response = curl_exec($ch);
                     curl_close($ch);*/
                    $notification = new Notification();
                    $notification->trip_id = $tripStart->trip_id;
                    $notification->company_id = $tripStart->company_id;
                    $notification->supervisor_id = $tripStart->supervisor_id;
                    $notification->driver_id = $tripStart->driver_id;
                    $notification->guide_id = $tripStart->guide_id;
                    $notification->message = $request->message;
                    $notification->headings = $request->headings;
                    $notification->save();
                    if ($notification->save())
                    {
                        $trips->status =  2; // لم تبد الرحلة
                        $trips->save();
                        if ($trips->save())
                        {
                            return $this->apiResponse('success trips start','',200);
                        }
                        else
                        {
                            return $this->apiResponse('please try again','',500);
                        }

                    }
                    else
                    {
                        return $this->apiResponse('please try again','',500);
                    }

                }
                else
                {
                    return $this->apiResponse('please try again','',500);
                }
            }
        }
    }


    #startTripDriver
    public function startTripDriver(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_token'=>'required', // user token driver
                'trip_id'=>'required|exists:trips,id',
                'headings'=>'required',
                'message'=>'required',
            ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors()->first(),'',200);
        }
        $driver = Driver::where('user_token',$request->user_token)->first();

        if (empty($driver))
        {
            return $this->apiResponse('','please check driver',404);
        }
        else
        {
            $trips = Trip::find($request->trip_id);

            if (empty($trips))
            {
                return $this->apiResponse('','not found this trips',404);
            }
            else
            {
                $tripStart = new TripStart();
                $tripStart->trip_id = $trips->id;
                $tripStart->company_id = $trips->company_id;
                $tripStart->supervisor_id = $trips->supervisor_id;
                $tripStart->driver_id = $trips->driver_id;
                $tripStart->guide_id = $trips->guide_id;
                $tripStart->bus_id = $trips->bus_id;
                $tripStart->path_id = $trips->path_id;
                $tripStart->start = Carbon::now();
                $tripStart->save();
                if ($tripStart->save())
                {
                    $playerGuide = Guide::find($trips->driver_id);
                    $playerSupervisor = Supervisor::find($trips->superviosr);
                    /* $content = array(
                         "en" => $request->message,
                     );

                     $headings = array(
                         "en" => $request->headings,
                     );
                     $fields = array(
                         'app_id' =>"ffba68ac-bdcc-4dbd-80ce-cd47738d8173",
                         'include_player_ids' => array("$playerSupervisor->player_ids","$playerGuide->player_ids"),
                         'data' => array("foo" => "bar"),
                         'contents' => $content,
                         'headings'=>$headings,
                     );

                     $fields = json_encode($fields);
                     $ch = curl_init();
                     curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                     curl_setopt($ch, CURLOPT_HEADER, FALSE);
                     curl_setopt($ch, CURLOPT_POST, TRUE);
                     curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

                     $response = curl_exec($ch);
                     curl_close($ch);*/
                    $notification = new Notification();
                    $notification->trip_id = $tripStart->trip_id;
                    $notification->company_id = $tripStart->company_id;
                    $notification->supervisor_id = $tripStart->supervisor_id;
                    $notification->driver_id = $tripStart->driver_id;
                    $notification->guide_id = $tripStart->guide_id;
                    $notification->message = $request->message;
                    $notification->headings = $request->headings;
                    $notification->save();
                    if ($notification->save())
                    {
                        $trips->status =  2; // لم تبد الرحلة
                        $trips->save();
                        if ($trips->save())
                        {
                            return $this->apiResponse('success trips start','',200);
                        }
                        else
                        {
                            return $this->apiResponse('please try again','',500);
                        }

                    }
                    else
                    {
                        return $this->apiResponse('please try again','',500);
                    }

                }
                else
                {
                    return $this->apiResponse('please try again','',500);
                }
            }
        }
    }

    #tripPause
    public function tripPause(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_token'=>'required',
                'trip_id'=>'required|exists:trips,id',
                'headings'=>'required',
                'message'=>'required',
                'status'=>'required|in:3',
            ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors()->first(),'',200);
        }
        $supervisors = Supervisor::where('user_token',$request->user_token)->first();

        if (empty($supervisors))
        {
            return $this->apiResponse('','please check supervisors',404);
        }
        else
        {
            $trips = Trip::find($request->trip_id);

            if (empty($trips))
            {
                return $this->apiResponse('','not found this trips',404);
            }
            else
            {
                $tripTripPause = new TripPause();
                $tripTripPause->trip_id = $trips->id;
                $tripTripPause->company_id = $trips->company_id;
                $tripTripPause->supervisor_id = $trips->supervisor_id;
                $tripTripPause->driver_id = $trips->driver_id;
                $tripTripPause->guide_id = $trips->guide_id;
                $tripTripPause->bus_id = $trips->bus_id;
                $tripTripPause->path_id = $trips->path_id;
                $tripTripPause->reason = $request->message;
                $tripTripPause->status = $request->status;
                $tripTripPause->start = Carbon::now();
                $tripTripPause->save();
                if ($tripTripPause->save())
                {
                    $playerDriver = Driver::find($trips->driver_id);
                    $playerGuide = Guide::find($trips->guide_id);
                    /* $content = array(
                         "en" => $request->message,
                     );

                     $headings = array(
                         "en" => $request->headings,
                     );
                     $fields = array(
                         'app_id' =>"ffba68ac-bdcc-4dbd-80ce-cd47738d8173",
                         'include_player_ids' => array("$playerDriver->player_ids","$playerGuide->player_ids"),
                         'data' => array("foo" => "bar"),
                         'contents' => $content,
                         'headings'=>$headings,
                     );

                     $fields = json_encode($fields);
                     $ch = curl_init();
                     curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                     curl_setopt($ch, CURLOPT_HEADER, FALSE);
                     curl_setopt($ch, CURLOPT_POST, TRUE);
                     curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

                     $response = curl_exec($ch);
                     curl_close($ch);*/
                    $notification = new Notification();
                    $notification->trip_id = $tripTripPause->trip_id;
                    $notification->company_id = $tripTripPause->company_id;
                    $notification->supervisor_id = $tripTripPause->supervisor_id;
                    $notification->driver_id = $tripTripPause->driver_id;
                    $notification->guide_id = $tripTripPause->guide_id;
                    $notification->message = $request->message;
                    $notification->headings = $request->headings;
                    $notification->save();
                    if ($notification->save())
                    {
                        $trips->status =  $request->status;
                        $trips->save();
                        if ($trips->save())
                        {
                            return $this->apiResponse('success trips Pause','',200);
                        }
                        else
                        {
                            return $this->apiResponse('please try again','',500);
                        }

                    }
                    else
                    {
                        return $this->apiResponse('please try again','',500);
                    }

                }
                else
                {
                    return $this->apiResponse('please try again','',500);
                }
            }
        }
    }

    #getTripsDriver
    public function getTripsDriver(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'lang'=>'required|in:en,ar',
                'user_token'=>'required|exists:drivers,user_token',
            ]);

        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }
        $driver = \DB::table('drivers')
            ->where('user_token','=',$request->user_token)->first();

        $trip = \DB::table('trips')
            ->join('trip_translations','trip_translations.trip_id','=','trips.id')
            /*->where('trips.status','=',1)*/
            ->where('trips.driver_id','=',$driver->id)
            ->where('trip_translations.locale','=',$request->lang)
            ->select('trips.id as id','trip_translations.name as name','trips.company_id as companyId'
                ,'trips.guide_id as guideId','trips.driver_id as driverId',
                'trips.bus_id as busId','trips.start_time as startTime','trips.number_passenger as numberPassenger'
                ,'trips.end_time as endTime','trips.start_date as startDate','trips.end_date as endDate'
                ,'trips.path_id as pathID','trips.price as price','trips.status as status',
                'trips.supervisor_id as supervisorId')
            ->get();

        if ($trip->isEmpty())
        {
            return $this->apiResponse('','not found data',404);
        }
        else
        {
            for ($i=0; $i<count($trip); $i++)
            {
                $status = '';
                if ($trip[$i]->status == 1)
                {
                    $status = 'مسندة';
                }
                elseif ($trip[$i]->status == 2)
                {
                    $status = 'قيد التنفيذ';
                }
                elseif ($trip[$i]->status == 3)
                {
                    $status = 'معلق';
                }
                elseif ($trip[$i]->status == 4)
                {
                    $status = 'ملغية';
                }
                elseif ($trip[$i]->status == 5)
                {
                    $status = 'مقفل نهائى';
                }
                elseif ($trip[$i]->status == 6)
                {
                    $status = 'مقفل جزئى';
                }
                elseif ($trip[$i]->status == 7)
                {
                    $status = 'مجدولة';
                }
                elseif ($trip[$i]->status == 10)
                {
                    $status = 'فورية';
                }
                else
                {
                    $status = 'لا توجد هذا الرحلة';
                }
                $details[] =
                    [

                        'tripId'=>$trip[$i]->id,
                        'tripName'=>$trip[$i]->name,
                        'companyId'=>$trip[$i]->companyId,
                        'companyName'=>\DB::table('company_translations')
                            ->where('company_id','=',$trip[$i]->companyId)->where('locale','=',$request->lang)
                            ->value('name'),
                         'guideId'=>$trip[$i]->guideId,
                        'guideName'=>\DB::table('guide_translations')
                            ->where('guide_id','=',$trip[$i]->guideId)->where('locale','=',$request->lang)
                            ->value('name'),
                        'supervisorId'=>$trip[$i]->supervisorId,
                        'supervisorName'=>\DB::table('supervisor_translations')
                            ->where('supervisor_id','=',$trip[$i]->supervisorId)->where('locale','=',$request->lang)
                            ->value('name'),
                         'driverId'=>$trip[$i]->driverId,
                        'driverName'=>\DB::table('driver_translations')
                            ->where('driver_id','=',$trip[$i]->driverId)->where('locale','=',$request->lang)
                            ->value('name'),
                        /*'busId'=>$trip[$i]->busId,*/
                        'busName'=>\DB::table('bus_translations')
                            ->where('bus_id','=',$trip[$i]->busId)->where('locale','=',$request->lang)
                            ->value('name'),
                        'numberPassenger'=>$trip[$i]->numberPassenger,
                        'dateStart'=>date('d-m-Y',strtotime($trip[$i]->startDate)).' '.date('h:m A',strtotime($trip[$i]->startTime)),
                        'dateEnd'=>date('d-m-Y',strtotime($trip[$i]->endDate)).' '.date('h:m A',strtotime($trip[$i]->endTime)),
                        'from'=>Destination::find(Path::find($trip[$i]->pathID)->from)->translate($request->lang)->name,
                        'to'=>Destination::find(Path::find($trip[$i]->pathID)->to)->translate($request->lang)->name,
                        'latStart'=>Destination::find(Path::find($trip[$i]->pathID)->from)->lat,
                        'lngStart'=>Destination::find(Path::find($trip[$i]->pathID)->from)->lng,
                        'latEnd'=>Destination::find(Path::find($trip[$i]->pathID)->to)->lat,
                        'lngEnd'=>Destination::find(Path::find($trip[$i]->pathID)->to)->lng,
                        'price'=>$trip[$i]->price,
                        'status'=>$status,
                        'statusId'=>$trip[$i]->status,
                    ];
            }
            return $this->apiResponse($details,'',200);
        }



    }

    #getTripsGuide
    public function getTripsGuide(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'lang'=>'required|in:en,ar',
                'user_token'=>'required|exists:guides,user_token',
            ]);

        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }
        $guide = \DB::table('guides')
            ->where('user_token','=',$request->user_token)->first();

        $trip = \DB::table('trips')
            ->join('trip_translations','trip_translations.trip_id','=','trips.id')
            /*->where('trips.status','=',1)*/
            ->where('trips.guide_id','=',$guide->id)
            ->where('trip_translations.locale','=',$request->lang)
            ->select('trips.id as id','trip_translations.name as name','trips.company_id as companyId'
                ,'trips.guide_id as guideId','trips.driver_id as driverId',
                'trips.bus_id as busId','trips.start_time as startTime','trips.number_passenger as numberPassenger'
                ,'trips.end_time as endTime','trips.start_date as startDate','trips.end_date as endDate'
                ,'trips.path_id as pathID','trips.price as price','trips.status as status',
                'trips.supervisor_id as supervisorId')
            ->get();

        if ($trip->isEmpty())
        {
            return $this->apiResponse('','not found data',404);
        }
        else
        {
            for ($i=0; $i<count($trip); $i++)
            {
                $status = '';
                if ($trip[$i]->status == 1)
                {
                    $status = 'مسندة';
                }
                elseif ($trip[$i]->status == 2)
                {
                    $status = 'قيد التنفيذ';
                }
                elseif ($trip[$i]->status == 3)
                {
                    $status = 'معلق';
                }
                elseif ($trip[$i]->status == 4)
                {
                    $status = 'ملغية';
                }
                elseif ($trip[$i]->status == 5)
                {
                    $status = 'مقفل نهائى';
                }
                elseif ($trip[$i]->status == 6)
                {
                    $status = 'مقفل جزئى';
                }
                elseif ($trip[$i]->status == 7)
                {
                    $status = 'مجدولة';
                }
                elseif ($trip[$i]->status == 10)
                {
                    $status = 'فورية';
                }
                else
                {
                    $status = 'لا توجد هذا الرحلة';
                }
                $details[] =
                    [

                        'tripId'=>$trip[$i]->id,
                        'tripName'=>$trip[$i]->name,
                        'companyId'=>$trip[$i]->companyId,
                        'companyName'=>\DB::table('company_translations')
                            ->where('company_id','=',$trip[$i]->companyId)->where('locale','=',$request->lang)
                            ->value('name'),
                         'guideId'=>$trip[$i]->guideId,
                        'guideName'=>\DB::table('guide_translations')
                            ->where('guide_id','=',$trip[$i]->guideId)->where('locale','=',$request->lang)
                            ->value('name'),
                        'supervisorId'=>$trip[$i]->supervisorId,
                        'supervisorName'=>\DB::table('supervisor_translations')
                            ->where('supervisor_id','=',$trip[$i]->supervisorId)->where('locale','=',$request->lang)
                            ->value('name'),
                         'driverId'=>$trip[$i]->driverId,
                        'driverName'=>\DB::table('driver_translations')
                            ->where('driver_id','=',$trip[$i]->driverId)->where('locale','=',$request->lang)
                            ->value('name'),
                        /*'busId'=>$trip[$i]->busId,*/
                        'busName'=>\DB::table('bus_translations')
                            ->where('bus_id','=',$trip[$i]->busId)->where('locale','=',$request->lang)
                            ->value('name'),
                        'numberPassenger'=>$trip[$i]->numberPassenger,
                        'dateStart'=>date('d-m-Y',strtotime($trip[$i]->startDate)).' '.date('h:m A',strtotime($trip[$i]->startTime)),
                        'dateEnd'=>date('d-m-Y',strtotime($trip[$i]->endDate)).' '.date('h:m A',strtotime($trip[$i]->endTime)),
                        'from'=>Destination::find(Path::find($trip[$i]->pathID)->from)->translate($request->lang)->name,
                        'to'=>Destination::find(Path::find($trip[$i]->pathID)->to)->translate($request->lang)->name,
                        'latStart'=>Destination::find(Path::find($trip[$i]->pathID)->from)->lat,
                        'lngStart'=>Destination::find(Path::find($trip[$i]->pathID)->from)->lng,
                        'latEnd'=>Destination::find(Path::find($trip[$i]->pathID)->to)->lat,
                        'lngEnd'=>Destination::find(Path::find($trip[$i]->pathID)->to)->lng,
                        'price'=>$trip[$i]->price,
                        'status'=>$status,
                        'statusId'=>$trip[$i]->status,
                    ];
            }
            return $this->apiResponse($details,'',200);
        }



    }

    #requestPauseTrip
    public function requestPauseTrip(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_token'=>'required', // user token guide
                'trip_id'=>'required|exists:trips,id',
                'headings'=>'required',
                'message'=>'required',
            ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors()->first(),'',200);
        }
        $guide = Guide::where('user_token',$request->user_token)->first();

        if (empty($guide))
        {
            return $this->apiResponse('','please check supervisors',404);
        }
        else
        {
            $trips = Trip::find($request->trip_id);

            if (empty($trips))
            {
                return $this->apiResponse('','not found this trips',404);
            }
            else
            {
                $tripStart = new RequestPauseTrip();
                $tripStart->trip_id = $trips->id;
                $tripStart->company_id = $trips->company_id;
                $tripStart->supervisor_id = $trips->supervisor_id;
                $tripStart->driver_id = $trips->driver_id;
                $tripStart->guide_id = $trips->guide_id;
                $tripStart->bus_id = $trips->bus_id;
                $tripStart->path_id = $trips->path_id;
                $tripStart->start = Carbon::now();
                $tripStart->status = $trips->status;
                $tripStart->save();
                if ($tripStart->save())
                {
                    $playerGuide = Guide::find($trips->guide_id);
                    $playerSupervisor = Supervisor::find($trips->supervisor_id);
                    /* $content = array(
                         "en" => $request->message,
                     );

                     $headings = array(
                         "en" => $request->headings,
                     );
                     $fields = array(
                         'app_id' =>"ffba68ac-bdcc-4dbd-80ce-cd47738d8173",
                         'include_player_ids' => array("$playerSupervisor->player_ids","$playerGuide->player_ids"),
                         'data' => array("foo" => "bar"),
                         'contents' => $content,
                         'headings'=>$headings,
                     );

                     $fields = json_encode($fields);
                     $ch = curl_init();
                     curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                     curl_setopt($ch, CURLOPT_HEADER, FALSE);
                     curl_setopt($ch, CURLOPT_POST, TRUE);
                     curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

                     $response = curl_exec($ch);
                     curl_close($ch);*/
                    $notification = new Notification();
                    $notification->trip_id = $tripStart->trip_id;
                    $notification->company_id = $tripStart->company_id;
                    $notification->supervisor_id = $tripStart->supervisor_id;
                    $notification->driver_id = $tripStart->driver_id;
                    $notification->guide_id = $tripStart->guide_id;
                    $notification->message = $request->message;
                    $notification->headings = $request->headings;
                    $notification->save();
                    if ($notification->save())
                    {
                        return $this->apiResponse('success send request to supervisor '.Supervisor::find($notification->supervisor_id)->name,'',200);

                    }
                    else
                    {
                        return $this->apiResponse('please try again','',500);
                    }

                }
                else
                {
                    return $this->apiResponse('please try again','',500);
                }
            }
        }
    }

    #getRequestPauseTrip
    public function getRequestPauseTrip (Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_token'=>'required', // user token supervisor
            ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors()->first(),'',200);
        }
        $supervisors = Supervisor::where('user_token',$request->user_token)->first();

        if (empty($supervisors))
        {
            return $this->apiResponse('','please check supervisors',404);
        }
        else
        {
            $getRequestPauseTrip = RequestPauseTrip::where('supervisor_id',$supervisors->id)->get();
            if ($getRequestPauseTrip->isEmpty())
            {
                return $this->apiResponse('','not found data',404);
            }
            else
            {
                for ($i=0; $i<count($getRequestPauseTrip); $i++)
                {
                    $details[] =
                        [
                            'requestId'=>$getRequestPauseTrip[$i]->id,
                            'companyId'=>$getRequestPauseTrip[$i]->company_id,
                            'driver_id'=>$getRequestPauseTrip[$i]->driver_id,
                            'driverName'=>Driver::find($getRequestPauseTrip[$i]->driver_id)->name,
                            'driverPhone'=>Driver::find($getRequestPauseTrip[$i]->driver_id)->phone,
                            'guideId'=>$getRequestPauseTrip[$i]->guide_id,
                            'guideName'=>Guide::find($getRequestPauseTrip[$i]->guide_id)->name,
                            'guidePhone'=>Guide::find($getRequestPauseTrip[$i]->guide_id)->phone,
                            'busId'=>$getRequestPauseTrip[$i]->bus_id,
                            'busNumber'=>Bus::find($getRequestPauseTrip[$i]->bus_id)->number_bus,
                            'pathId'=>$getRequestPauseTrip[$i]->path_id,
                            'from'=>Destination::find(Path::find($getRequestPauseTrip[$i]->path_id)->from)->name,
                            'to'=>Destination::find(Path::find($getRequestPauseTrip[$i]->path_id)->to)->name,
                            'startTime'=>date('h:m A',strtotime($getRequestPauseTrip[$i]->start)),
                            'startDate'=>date('d-m-Y',strtotime($getRequestPauseTrip[$i]->start)),
                            'tripId'=>$getRequestPauseTrip[$i]->trip_id,
                            'status'=>$getRequestPauseTrip[$i]->status,
                        ];
                }
                return $this->apiResponse($details,'',200);
            }
        }
    }

    #getRequestPauseTripGuide
    public function getRequestPauseTripGuide (Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_token'=>'required', // user token guide
            ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors()->first(),'',200);
        }
        $guide = Guide::where('user_token',$request->user_token)->first();

        if (empty($guide))
        {
            return $this->apiResponse('','please check supervisors',404);
        }
        else
        {
            $getRequestPauseTrip = RequestPauseTrip::where('guide_id',$guide->id)->get();
            if ($getRequestPauseTrip->isEmpty())
            {
                return $this->apiResponse('','not found data',404);
            }
            else
            {
                for ($i=0; $i<count($getRequestPauseTrip); $i++)
                {
                    $details[] =
                        [
                            'requestId'=>$getRequestPauseTrip[$i]->id,
                            'companyId'=>$getRequestPauseTrip[$i]->company_id,
                            'driver_id'=>$getRequestPauseTrip[$i]->driver_id,
                            'driverName'=>Driver::find($getRequestPauseTrip[$i]->driver_id)->name,
                            'guideId'=>$getRequestPauseTrip[$i]->guide_id,
                            'guideName'=>Guide::find($getRequestPauseTrip[$i]->guide_id)->name,
                            'busId'=>$getRequestPauseTrip[$i]->bus_id,
                            'busNumber'=>Bus::find($getRequestPauseTrip[$i]->bus_id)->number_bus,
                            'pathId'=>$getRequestPauseTrip[$i]->path_id,
                            'from'=>Destination::find(Path::find($getRequestPauseTrip[$i]->path_id)->from)->name,
                            'to'=>Destination::find(Path::find($getRequestPauseTrip[$i]->path_id)->to)->name,
                            'startTime'=>date('h:m A',strtotime($getRequestPauseTrip[$i]->start)),
                            'startDate'=>date('d-m-Y',strtotime($getRequestPauseTrip[$i]->start)),
                            'tripId'=>$getRequestPauseTrip[$i]->trip_id,
                            'status'=>$getRequestPauseTrip[$i]->status,
                            'type'=>$getRequestPauseTrip[$i]->type == 'yes' ? 'Yrs':'No',
                        ];
                }
                return $this->apiResponse($details,'',200);
            }
        }
    }

    #requestAnswer
    public function requestAnswer(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_token'=>'required', // user token supervisor
                'requestId'=>'required|exists:request_pause_trips,id',
                'type'=>'required|in:yes,no',
                'headings'=>'required',
                'message'=>'required',
            ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors()->first(),'',200);
        }
        $supervisor = Supervisor::where('user_token',$request->user_token)->first();

        if (empty($supervisor))
        {
            return $this->apiResponse('','please check supervisors',404);
        }
        else
        {
            $requestTrips = RequestPauseTrip::find($request->requestId);
            if (empty($requestTrips))
            {
                return $this->apiResponse('','please check request send first',404);
            }
            else
            {
                $requestTrips->type = $request->type;
                if ($requestTrips->save())
                {
                    if ($requestTrips->type == 'yes')
                    {
                        $playerDriver = Driver::find($requestTrips->driver_id);
                        $playerGuide = Guide::find($requestTrips->guide_id);
                        /* $content = array(
                             "en" => $request->message,
                         );

                         $headings = array(
                             "en" => $request->headings,
                         );
                         $fields = array(
                             'app_id' =>"ffba68ac-bdcc-4dbd-80ce-cd47738d8173",
                             'include_player_ids' => array("$playerDriver->player_ids","$playerGuide->player_ids"),
                             'data' => array("foo" => "bar"),
                             'contents' => $content,
                             'headings'=>$headings,
                         );

                         $fields = json_encode($fields);
                         $ch = curl_init();
                         curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                         curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                         curl_setopt($ch, CURLOPT_HEADER, FALSE);
                         curl_setopt($ch, CURLOPT_POST, TRUE);
                         curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

                         $response = curl_exec($ch);
                         curl_close($ch);*/
                        $notification = new Notification();
                        $notification->trip_id = $requestTrips->trip_id;
                        $notification->company_id = $requestTrips->company_id;
                        $notification->supervisor_id = $requestTrips->supervisor_id;
                        $notification->driver_id = $requestTrips->driver_id;
                        $notification->guide_id = $requestTrips->guide_id;
                        $notification->message = $request->message;
                        $notification->headings = $request->headings;
                        $notification->save();
                        if ($notification->save())
                        {
                           $trip = Trip::find($requestTrips->trip_id);
                           $trip->status = 3; // لم يرسل طلب لتعلق الرحلة
                           $trip->save();
                           if ($trip->save())
                           {
                               return $this->apiResponse('done send notification to guide '.Guide::find($notification->guide_id)->name,'',200);
                           }
                           else
                           {
                               return $this->apiResponse('please try again','',500);
                           }
                        }
                        else
                        {
                            return $this->apiResponse('please try again','',500);
                        }

                    }
                    else
                    {
                        return $this->apiResponse('done send notification to guide '.Guide::find($requestTrips->guide_id)->name,'',200);
                    }

                }
            }
        }
    }

    #getNotifications
    public function getNotifications(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_token'=>'required',
                'type'=>'required|in:supervisors,drivers,guides',
            ]);
        $type = \DB::table($request->type)->where('user_token','=',$request->user_token)->first();

        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }
        else
        {
            if ($request->type == 'supervisors')
            {
                $notification = Notification::where('supervisor_id',$type->id)->orderBy('created_at','DESC')->get();
                if ($notification->isEmpty())
                {
                    return $this->apiResponse('','not found notification',404);
                }
                else
                {
                    for ($i=0; $i<count($notification); $i++)
                    {
                        $details[] =
                            [
                               'supervisorName'=>Supervisor::find($notification[$i]->supervisor_id)->name,
                                'driverName'=>Driver::find($notification[$i]->driver_id)->name,
                                'guideName'=>Guide::find($notification[$i]->guide_id)->name,
                                'headings'=>$notification[$i]->headings,
                                'message'=>$notification[$i]->message,
                            ];
                    }
                    return $this->apiResponse($details,'',200);
                }

            }
            elseif ($request->type == 'drivers')
            {
                $notification = Notification::where('driver_id',$type->id)->orderBy('created_at','DESC')->get();
                if ($notification->isEmpty())
                {
                    return $this->apiResponse('','not found notification',404);
                }
                else
                {
                    for ($i=0; $i<count($notification); $i++)
                    {
                        $details[] =
                            [
                                'supervisorName'=>Supervisor::find($notification[$i]->supervisor_id)->name,
                                'driverName'=>Driver::find($notification[$i]->driver_id)->name,
                                'guideName'=>Guide::find($notification[$i]->guide_id)->name,
                                'headings'=>$notification[$i]->headings,
                                'message'=>$notification[$i]->message,
                            ];
                    }
                    return $this->apiResponse($details,'',200);
                }
            }
            elseif ($request->type == 'guides')
            {
                $notification = Notification::where('guide_id',$type->id)->orderBy('created_at','DESC')->get();
                if ($notification->isEmpty())
                {
                    return $this->apiResponse('','not found notification',404);
                }
                else
                {
                    for ($i=0; $i<count($notification); $i++)
                    {
                        $details[] =
                            [
                                'supervisorName'=>Supervisor::find($notification[$i]->supervisor_id)->name,
                                'driverName'=>Driver::find($notification[$i]->driver_id)->name,
                                'guideName'=>Guide::find($notification[$i]->guide_id)->name,
                                'headings'=>$notification[$i]->headings,
                                'message'=>$notification[$i]->message,
                            ];
                    }
                    return $this->apiResponse($details,'',200);
                }
            }
            else
            {
                return $this->apiResponse('','please select type first',400);
            }
        }
    }

    #endTripSupervisor
    public function endTripSupervisor(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_token'=>'required', // supervisor
                'trip_id'=>'required|exists:trips,id',
                'headings'=>'required',
                'message'=>'required',
            ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors()->first(),'',200);
        }
        $supervisor = Supervisor::where('user_token',$request->user_token)->first();

        if (empty($supervisor))
        {
            return $this->apiResponse('','please check Supervisor',404);
        }
        else
        {
            $trips = Trip::find($request->trip_id);

            if (empty($trips))
            {
                return $this->apiResponse('','not found this trips',404);
            }
            else
            {
                $tripStart = new EndTrip();
                $tripStart->trip_id = $trips->id;
                $tripStart->company_id = $trips->company_id;
                $tripStart->supervisor_id = $supervisor->id;
                $tripStart->driver_id = $trips->driver_id;
                $tripStart->guide_id = $trips->guide_id;
                $tripStart->bus_id = $trips->bus_id;
                $tripStart->path_id = $trips->path_id;
                //$tripStart->start = Carbon::now();
                $tripStart->end = Carbon::now();
                $tripStart->save();
                if ($tripStart->save())
                {

                    $playerSupervisor = Supervisor::find($supervisor->id);
                    $playerDriver = Driver::find($trips->driver_id);
                    $playerGuide = Guide::find($trips->guide_id);
                    $playerMember = Member::find($playerSupervisor->member_id);

                    $sendMember = new TripMemeber();
                    $sendMember->member_id = $playerMember->id ;
                    $sendMember->trip_id = $tripStart->trip_id ;
                    $sendMember->company_id = $tripStart->company_id ;
                    $sendMember->type = 'supervisor';
                    $sendMember->user_id = $tripStart->driver_id ;
                    $sendMember->sendDriver = 1 ;
                    $sendMember->save();
                    /* $content = array(
                         "en" => $request->message,
                     );

                     $headings = array(
                         "en" => $request->headings,
                     );
                     $fields = array(
                         'app_id' =>"ffba68ac-bdcc-4dbd-80ce-cd47738d8173",
                         'include_player_ids' => array("$playerDriver->player_ids","$playerGuide->player_ids"),
                         'data' => array("foo" => "bar"),
                         'contents' => $content,
                         'headings'=>$headings,
                     );

                     $fields = json_encode($fields);
                     $ch = curl_init();
                     curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                     curl_setopt($ch, CURLOPT_HEADER, FALSE);
                     curl_setopt($ch, CURLOPT_POST, TRUE);
                     curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

                     $response = curl_exec($ch);
                     curl_close($ch);*/
                    $notification = new Notification();
                    $notification->trip_id = $tripStart->trip_id;
                    $notification->company_id = $tripStart->company_id;
                    $notification->supervisor_id = $tripStart->supervisor_id;
                    $notification->driver_id = $tripStart->driver_id;
                    $notification->guide_id = $tripStart->guide_id;
                    $notification->message = $request->message;
                    $notification->headings = $request->headings;
                    $notification->save();
                    if ($notification->save())
                    {
                        $trips->status =  6; // الرحلة مغلق جزئى
                        $trips->save();
                        if ($trips->save())
                        {
                            return $this->apiResponse('success trips end for supervisor','',200);
                        }
                        else
                        {
                            return $this->apiResponse('please try again','',500);
                        }

                    }
                    else
                    {
                        return $this->apiResponse('please try again','',500);
                    }

                }
                else
                {
                    return $this->apiResponse('please try again','',500);
                }
            }
        }
    }

    #endTripDriver
    public function endTripDriver(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_token'=>'required', // driver
                'trip_id'=>'required|exists:trips,id',
                'headings'=>'required',
                'message'=>'required',
            ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors()->first(),'',200);
        }
        $driver = Driver::where('user_token',$request->user_token)->first();

        if (empty($driver))
        {
            return $this->apiResponse('','please check driver',404);
        }
        else
        {
            $trips = Trip::find($request->trip_id);

            if (empty($trips))
            {
                return $this->apiResponse('','not found this trips',404);
            }
            else
            {
                $tripStart = new EndTrip();
                $tripStart->trip_id = $trips->id;
                $tripStart->company_id = $trips->company_id;
                $tripStart->supervisor_id = $trips->supervisor_id;
                $tripStart->driver_id = $trips->driver_id;
                $tripStart->guide_id = $trips->guide_id;
                $tripStart->bus_id = $trips->bus_id;
                $tripStart->path_id = $trips->path_id;
                //$tripStart->start = Carbon::now();
                $tripStart->end = Carbon::now();
                $tripStart->save();
                if ($tripStart->save())
                {

                    $playerSupervisor = Supervisor::find($trips->supervisor_id);
                    $playerDriver = Driver::find($trips->driver_id);
                    $playerGuide = Guide::find($trips->guide_id);
                    $playerMember = Member::find($playerSupervisor->member_id);

                    $sendMember = new TripMemeber();
                    $sendMember->member_id = $playerMember->id ;
                    $sendMember->trip_id = $tripStart->trip_id ;
                    $sendMember->company_id = $tripStart->company_id ;
                    $sendMember->type = 'driver';
                    $sendMember->user_id = $tripStart->driver_id ;
                    $sendMember->sendDriver = 1 ;
                    $sendMember->save();
                        /* $content = array(
                             "en" => $request->message,
                         );

                         $headings = array(
                             "en" => $request->headings,
                         );
                         $fields = array(
                             'app_id' =>"ffba68ac-bdcc-4dbd-80ce-cd47738d8173",
                             'include_player_ids' => array("$playerDriver->player_ids","$playerGuide->player_ids"),
                             'data' => array("foo" => "bar"),
                             'contents' => $content,
                             'headings'=>$headings,
                         );

                         $fields = json_encode($fields);
                         $ch = curl_init();
                         curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                         curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                         curl_setopt($ch, CURLOPT_HEADER, FALSE);
                         curl_setopt($ch, CURLOPT_POST, TRUE);
                         curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

                         $response = curl_exec($ch);
                         curl_close($ch);*/
                    $notification = new Notification();
                    $notification->trip_id = $tripStart->trip_id;
                    $notification->company_id = $tripStart->company_id;
                    $notification->supervisor_id = $tripStart->supervisor_id;
                    $notification->driver_id = $tripStart->driver_id;
                    $notification->guide_id = $tripStart->guide_id;
                    $notification->message = $request->message;
                    $notification->headings = $request->headings;
                    $notification->save();
                    if ($notification->save())
                    {
                        $trips->status =  6; // الرحلة مغلق جزئى
                        $trips->save();
                        if ($trips->save())
                        {
                            return $this->apiResponse('success trips end from driver','',200);
                        }
                        else
                        {
                            return $this->apiResponse('please try again','',500);
                        }

                    }
                    else
                    {
                        return $this->apiResponse('please try again','',500);
                    }

                }
                else
                {
                    return $this->apiResponse('please try again','',500);
                }
            }
        }
    }

    #endTripGuide
    public function endTripGuide(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_token'=>'required', // guide
                'trip_id'=>'required|exists:trips,id',
                'headings'=>'required',
                'message'=>'required',
            ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors()->first(),'',200);
        }
        $guide = Guide::where('user_token',$request->user_token)->first();

        if (empty($guide))
        {
            return $this->apiResponse('','please check guide',404);
        }
        else
        {
            $trips = Trip::find($request->trip_id);

            if (empty($trips))
            {
                return $this->apiResponse('','not found this trips',404);
            }
            else
            {
                $tripStart = new EndTrip();
                $tripStart->trip_id = $trips->id;
                $tripStart->company_id = $trips->company_id;
                $tripStart->supervisor_id = $trips->supervisor_id;
                $tripStart->driver_id = $trips->driver_id;
                $tripStart->guide_id = $trips->guide_id;
                $tripStart->bus_id = $trips->bus_id;
                $tripStart->path_id = $trips->path_id;
                //$tripStart->start = Carbon::now();
                $tripStart->end = Carbon::now();
                $tripStart->save();
                if ($tripStart->save())
                {

                    $playerSupervisor = Supervisor::find($trips->supervisor_id);
                    $playerDriver = Driver::find($trips->driver_id);
                    $playerGuide = Guide::find($trips->guide_id);
                    $playerMember = Member::find($playerSupervisor->member_id);

                    $sendMember = new TripMemeber();
                    $sendMember->member_id = $playerMember->id ;
                    $sendMember->trip_id = $tripStart->trip_id ;
                    $sendMember->company_id = $tripStart->company_id ;
                    $sendMember->type = 'guide';
                    $sendMember->user_id = $tripStart->guide_id ;
                    $sendMember->sendGuide = 1 ;
                    $sendMember->save();
                    /* $content = array(
                         "en" => $request->message,
                     );

                     $headings = array(
                         "en" => $request->headings,
                     );
                     $fields = array(
                         'app_id' =>"ffba68ac-bdcc-4dbd-80ce-cd47738d8173",
                         'include_player_ids' => array("$playerDriver->player_ids","$playerGuide->player_ids"),
                         'data' => array("foo" => "bar"),
                         'contents' => $content,
                         'headings'=>$headings,
                     );

                     $fields = json_encode($fields);
                     $ch = curl_init();
                     curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                     curl_setopt($ch, CURLOPT_HEADER, FALSE);
                     curl_setopt($ch, CURLOPT_POST, TRUE);
                     curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

                     $response = curl_exec($ch);
                     curl_close($ch);*/
                    $notification = new Notification();
                    $notification->trip_id = $tripStart->trip_id;
                    $notification->company_id = $tripStart->company_id;
                    $notification->supervisor_id = $tripStart->supervisor_id;
                    $notification->driver_id = $tripStart->driver_id;
                    $notification->guide_id = $tripStart->guide_id;
                    $notification->message = $request->message;
                    $notification->headings = $request->headings;
                    $notification->save();
                    if ($notification->save())
                    {
                        $trips->status =  6; // الرحلة مغلق جزئى
                        $trips->save();
                        if ($trips->save())
                        {
                            return $this->apiResponse('success trips end form guide','',200);
                        }
                        else
                        {
                            return $this->apiResponse('please try again','',500);
                        }

                    }
                    else
                    {
                        return $this->apiResponse('please try again','',500);
                    }

                }
                else
                {
                    return $this->apiResponse('please try again','',500);
                }
            }
        }
    }

    #getRequestMember
    public function getRequestMember(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_token'=>'required', // member
            ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors()->first(),'',200);
        }
        $member = Member::where('user_token',$request->user_token)->first();

        if (empty($member))
        {
            return $this->apiResponse('','please check guide',404);
        }
        else
        {
            $trip_memebers = TripMemeber::where('member_id',$member->id)->get();

            if ($trip_memebers->isEmpty())
            {
               return $this->apiResponse('','not found request member',404);
            }
            else
            {
                for ($i=0; $i<count($trip_memebers); $i++)
                {
                    if ($trip_memebers[$i]->type == 'driver')
                    {
                        $type = 'Driver';
                        $userId = $trip_memebers[$i]->user_id;
                        $userName = Driver::find($trip_memebers[$i]->user_id)->name;
                        $send = $trip_memebers[$i]->sendDriver == 0 ? 'Not Send' : 'Send';
                    }
                    else
                    {
                        $type = 'Guide';
                        $userId = $trip_memebers[$i]->user_id;
                        $userName = Guide::find($trip_memebers[$i]->user_id)->name;
                        $send = $trip_memebers[$i]->sendGuide == 0 ? 'Not Send' : 'Send';
                    }
                    $details [] = [
                        'tripId'=>$trip_memebers[$i]->trip_id,
                        'type'=>$type,
                        'userId'=>$userId,
                        'userName'=>$userName,
                        'send'=>$send,
                    ] ;
                }
                return $this->apiResponse($details,'',200);
            }
        }
    }

    #getRequestMemberByTripsID
    public function getRequestMemberByTripsID(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_token'=>'required', // member
                'trip_id' =>'required'
            ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors()->first(),'',200);
        }
        $member = Member::where('user_token',$request->user_token)->first();

        if (empty($member))
        {
            return $this->apiResponse('','please check guide',404);
        }
        else
        {
            $trip_memebers = TripMemeber::where('member_id',$member->id)
                ->where('trip_id',$request->trip_id)
                ->get();

            if ($trip_memebers->isEmpty())
            {
                return $this->apiResponse('','not found request member',404);
            }
            else
            {
                for ($i=0; $i<count($trip_memebers); $i++)
                {
                    if ($trip_memebers[$i]->type == 'driver')
                    {
                        $type = 'Driver';
                        $userId = $trip_memebers[$i]->user_id;
                        $userName = Driver::find($trip_memebers[$i]->user_id)->name;
                        $send = $trip_memebers[$i]->sendDriver == 0 ? 'Not Send' : 'Send';
                    }
                    else
                    {
                        $type = 'Guide';
                        $userId = $trip_memebers[$i]->user_id;
                        $userName = Guide::find($trip_memebers[$i]->user_id)->name;
                        $send = $trip_memebers[$i]->sendGuide == 0 ? 'Not Send' : 'Send';
                    }
                    $details [] = [
                        'tripId'=>$trip_memebers[$i]->trip_id,
                        'type'=>$type,
                        'userId'=>$userId,
                        'userName'=>$userName,
                        'send'=>$send,
                    ] ;
                }
                return $this->apiResponse($details,'',200);
            }
        }
    }

    #answerRequestMember
    public function answerRequestMember(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_token'=>'required', // member
                'trip_id'=>'required|exists:trips,id',
                'headings'=>'required',
                'message'=>'required',
            ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors()->first(),'',200);
        }
        $member = Member::where('user_token',$request->user_token)->first();

        if (empty($member))
        {
            return $this->apiResponse('','please check guide',404);
        }
        else
        {
            $trips = Trip::find($request->trip_id);

            if (empty($trips))
            {
                return $this->apiResponse('','not found this trips',404);
            }
            else
            {
                $tripStart = new EndTrip();
                $tripStart->trip_id = $trips->id;
                $tripStart->company_id = $trips->company_id;
                $tripStart->supervisor_id = $trips->supervisor_id;
                $tripStart->driver_id = $trips->driver_id;
                $tripStart->guide_id = $trips->guide_id;
                $tripStart->bus_id = $trips->bus_id;
                $tripStart->path_id = $trips->path_id;
                //$tripStart->start = Carbon::now();
                $tripStart->end = Carbon::now();
                $tripStart->save();
                if ($tripStart->save())
                {

                    $playerSupervisor = Supervisor::find($trips->supervisor_id);
                    $playerDriver = Driver::find($trips->driver_id);
                    $playerGuide = Guide::find($trips->guide_id);
                    $playerMember = Member::find($playerSupervisor->member_id);

                    $sendMember =TripMemeber::where('trip_id',$request->trip_id)->get();
                    for ($i=0; $i<count($sendMember); $i++)
                    {
                        $sendMember[$i]->status = 1;
                        $sendMember[$i]->save();

                    }
                    /* $content = array(
                         "en" => $request->message,
                     );

                     $headings = array(
                         "en" => $request->headings,
                     );
                     $fields = array(
                         'app_id' =>"ffba68ac-bdcc-4dbd-80ce-cd47738d8173",
                         'include_player_ids' => array("$playerDriver->player_ids","$playerGuide->player_ids"),
                         'data' => array("foo" => "bar"),
                         'contents' => $content,
                         'headings'=>$headings,
                     );

                     $fields = json_encode($fields);
                     $ch = curl_init();
                     curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                     curl_setopt($ch, CURLOPT_HEADER, FALSE);
                     curl_setopt($ch, CURLOPT_POST, TRUE);
                     curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

                     $response = curl_exec($ch);
                     curl_close($ch);*/
                    $notification = new Notification();
                    $notification->trip_id = $tripStart->trip_id;
                    $notification->company_id = $tripStart->company_id;
                    $notification->supervisor_id = $tripStart->supervisor_id;
                    $notification->driver_id = $tripStart->driver_id;
                    $notification->guide_id = $tripStart->guide_id;
                    $notification->message = $request->message;
                    $notification->headings = $request->headings;
                    $notification->save();
                    if ($notification->save())
                    {
                        $trips->status =  5; // الرحلة مغلق نهائى
                        $trips->save();
                        if ($trips->save())
                        {
                            return $this->apiResponse('success trips end','',200);
                        }
                        else
                        {
                            return $this->apiResponse('please try again','',500);
                        }

                    }
                    else
                    {
                        return $this->apiResponse('please try again','',500);
                    }

                }
                else
                {
                    return $this->apiResponse('please try again','',500);
                }
            }
        }

    }

    public function getSupervisors(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_token'=>'required', // member
            ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors()->first(),'',200);
        }
        $member = Member::where('user_token',$request->user_token)->first();

        if (empty($member))
        {
            return $this->apiResponse('','please check guide',404);
        }
        else
        {
           $supervisor = Supervisor::where('member_id',$member->id)->get();
           if ($supervisor->isEmpty())
           {
               return $this->apiResponse('','not found supervisors',404);
           }
           else
           {
               for ($i=0; $i<count($supervisor); $i++)
               {
                   $details[] =
                       [
                           'id'=>$supervisor[$i]->id,
                           'name'=>SupervisorTranslation::where('supervisor_id',$supervisor[$i]->id)->where('locale','ar')->value('name'),
                           'image'=>$supervisor[$i]->imagePath,
                           'userToken'=>$supervisor[$i]->user_token,
                           'phone'=>$supervisor[$i]->phone,
                           'mobile'=>$supervisor[$i]->mobile,
                           'address'=>$supervisor[$i]->address,
                           'ssn'=>$supervisor[$i]->ssn,
                           'nationality'=>$supervisor[$i]->nationality,
                           'player_ids'=>$supervisor[$i]->player_ids,
                           'status'=>$supervisor[$i]->status == 1 ? 'نشط' : 'غير نشط',

                       ];
               }
               return $this->apiResponse($details,'',200);
           }
        }
    }

    public function getTripsMemberSupervisor(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'supervisor_id'=>'required|exists:supervisors,id', // member
            ]);
        if ($validator->fails()) {
            return $this->apiResponse($validator->errors()->first(),'',200);
        }
        $supervisor = Supervisor::find($request->supervisor_id)->first();

        if (empty($supervisor))
        {
            return $this->apiResponse('','this supervisor not found',404);
        }
        else
        {
            $trip = \DB::table('trips')
                ->join('trip_translations','trip_translations.trip_id','=','trips.id')
                ->where('trips.supervisor_id','=',$supervisor->id)
                ->where('trip_translations.locale','=','ar')
                ->select('trips.id as id','trip_translations.name as name','trips.company_id as companyId'
                    ,'trips.guide_id as guideId','trips.driver_id as driverId',
                    'trips.bus_id as busId','trips.start_time as startTime','trips.number_passenger as numberPassenger'
                    ,'trips.end_time as endTime','trips.start_date as startDate','trips.end_date as endDate'
                    ,'trips.path_id as pathID','trips.price as price','trips.status as status')
                ->get();

            if ($trip->isEmpty())
            {
                return $this->apiResponse('','not found data',404);
            }
            else
            {
                for ($i=0; $i<count($trip); $i++)
                {
                    $status = '';
                    if ($trip[$i]->status == 1)
                    {
                        $status = 'مسندة';
                    }
                    elseif ($trip[$i]->status == 2)
                    {
                        $status = 'قيد التنفيذ';
                    }
                    elseif ($trip[$i]->status == 3)
                    {
                        $status = 'معلق';
                    }
                    elseif ($trip[$i]->status == 4)
                    {
                        $status = 'ملغية';
                    }
                    elseif ($trip[$i]->status == 5)
                    {
                        $status = 'مقفل نهائى';
                    }
                    elseif ($trip[$i]->status == 6)
                    {
                        $status = 'مقفل جزئى';
                    }
                    elseif ($trip[$i]->status == 7)
                    {
                        $status = 'مجدولة';
                    }
                    elseif ($trip[$i]->status == 10)
                    {
                        $status = 'فورية';
                    }
                    else
                    {
                        $status = 'لا توجد هذا الرحلة';
                    }
                    $details[] =
                        [

                            'tripId'=>$trip[$i]->id,
                            'tripName'=>$trip[$i]->name,
                            'companyId'=>$trip[$i]->companyId,
                            'companyName'=>\DB::table('company_translations')
                                ->where('company_id','=',$trip[$i]->companyId)->where('locale','=',$request->lang)
                                ->value('name'),
                            /* 'guideId'=>$trip[$i]->guideId,*/
                            'guideName'=>\DB::table('guide_translations')
                                ->where('guide_id','=',$trip[$i]->guideId)->where('locale','=',$request->lang)
                                ->value('name'),
                            /* 'driverId'=>$trip[$i]->driverId,*/
                            'driverName'=>\DB::table('driver_translations')
                                ->where('driver_id','=',$trip[$i]->driverId)->where('locale','=',$request->lang)
                                ->value('name'),
                            /*'busId'=>$trip[$i]->busId,*/
                            'busName'=>\DB::table('bus_translations')
                                ->where('bus_id','=',$trip[$i]->busId)->where('locale','=',$request->lang)
                                ->value('name'),
                            'numberPassenger'=>$trip[$i]->numberPassenger,
                            'dateStart'=>date('d-m-Y',strtotime($trip[$i]->startDate)).' '.date('h:m A',strtotime($trip[$i]->startTime)),
                            'dateEnd'=>date('d-m-Y',strtotime($trip[$i]->endDate)).' '.date('h:m A',strtotime($trip[$i]->endTime)),
                            'from'=>Destination::find(Path::find($trip[$i]->pathID)->from)->translate($request->lang)->name,
                            'to'=>Destination::find(Path::find($trip[$i]->pathID)->to)->translate($request->lang)->name,
                            'latStart'=>Destination::find(Path::find($trip[$i]->pathID)->from)->lat,
                            'lngStart'=>Destination::find(Path::find($trip[$i]->pathID)->from)->lng,
                            'latEnd'=>Destination::find(Path::find($trip[$i]->pathID)->to)->lat,
                            'lngEnd'=>Destination::find(Path::find($trip[$i]->pathID)->to)->lng,
                            'price'=>$trip[$i]->price,
                            'status'=>$status,
                            'statusId'=>$trip[$i]->status,
                        ];
                }
                return $this->apiResponse($details,'',200);
            }
        }
    }

}
