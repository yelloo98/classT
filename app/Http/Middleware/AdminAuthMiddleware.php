<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuthMiddleware
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

    	//# 사용자 로그인 체크
    	if(!Auth::guard('admins')->user()) {
		    return redirect('/admin/login');
	    }
	    
        return $next($request);
    }
}
