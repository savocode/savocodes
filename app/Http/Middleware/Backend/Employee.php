<?php

namespace App\Http\Middleware\Backend;

use Closure;
use Auth;
use App\Models\User;

class Employee
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ( Auth::guard($guard)->check() ) {
            if ( Auth::guard($guard)->user()->isEmployee() ) {
                return $next($request);
            } else {
                // Unauthorized Access
                abort(401);
            }
        } else {
            return redirect()->guest('backend/login');
        }
    }
}
