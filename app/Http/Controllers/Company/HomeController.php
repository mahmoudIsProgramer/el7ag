<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('company');
    }

    public function dashboard()
    {
        try{
           return view('backend.company.dashboard.dashboard');
        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }


    public function logout()
    {
        auth()->guard('company')->logout();
        return redirect('/')->withInput();
    }
}
