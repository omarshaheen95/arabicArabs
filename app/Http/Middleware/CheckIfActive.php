<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckIfActive
{

    public function handle(Request $request, Closure $next)
    {
        if ($request->is('manager/home') && !Auth::guard('manager')->user()->active) {
            Session::put('not_active', 'not_active');
        } elseif ($request->is('school/home') && !Auth::guard('school')->user()->active) {
            Session::put('not_active', 'not_active');
        }elseif ($request->is('supervisor/home') && !Auth::guard('supervisor')->user()->active) {
            Session::put('not_active', 'not_active');
        }elseif ($request->is('teacher/home') && !Auth::guard('teacher')->user()->active) {
            Session::put('not_active', 'not_active');
        }else{
            Session::forget('not_active');
        }

        if ($request->is('manager/*') &&
            !$request->is('manager/home') &&
            !$request->is('manager/logout') &&
            !$request->is('manager/lang/*') &&
            !Auth::guard('manager')->user()->active) {
            return redirect('manager/home')->with('not_active','not_active');

        } elseif ($request->is('school/*') &&
            !$request->is('school/home') &&
            !$request->is('school/lang/*') &&
            !$request->is('school/logout') && !Auth::guard('school')->user()->active) {
            return redirect('school/home')->with('not_active','not_active');

        }elseif ($request->is('supervisor/*') &&
            !$request->is('supervisor/home') &&
            !$request->is('supervisor/lang/*') &&
            !$request->is('supervisor/logout') && !Auth::guard('supervisor')->user()->active) {
            return redirect('supervisor/home')->with('not_active','not_active');
        }elseif ($request->is('teacher/*') &&
            !$request->is('teacher/home') &&
            !$request->is('teacher/lang/*') &&
            !$request->is('teacher/logout') && !Auth::guard('teacher')->user()->active) {
            return redirect('teacher/home')->with('not_active','not_active');
        }

        return $next($request);
    }
}
