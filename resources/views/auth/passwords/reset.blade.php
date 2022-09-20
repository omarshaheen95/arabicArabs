@extends('layouts.container_2')

@section('content')
    <!-- START SLIDER -->
    <div id="slider" class="aos-item slider-bg theme-bg-secondary-light" data-aos="fade-in">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="section-warp">
                        <div class="section-header">
                            <h1 class="section-title"> استعادة كلمة المرور </h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="contact-item">
                        <div class="card">
                            <div class="card-body">

                                <form class="col-form" method="POST" action="/password/reset">
                                    @csrf

                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @if (session('status'))
                                                <div class="alert alert-success" role="alert">
                                                    {{ session('status') }}
                                                </div>
                                            @endif
                                            @if (count($errors) > 0)
                                                <div class="alert alert-warning">
                                                    <ul style="width: 100%;">
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="email" placeholder="البريد الإلكتروني" value="{{ $email ?? old('email') }}">
                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="password" placeholder="كلمة المرور">
                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="password_confirmation" placeholder="تأكيد كلمة المرور">
                                                @if ($errors->has('password_confirmation'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <button type="submit" class="theme-btn theme-btn-default btn-block">تغيير كلمة المرور <i class="flaticon-double-right-arrows-angles"></i></button>
                                        </div>
                                        <div class="col-lg-6 mt-4">
                                            <a href="/login">تسجيل الدخول</a>
                                        </div>
                                        <div class="col-lg-6 mt-4 text-left">
                                            <a href="/register">تسجيل حساب جديد</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- END SLIDER -->
@endsection
