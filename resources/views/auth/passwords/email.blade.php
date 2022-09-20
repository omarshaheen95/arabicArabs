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
                                <img src="{{asset('web_assets/img/forget-password.svg')}}" alt="">
                            </div>
                            <h1 class="title">استعادة كلمة المرور </h1>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-9">
                        <div class="form form-login">
                            <form action="/password/email" method="post" class="needs-validation" novalidate>
                                @csrf
                                <div class="form-group">
                                    <label for="email" class="form-label">البريد الإلكتروني</label>
                                    <div class="form-control-icon">
                                        <div class="icon">
                                            <img src="{{asset('web_assets/img/mail.svg')}}" alt="">
                                        </div>
                                        <input type="email" name="email" id="email" class="form-control" placeholder=" ex: example@domain.com"
                                               required autocomplete="off" autofocus pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-theme btn-submit">
                                        <span class="spinner-border d-none"></span>
                                        <span class="text"> إرسال رابط استعادة كلمة المرور </span>
                                    </button>
                                </div>
                                <div class="form-group text-center">
                                    <a href="/login" class="form-link"> تسجيل الدخول </a>
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
