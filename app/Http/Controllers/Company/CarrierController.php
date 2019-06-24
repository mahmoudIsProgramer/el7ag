<?php

namespace App\Http\Controllers\Company;

use App\Path;
use App\Carrier;
use App\Destination;
use App\CarrierPath ; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarrierController extends Controller
{
    public function __construct()
    {
        $this->middleware('company');

       /* $this->middleware(['auth:company','permission:read_drivers'])->only('index');
        $this->middleware(['auth:company','permission:create_drivers'])->only('create','store');
        $this->middleware(['auth:company','permission:update_drivers'])->only('edit','update');
        $this->middleware(['auth:company','permission:delete_drivers'])->only('status','delete');
   */
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

            return redirect()->route('company.carrier.index');

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

            $requestData = $request->except(['_token']);
            $carrier->update($requestData);
            session()->flash('success',trans('admin.Data has been updated successfully'));

            return redirect()->route('company.carrier.index');


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

            return redirect()->route('company.carrier.index');

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

            return redirect()->route('company.carrier.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

    // get all paths for  carrier
    public function carrierPath($id){
        try{
            $userId = \Auth::guard('company')->user()->id;
            $carrier_id = $id ;
            $path = Path::where('company_id',$userId)
                ->orderBy('created_at','desc')->get();

            return view('backend.carrier.all_paths_to_set_price' ,compact(['path' , 'carrier_id']) );
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }
    // update price view 
    public function set_price_view($carrier_id , $path_id){
        try{
            $carrier_id = $carrier_id ; 
            $path = Path::find($path_id);
            $from = Destination::where('id',$path->from)->first(); 
            $to   = Destination::where('id',$path->to)->first(); 
            $from= $from->translate(\App::getLocale())->name;
            $to= $to->translate(\App::getLocale())->name;
            $price = CarrierPath::where('carrier_id',$carrier_id)->where('path_id',$path_id)->value('price'); 
            
            checkData($path);
            $userId = \Auth::guard('company')->user()->id;
            $destination = Destination::where('company_id',$userId)->get();
            return view('backend.carrier.edit_price',compact(['path','from','to' ,'carrier_id','price']));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }

    }

    public function price_update( Request $request){
        try{
            $rules = $message = [];

            $rules += [
                'price'=>'required|regex:/^\d*(\.\d{1,2})?$/|min:1',
            ];

            $message += [
                'price.required' =>trans('admin.Price is required'),
            ];

            $validator = \Validator::make($request->all(), $rules, $message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $update_price = CarrierPath::updateOrCreate(
                ['carrier_id' => request('carrier_id'), 'path_id' => request('path_id')],
                ['price' => request('price') ]
            );

            if ($update_price)
            {
                session()->flash('success',trans('admin.Data has been updated successfully'));
                return redirect()->back()->withInput();
            }

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }
}
