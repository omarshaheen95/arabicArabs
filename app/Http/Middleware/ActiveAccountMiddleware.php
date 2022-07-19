<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActiveAccountMiddleware
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
        $user = Auth::guard('web')->user();

        if (is_null($user->active_to)) {
            return redirect()->route('home')->with('message',  "اشترك في باقة أولا")->with('m-class', 'error');
        }
        if ($user->active_to < now()) {
            return redirect()->route('home')->with('message', "يرجى تجديد الإشتراك")->with('m-class', 'error');
        }
        return $next($request);
    }
}
