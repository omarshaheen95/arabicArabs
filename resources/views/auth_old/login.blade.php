@extends('layouts.container')

@section('content')
    <!-- START SLIDER -->
    <div id="slider" class="aos-item slider-bg theme-bg-secondary-light" data-aos="fade-in">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="section-warp">
                        <div class="section-header">
                            <h1 class="section-title"> تسجيل الدخول </h1>
                            <p>تعلم ، تدرب ، اختبر نفسك</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="contact-item">
                        <div class="card">
                            <div class="card-body">
                                <form class="col-form" action="/login" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="email" placeholder="البريد الإلكتروني">
                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="password" placeholder="كلمة المرور">
                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <button type="submit" class="theme-btn theme-btn-default btn-block">تسجيل الدخول <i class="flaticon-double-right-arrows-angles"></i></button>
                                        </div>
                                        <div class="col-lg-6 mt-4">
                                            <a href="/password/reset">نسيت كلمة المرور</a>
                                        </div>
                                        <div class="col-lg-6 mt-4 text-left">
                                            <a href="/register">إنشاء حساب جديد</a>
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
