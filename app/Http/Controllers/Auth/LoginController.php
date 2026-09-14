<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials()
    {
        $username = $this->username();
        $credentials = request()->only($username, 'password');
        if (isset($credentials[$username])) {
            $credentials[$username] = strtolower($credentials[$username]);
        }
        return $credentials;
    }

    //check if user is not archived before login
    protected function attemptLogin(Request $request)
    {
        // Retrieve the user based on the email provided
        $user = \App\Models\User::where(DB::raw('LOWER(email)'), strtolower($request->email))->first();

        // Check if the user exists and if they are active
        if ($user && $user->archived) {
            \App\Services\LoginActivityService::record('failed', 'web', $user, ['identifier' => $request->email, 'reason' => 'account_archived']);
            return false;
        }

        // Check if the user's school has suspended student login
        if ($user && $user->school_id && $user->school && $user->school->suspend_student_login) {
            \App\Services\LoginActivityService::record('failed', 'web', $user, ['identifier' => $request->email, 'reason' => 'school_suspended']);
            throw ValidationException::withMessages([
                $this->username() => ['حسابك معلق مؤقتا'],
            ]);
        }

        // Attempt to log the user in with the credentials provided
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }
}
