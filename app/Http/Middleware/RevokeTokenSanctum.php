<?php

namespace App\Http\Middleware;

use App\Models\UserModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevokeTokenSanctum
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //Nếu người dùng không check remember me
        if ($request->user()->remember_token === '') {

            $now = time();
            $lastLogin = strtotime($request->user()->last_login_at);
    
            //Thời gian đăng nhập đã hết, xóa token hiện  tại
            if (($now - $lastLogin)/60 > config('session.lifetime')) {
                $request->user()->currentAccessToken()->delete();
            }

        }

        return $next($request);
    }
}
