<?php

namespace App\Http\Middleware;

use Closure;

class Supervisor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next = null, $guard = 'supervisor')
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
