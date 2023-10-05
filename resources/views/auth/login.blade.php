@extends('layouts.container_2')

@section('content')
    <main class="wrapper" id="login-home">
        <!-- Start login-home -->
        <section class="login-home login-student pt-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title text-center mb-5">
                            <div class="img">
                                <img src="{{asset('web_assets/img/login-student.svg')}}" alt="">
                            </div>
                            <h1 class="title"> تسجيل الدخول </h1>
                            <p class="info"> تعلم ، تدرب ، اختبر نفسك ، العب</p>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-9">
                        <div class="form form-login">
                            <form action="/login" method="post" class="needs-validation" novalidate>
                                @csrf
                                <div class="form-group">
                                    <label for="email" class="form-label"> البريد الإلكتروني </label>
                                    <div class="form-control-icon">
                                        <div class="icon">
                                            <img src="{{asset('web_assets/img/mail.svg')}}" alt="">
                                        </div>
                                        <input type="email" name="email" id="email" value="{{request()->get('username', null)}}" class="form-control" placeholder=" ex: example@domain.com"
                                               required autocomplete="off" autofocus pattern="[A-Za-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                         @if ($errors->has('email'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="forget-password-label">
                                        <label for="password" class="form-label"> كلمه المرور
                                        </label>
                                        <a href="/password/reset">نسيت كلمة المرور</a>
                                    </div>
                                    <div class="form-control-icon">
                                        <div class="icon">
                                            <img src="{{asset('web_assets/img/password.svg')}}" alt="">
                                        </div>
                                        <input type="password" name="password" value="{{request()->get('password', null)}}"  id="password" class="form-control" placeholder=" **********" required>
                                        @if ($errors->has('password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-theme btn-submit">
                                        <span class="spinner-border d-none"></span>
                                        <span class="text"> تسجيل الدخول
 </span>
                                    </button>
                                </div>
                                <div class="form-group text-center">
                                    <a href="/register" class="form-link"> إنشاء حساب جديد </a>
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
