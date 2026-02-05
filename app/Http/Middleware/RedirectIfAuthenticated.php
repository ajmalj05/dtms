<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // dd('iik');
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {

            if (Auth::guard($guard)->check()) {

                $user = auth()->user();

                $ipAddress = $_SERVER['REMOTE_ADDR'];


                    DB::table('login_log')->insert([
                        'user_id'=>  $user->id ,
                        'login_time'=>date('Y-m-d H:i:s'),
                        'login_ip' =>  $ipAddress,
                    ]);

                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
