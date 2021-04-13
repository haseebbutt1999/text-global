<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ShopActive
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
        if(Auth::user()->role == "admin"){
            return redirect('/shops');
        }else{
            if(Auth::user()->user_status == 'inactive'){
                if(isset(Auth::user()->shopdetail)){
                    return response()->view('adminpanel/module/user/setup_page');
                }else{
                    return response()->view('adminpanel/module/user/shop_detail');
                }
            }
        }

        return $next($request);
    }
}
