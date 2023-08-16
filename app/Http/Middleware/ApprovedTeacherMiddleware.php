<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovedTeacherMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('teacher')->user();

        if ($user->approved == 0) {
            return redirect()->route('teacher.home')->with('account-message', t('You Account Is Deactivated'))->with('m-class', 'error');
        }
        return $next($request);
    }
}
