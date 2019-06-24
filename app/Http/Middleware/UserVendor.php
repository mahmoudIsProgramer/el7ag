<?php

namespace App\Http\Middleware;

use Closure;

class UserVendor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next = null, $guard = 'user_vendor')
    {
        if (\Auth::guard($guard)->check()) {
            return $next($request);
        }
        else
        {
            return redirect('/');
        }


    }
}
