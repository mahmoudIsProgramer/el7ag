<?php

# because to load abatch or source code

#------------------Delete Image-----------






if (!function_exists('CheckIfBusy'))
{
    
    /*
    * func used when create trip 
    *
    * func to check if the  person[ driver , guide , supervisor ]
    * is busy or not in this time 
    *  return [true = already busy   , false   = not busy ]  
    */
    function CheckIfBusy($start , $end ,  $col, $col_value  ){

        $data = \App\Trip::where([
            ['start_date', '<=', $start],
            ['end_date', '>=',  $end ]
        ])->where( $col, $col_value )->get();

        $data1 = \App\Trip::where([
            ['end_date', '>=', $start],
            ['end_date', '<=',  $end ]
        ])->where( $col, $col_value )->get();

        $data2 = \App\Trip::where([
            ['start_date', '>=', $start],
            ['start_date', '<=',  $end ]
        ])->where($col , $col_value )->get();

        if( count($data) > 0 || count($data1) > 0 ||  count($data2) > 0 ){
            return true ;
        }else{
            return false ;
        }


    }

}

if (!function_exists('CheckIfBusyUpdate'))
{
    
    /*
    * func used when update  trip 
    *
    * func to check if the  person[ driver , guide , supervisor ]
    * is busy or not in this time 
    *  return [true = already busy   , false   = not busy ]  
    */
    function CheckIfBusyUpdate( $trip_id , $start , $end ,  $col, $col_value  ){

        $data = \App\Trip::where('id','!=', $trip_id )->where([
            ['start_date', '<=', $start],
            ['end_date', '>=',  $end ]
        ])->where( $col, $col_value )
        ->get();
        // dd($data) ; 

        $data1 = \App\Trip::where('id','!=', $trip_id )->where([
            ['end_date', '>=', $start],
            ['end_date', '<=',  $end ]
        ])->where( $col, $col_value )
        ->get();

        $data2 = \App\Trip::where('id','!=', $trip_id )->where([
            ['start_date', '>=', $start],
            ['start_date', '<=',  $end ]
        ])->where($col , $col_value )
        ->get();

        if( count($data) > 0 || count($data1) > 0 ||  count($data2) > 0 ){
            return true ;
        }else{
            return false ;
        }


    }

}


if (!function_exists('send_notification'))
{
    function send_notification( $txt , $token  , $device_type ){
        if( $device_type == 'android' ){
            $push = new PushNotification('fcm');
            $push->setMessage([
                'data' => [
                        'title'=>'This is the title',
                        'message'=>$txt,
                        ]
                ])
                // ->setApiKey('AAAAK8lWUHM:APA91bGEYlrZ0ZO_siwuvjUHPW1RKafnXslecIYEAt9bXVZ0qUmedFSgCNKXo96QHyFMvxZnUB6Q23ZY66s2cFP40rFqibmYb0NR5UDq7hhZ1ZGdIHiVNm8QwjxnrKN0umDFsQvX-Ev9')
                ->setApiKey('AAAAE8PtElE:APA91bFWEYpTvHaKTPBRUaXXDBrloaluiFTuvxYJb5XdlyDJtkjpUWpfN2CQktQDI8T8CYCrJkoD6izCNbNp7VN8UqfeVpSbatS0KNhfejiCxhh2TIVTUxF9V_Dg5Ti5rZvAKmjBycQP')
                ->setDevicesToken($token )
                ->send()->getFeedback();
                // dd($push);
                return true ;
        }
        
    }

}


if (!function_exists('get_guard'))
{
    function get_guard(){
        if(Auth::guard('admin')->check())
            {return "admin";}
        elseif(Auth::guard('company')->check())
            {return "company";}
        elseif(Auth::guard('supervisor')->check())
            {return "company";}
        elseif(Auth::guard('client')->check())
            {return "";}
    }

}

if (!function_exists('DeleteImage'))
{
    function DeleteImage($DeleteFileWithName)
    {
        if(file_exists($DeleteFileWithName))
        {
            \File::delete($DeleteFileWithName);
        }
    }

}

if (!function_exists('image_path'))
{
    function image_path($user_type , $image )
    {
        return asset('upload/'.$user_type.'/'.$image);
    }

}




if (!function_exists('DeleteMultipleImage'))
{
    function DeleteMultipleImage( $user_id , $user_type )
    {
        $files_names  = DB::table('attachments')->where('user_id',$user_id)->
            where('type',$user_type)->
            pluck('file_name')->toArray(); 
            
        foreach( $files_names  as  $file ){
            DeleteImage( public_path('upload/'.$user_type.'/'.$file) );
        }
        DB::table('attachments')->where('user_id',$user_id)->
            where('type',$user_type)->delete(); 

        
    }

}

#upload image
if (!function_exists('uploadImages'))
{
    function uploadImages($request,$path,$deleteFileWithName = '')
    {

        if($deleteFileWithName != '')
        {
            #Delete Image
            DeleteImage(public_path('upload/'.$path.$deleteFileWithName));
        }
        \Intervention\Image\Facades\Image::make($request)->resize(300,null,function ($constraint){
            $constraint->aspectRatio();
        })->save(public_path('upload/'.$path.$request->hashName()));
        ;
        if($deleteFileWithName != '')
        {
            DeleteImage(public_path('upload/'.$path.$deleteFileWithName));
        }

        return $request->hashName();
    }

}

#multiple upload image
if (!function_exists('MultipleUploadImages'))
{
    function MultipleUploadImages($requests , $path )
    {
        $data = []; 
        foreach( $requests as  $request){
            \Intervention\Image\Facades\Image::make($request)->resize(300,null,function ($constraint){
                $constraint->aspectRatio();
            })->save(public_path('upload/'.$path.$request->hashName()));
            $data[] = $request->hashName();      
        }
        return $data ; 
    }

}




#auth guard admin
if (!function_exists('admin'))
{
    function admin()
    {
        return auth()->guard('admin');
    }

}
#acctive side bar
if (!function_exists('activeMenu'))
{
    function activeMenu($link)
    {
        if (preg_match('/'.$link.'/i',Request::segment(2))){

            return ['menu-open','display:block'];
        }
        elseif (preg_match('/'.$link.'/i',Request::segment(2))){

        }
        else
        {
            return ['',''];
        }
    }

}


# dataTable Language

if (!function_exists('dataTaleLang'))
{
    function dataTaleLang()
    {
        return
        [
            'sProcessing'=>trans('admin.sProcessing'),
            'sLengthMenu'=>trans('admin.sLengthMenu'),
            'sZeroRecords'=>trans('admin.sZeroRecords'),
            'sEmptyTable'=>trans('admin.sEmptyTable'),
            'sInfo'=>trans('admin.sInfo'),
            'sInfoEmpty'=>trans('admin.sInfoEmpty'),
            'sInfoFiltered'=>trans('admin.sInfoFiltered'),
            'sInfoPostFix'=>trans('admin.sInfoPostFix'),
            'sSearch'=>trans('admin.sSearch'),
            'sUrl'=>trans('admin.sUrl'),
            'sInfoThousands'=>trans('admin.sInfoThousands'),
            'sLoadingRecords'=>trans('admin.sLoadingRecords'),
            'oPaginate'=>[
                'sFirst'=>trans('admin.sFirst'),
                'sLast'=>trans('admin.sLast'),
                'sNext'=>trans('admin.sNext'),
                'sPrevious'=>trans('admin.sPrevious'),
            ],

            'oAria'=>[
                'sSortAscending'=>trans('admin.sSortAscending'),
                'sSortDescending'=>trans('admin.sSortDescending'),
            ],
        ];
    }

}

#validate helper function

if (!function_exists('validateImage'))
{
    function validateImage($ext = null)
    {
      if ($ext == null)
      {
          return 'image|mimes:jpg,jpeg,png,bmp';
      }
      else
      {
          return 'image|mimes:'.$ext;
      }
    }

}

if (!function_exists('checkData'))
{
    function checkData($data)
    {
       if (empty($data))
       {
           session()->flash('warning',trans('admin.Please refresh this page please'));
           return redirect()->back()->withInput();
       }
    }

}

if (!function_exists('checkEmail'))
{
    function checkEmail($table,$request)
    {
        $data = DB::table($table)->where('email','=',$request)->first();
        return $data;
    }

}

if (!function_exists('cdataEmpty'))
{
    function cdataEmpty($table,$col,$request)
    {
        $data = DB::table($table)->where($col,'=',$request)->first();
        return $data;
    }

}


if (!function_exists('checkTrips'))
{
    function checkTrips($table,$col,$request)
    {
        $data = DB::table($table)->where($col,'=',$request)->get();
        return $data;
    }

}

if (!function_exists('checkTripsUpdate'))
{
    function checkTripsUpdate($table,$col,$request=[])
    {
        $data = DB::table($table)
            ->whereNotIn($col, $request)
            //->whereNotIn('id', [1, 2, 3])
            ->get();
        return $data;
    }

}

if (!function_exists('checkTripsForLoop'))
{
    function checkTripsForLoop($collection,$request)
    {
        for ($i=0; $i<count($collection); $i++)
        {

            if ($collection[$i]->end_date > $request )
            {
               return true;
            }
            else
            {
                return false;
            }
        }
    }

}

if (!function_exists('checkTripsForLoopUpdate'))
{
    function checkTripsForLoopUpdate($collection,$request)
    {
        for ($i=0; $i<count($collection); $i++)
        {

            if ($collection[$i]->end_date >= $request)
            {

                return true;
            }
            else
            {
                return false;
            }
        }
    }

}

if (!function_exists('getAge'))
{
    function getAge($birth_day)
    {
        list($year, $month, $day) = explode("-", $birth_day);

        $year_diff  = date("Y") - $year;
        $month_diff = date("m") - $month;
        $day_diff   = date("d") - $day;
        if($month_diff < 0)
        {
            $year_diff --;
        }
        else if(($month_diff == 0) && ($day_diff < 0))
        {
            $year_diff --;
        }
        return $year_diff;

    }

}


if (!function_exists('getDataAuth'))
{
    function getDataAuth()
    {
        if (\Auth::guard('company')->user())
        {
            $userId = \Auth::guard('company')->user();

            return $userId;

        }
        elseif (\Auth::guard('user_vendor')->user())
        {
            $userId = \Auth::guard('user_vendor')->user();
            return $userId;

        }
        else
        {
            return redirect()->back();
        }
    }

}

if (!function_exists('getUrl'))
{
    function getUrl($routeName )
    {
        if (Request::route()->getName() == $routeName)
        {
            return 'active';
        }
        else
        {
            return '';
        }

    }

}







