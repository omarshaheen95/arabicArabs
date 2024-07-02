<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Package;
use App\Models\School;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Propaganistas\LaravelPhone\PhoneNumber;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'check_subscribe';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest');
        $this->validationRules["name"] = 'required|max:40';
        $this->validationRules["email"] = 'required|email:rfc,dns|unique:users,email,{$id},id,deleted_at,NULL';
        $this->validationRules["password"] = 'required|min:6|confirmed';
        $this->validationRules["school_id"] = 'required|exists:schools,id';
        $this->validationRules["package_id"] = 'required|exists:packages,id';
        $this->validationRules["grade_id"] = 'required';
        $this->validationRules['country_code'] = 'required';
        $this->validationRules['short_country'] = 'required';
        $this->validationRules["mobile"] = ['required'];
//        $this->validationRules['mobile'] = ['required', 'phone:' . request()->get('short_country')];

    }

    public function showRegistrationForm()
    {
        $title = "إنشاء حساب جديد";
        $schools = School::query()->where('active', 1)->orderBy('name')->get();
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $packages = Package::query()->where('active', 1)->get();
        $grades = Grade::query()->get();
        return view('auth.register', compact('schools', 'title', 'validator', 'packages', 'grades'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, $this->validationRules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $data['active_from'] = now();
        $data['active_to'] = now();
        $data['mobile'] = PhoneNumber::make($data['mobile'])->ofCountry($data['short_country']);
        $user = User::query()->create($data);
        return $user;
    }
}
