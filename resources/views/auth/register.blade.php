@extends('layouts.container_2')

@section('content')
    <main class="wrapper" id="login-home">
        <!-- Start login-home -->
        <section class="login-home login-student pt-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title text-center mb-5">
                            <h1 class="title"> تسجيل حساب جديد </h1>
                            <p class="info"> يرجى إدخال كافة البيانات بصورة صحيحة </p>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    @if (count($errors) > 0)
                        <div class="alert alert-warning">
                            <ul style="width: 100%;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="col-lg-6 col-md-9">
                        <div class="form form-login">
                            <form action="/register" method="post" class="needs-validation" novalidate>
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="username" class="form-label"> الاسم </label>
                                            <div class="form-control-icon">
                                                <div class="icon">
                                                    <img src="{{asset('web_assets/img/username.svg')}}" alt="">
                                                </div>
                                                <input type="text" name="name" id="username" class="form-control" placeholder="مثلاً: محمد خالد الاميري"
                                                       required autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label"> البريد الإلكتروني</label>
                                            <div class="form-control-icon">
                                                <div class="icon">
                                                    <img src="{{asset('web_assets/img/mail.svg')}}" alt="">
                                                </div>
                                                <input type="email" name="email" id="email" class="form-control" placeholder=" ex: example@domain.com"
                                                       required autocomplete="off" autofocus pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="forget-password-label">
                                                <label for="password" class="form-label"> كلمه المرور </label>
                                            </div>
                                            <div class="form-control-icon">
                                                <div class="icon">
                                                    <img src="{{asset('web_assets/img/password.svg')}}" alt="">
                                                </div>
                                                <input type="password" name="password" id="password" class="form-control" placeholder=" **********" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="forget-password-label">
                                                <label for="password_confirmation" class="form-label"> تأكيد كلمة المرور </label>
                                            </div>
                                            <div class="form-control-icon">
                                                <div class="icon">
                                                    <img src="{{asset('web_assets/img/password.svg')}}" alt="">
                                                </div>
                                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder=" **********" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="mobile" class="form-label"> الموبايل </label>
                                            <div class="form-control-icon phone">
                                                <div class="icon">
                                                    <img src="{{asset('web_assets/img/phone.svg')}}" alt="">
                                                </div>
                                                <input type="tel" class="form-control phone-input" name="mobile" id="mobile-1" data-country="sa" onblur="getPhoneKey(this.id)" required>
                                                <input type="hidden" class="form-control"  id="mobile-1-code" name="country_code">
                                                <input type="hidden" class="form-control"  id="mobile-1-country" name="short_country">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="universtiy" class="form-label"> اختر المدرسة </label>
                                            <div class="form-control-icon">
                                                <div class="icon">
                                                    <img src="{{asset('web_assets/img/school.svg')}}" alt="">
                                                </div>
                                                <select name="school_id" id="universtiy" class="form-control form-select" required>
                                                    @foreach($schools as $school)
                                                        <option value="{{$school->id}}">{{$school->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="class" class="form-label"> اختر الصف </label>
                                            <div class="form-control-icon">
                                                <div class="icon">
                                                    <img src="{{asset('web_assets/img/class.svg')}}" alt="">
                                                </div>
                                                <select name="grade_id" id="class" class="form-control form-select" required>
                                                    @foreach($grades as $grade)
                                                        <option value="{{$grade->id}}">{{$grade->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="package" class="form-label"> اختر باقة </label>
                                            <div class="form-control-icon">
                                                <div class="icon">
                                                    <img src="{{asset('web_assets/img/package.svg')}}" alt="">
                                                </div>
                                                <select name="package_id" id="package" class="form-control form-select" required>
                                                    @foreach($packages as $package)
                                                        <option value="{{$package->id}}" {{request()->get('package_id', false) && request()->get('package_id', false) == $package->id ? 'selected':''}}>{{$package->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
{{--                                    <div class="col-sm-6">--}}
{{--                                        <div class="form-group p">--}}
{{--                                            <label for="years" class="form-label">سنوات تعلم اللغة العربية</label>--}}
{{--                                            <div class="form-control-icon">--}}
{{--                                                <div class="icon">--}}
{{--                                                    <div id="range_count" class="range-count">6</div>--}}
{{--                                                </div>--}}
{{--                                                <input type="range" name="year_learning" min="1" max="12" step="1" value="6" class=" form-range" id="years" required>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-theme btn-submit">
                                        <span class="spinner-border d-none"></span>
                                        <span class="text"> تسجيل </span>
                                    </button>
                                </div>
                                <div class="form-group text-center">
                                    <a href="/login" class="form-link"> العودة لتسجيل الدخول </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End login-home -->
    </main>
@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator->selector('#form_information') !!}

@endsection
