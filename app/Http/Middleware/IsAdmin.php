<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       
        if($request->header('is_admin'))
        {
            $is_admin = $request->header('is_admin');
        }
        else
        {
            $is_admin = $request->input('is_admin');
        }

        if($is_admin!=true)
        {
            return response('Only the admin is able to perform this action', 401);
        }

        return $next($request);
    }
}
