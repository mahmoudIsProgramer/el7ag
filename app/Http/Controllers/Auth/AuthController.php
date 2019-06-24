<?php

namespace App\Http\Controllers\Auth;

use App\Admin;
use App\Company;
use App\Http\Requests\AuthRequest;
use App\Mail\AdminResetPassword;
use App\Supervisor;
use App\User;
use App\UserVendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function formLogin()
    {
        try{
            return view('backend.auth.login');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back();
        }
    }

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
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }


            $tables = array('web' => 'User','admin' => 'Admin', 'company' => 'Company' ,'user_vendor'=>'UserVendor','supervisor'=>'Supervisor');

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
                session()->flash('warning',trans('admin.Login failed please try again'));
                return redirect()->back();
            }
            if ($nkey == 'admin')
            {
                $data = Admin::where('email',\request('email'))->where('status',1)->first();

                if (empty($data))
                {
                    session()->flash('warning',trans('admin.Login failed please try again'));
                    return redirect()->back()->withInput();
                }
                else
                {
                    if(\Auth::guard($nkey)->attempt(array('email'=> $email, 'password' => $password),$remember))
                    {
                        \Auth::guard('company')->logout();
                        \Auth::guard('user_vendor')->logout();
                        \Auth::guard('supervisor')->logout();
                        return redirect()->route('admin.home');
                    }
                    else
                    {
                        session()->flash('warning',trans('admin.Login failed please try again'));
                        return redirect()->back()->withInput();
                    }

                }

            }

            elseif ($nkey == 'company')
            {
                $data = Company::where('email',\request('email'))->where('status',1)->first();


                if (empty($data))
                {
                    session()->flash('warning',trans('admin.Login failed please try again'));
                    return redirect()->back()->withInput();
                }
                else
                {
                    if(\Auth::guard($nkey)->attempt(array('email'=> $email, 'password' => $password),$remember))
                    {
                        \Auth::guard('admin')->logout();
                        \Auth::guard('user_vendor')->logout();
                        \Auth::guard('supervisor')->logout();
                        return redirect()->route('company.home');
                    }
                    else
                    {
                        session()->flash('warning',trans('admin.Login failed please try again'));
                        return redirect()->back()->withInput();
                    }

                }

            }

            elseif ($nkey == 'user_vendor')
            {
                $data = UserVendor::where('email',\request('email'))->where('status',1)->first();
                if (empty($data))
                {
                    session()->flash('warning',trans('admin.Login failed please try again'));
                    return redirect()->back()->withInput();
                }
                else
                {
                    if(\Auth::guard($nkey)->attempt(array('email'=> $email, 'password' => $password),$remember))
                    {
                        \Auth::guard('admin')->logout();
                        \Auth::guard('company')->logout();
                        \Auth::guard('supervisor')->logout();
                        return redirect()->route('user.vendor.home');
                    }
                    else
                    {
                        session()->flash('warning',trans('admin.Login failed please try again'));
                        return redirect()->back()->withInput();
                    }

                }

            }

            elseif ($nkey == 'supervisor')
            {
                $data = Supervisor::where('email',\request('email'))->where('status',1)->first();
                if (empty($data))
                {
                    session()->flash('warning',trans('admin.Login failed please try again'));
                    return redirect()->back()->withInput();
                }
                else
                {
                    if(\Auth::guard($nkey)->attempt(array('email'=> $email, 'password' => $password),$remember))
                    {
                        \Auth::guard('admin')->logout();
                        \Auth::guard('company')->logout();
                        \Auth::guard('user_vendor')->logout();
                        return redirect()->route('supervisor.home');
                    }
                    else
                    {
                        session()->flash('warning',trans('admin.Login failed please try again'));
                        return redirect()->back()->withInput();
                    }

                }

            }

            else
            {
                \Auth::guard('admin')->logout();
                \Auth::guard('company')->logout();
                \Auth::guard('user_vendor')->logout();
                \Auth::guard('supervisor')->logout();
                session()->flash('warning',trans('admin.Login failed please try again'));
                return redirect()->back()->withInput();
            }
        }catch (\Exception $exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function resetPassword()
    {
        return view('backend.auth.backend.password.email');
    }

    public function postResetPassword(Request $request)
    {
        $messages = [
            'email.required' => 'We need to know your e-mail address!',
        ];
        $validator = \Validator::make($request->all(), [
            'email'=>'required|email|string',
        ], $messages);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $admin = Admin::where('email',$request->get('email'))->first();
        if (!empty($admin))
        {
            $token = app('auth.password.broker')->createToken($admin);
            $data = \DB::table('password_resets')->insert(
                [
                    'email'=>$admin->email,
                    'token'=>$token,
                    'created_at'=>Carbon::now()
                ]);
            // return new AdminResetPassword(['data'=>$admin,'token'=>$token]);
            \Mail::to($admin->email)->send(new AdminResetPassword(['data'=>$admin,'token'=>$token]));
            session()->flash('success',trans('admin.Rest link is sent'));
        }
        return redirect()->back();
    }

    public function reset($token)
    {
        $checkToken = \DB::table('password_resets')
            ->where('token','=',$token)
            ->where('created_at','>',Carbon::now()->subHour(2))
            ->first();

        if (!empty($checkToken))
        {
            return view('backend.auth.backend.password.reset',['data'=>$checkToken]);
        }
        else
        {
            return redirect()->route('get.reset.password');
        }
    }

    public function postReset(Request $request ,$token)
    {

        $messages = [
            'email.required' => 'We need to know your e-mail address!',
            'password.required' => 'We need to know your password !',
            'password_confirmation.required' => 'We need to know your password confirmation !',
        ];
        $validator = \Validator::make($request->all(), [
            'email'=>'required|email|string',
            'password'=>'required|confirmed',
            'password_confirmation'=>'required'
        ], $messages);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        $checkToken = \DB::table('password_resets')
            ->where('token','=',$token)
            ->where('created_at','>',Carbon::now()->subHour(2))
            ->first();
        if (!empty($checkToken))
        {
            Admin::where('email',$checkToken->email)
                ->update([
                    'email'=>$checkToken->email,
                    'password'=>\Hash::make(\request('password'))
                ]);
            \DB::table('password_resets')
                ->where('email','=',\request('email'))->delete();

            auth()->guard('admin')->attempt(['email'=>$checkToken->email,'password'=>\request('password')] , true);
            return redirect()->route('admin.home');
        }
        else
        {
            return redirect()->route('get.reset.password');
        }
    }
}
