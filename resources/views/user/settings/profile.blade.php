{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('user.layout.container_v2')
@section('style')
    <link href="{{asset('intl-tel-input-master/build/css/intlTelInput.min.css')}}" rel="stylesheet">
    <style>
        .hide {
            display: none;
        }
    </style>
    @if(app()->getLocale() == 'ar')
        <style>
            .iti * {
                direction: ltr;
            }
        </style>
    @endif
@endsection
@section('content')
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h1 class="title"> الملف الشخصي </h1>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="/home"> الرئيسة </a>
                            <span class="breadcrumb-item active" aria-current="page"> الملف الشخصي </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="profile-card form form-login">
                        <div class="profile-header">
                            <div class="title"> معلومات الحساب </div>
                        </div>
                        <form action="{{ route('profile_update') }}" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
                            @csrf
                            <div class="form-group">
                                <label for="name" class="form-label"> الاسم كاملا </label>
                                <div class="form-control-icon">
                                    <div class="icon">
                                        <img src="{{asset('web_assets/img/username.svg')}}" alt="">
                                    </div>
                                    <input type="text" name="name" id="name" value="{{ isset($user->name) ? $user->name : old("name") }}" class="form-control" placeholder=""
                                           required autocomplete="off" autofocus>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="mobile-1" class="form-label"> الموبايل </label>
                                <div class="form-control-icon phone">
                                    <div class="icon">
                                        <img src="{{asset('web_assets/img/phone.svg')}}" alt="">
                                    </div>
                                    <input type="tel" class="form-control phone-input" value="{{ isset($user) ? $user->mobile : old('mobile') }}" name="mobile" id="mobile-1" data-country="sa" onkeyup="getPhoneKey(this.id)" required>
                                    <input type="hidden" class="form-control" value="{{ isset($user) ? $user->country_code : old('country_code') }}"  id="mobile-1-code" name="country_code">
                                    <input type="hidden" class="form-control" value="{{ isset($user) ? $user->short_country : old('short_country') }}"  id="mobile-1-country" name="short_country">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label"> البريد الإلكتروني </label>
                                <div class="form-control-icon">
                                    <div class="icon">
                                        <img src="{{asset('web_assets/img/mail.svg')}}" alt="">
                                    </div>
                                    <input type="email" name="email" id="email" class="form-control" placeholder=" ex: example@domain.com" value="{{ isset($user->email) ? $user->email : old("email") }}"
                                           required autocomplete="off" autofocus pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="school_id" class="form-label"> المدرسة </label>
                                <div class="form-control-icon">
                                    <div class="icon">
                                        <img src="{{asset('web_assets/img/school.svg')}}" alt="">
                                    </div>
                                    <select name="school_id" id="school_id" class="form-control form-select" required>
                                        <option value="" selected disabled> Select School </option>
                                        @foreach($schools as $school)
                                            <option
                                                value="{{$school->id}}" {{isset($user) && $user->school_id == $school->id ? 'selected':''}}>{{$school->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-theme btn-submit">
                                    <span class="spinner-border d-none"></span>
                                    <span class="text"> حفظ </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="profile-card form form-login">
                        <div class="profile-header">
                            <div class="title"> تغيير كلمة المرور </div>
                        </div>
                        <form id="form_password" action="{{ route('update_password') }}" method="post" class="needs-validation" novalidate>
                            @csrf
                            {{--                            <div class="form-group">--}}
                            {{--                                <label for="old_password" class="form-label"> كلمة المرور الحالية </label>--}}
                            {{--                                <div class="form-control-icon">--}}
                            {{--                                    <div class="icon">--}}
                            {{--                                        <img src="{{asset('web_assets/img/password.svg')}}" alt="">--}}
                            {{--                                    </div>--}}
                            {{--                                    <input type="password" name="old_password" id="old_password" class="form-control" placeholder=" ************"--}}
                            {{--                                           required autocomplete="off">--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            <div class="form-group">
                                <label for="password" class="form-label"> كلمة المرور </label>
                                <div class="form-control-icon">
                                    <div class="icon">
                                        <img src="{{asset('web_assets/img/password.svg')}}" alt="">
                                    </div>
                                    <input type="password" name="password" id="password" class="form-control" placeholder=" ************"
                                           required autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور </label>
                                <div class="form-control-icon">
                                    <div class="icon">
                                        <img src="{{asset('web_assets/img/password.svg')}}" alt="">
                                    </div>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder=" ************"
                                           required autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-theme btn-submit">
                                    <span class="spinner-border d-none"></span>
                                    <span class="text"> حفظ البيانات </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    {{--    <script src="{{asset('intl-tel-input-master/build/js/intlTelInput.min.js')}}"></script>--}}
@endsection
