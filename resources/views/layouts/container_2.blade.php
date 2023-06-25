<!doctype html>
<html lang="ar"  dir="rtl">
<head>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ isset($title) ? $title. " | ":''  }}منصة لغتي الأولى</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="author" content="">
    <meta name="robots" content="index, follow">

    <meta name="geo.position" content="">
    <meta name="geo.placename" content="">
    <meta name="geo.region" content="">

    <meta property="og:type" content="" />
    <meta property="og:title" content="{{ isset($title) ? $title. " | ":''  }}منصة لغتي الأولى" />
    <meta property="og:description" content="" />
    <meta property="og:image" content="{{asset('web_assets/img/logo.svg')}}" />
    <meta property="og:url" content="" />
    <meta property="og:site_name" content="" />

    <meta name="twitter:title" content="{{ isset($title) ? $title. " | ":''  }}منصة لغتي الأولى">
    <meta name="twitter:description" content="">
    <meta name="twitter:image" content="{{asset('web_assets/img/logo.svg')}}">
    <meta name="twitter:site" content="">
    <meta name="twitter:creator" content="">

    <link rel="canonical" href="" />
    <link rel="shortcut icon" href="{{asset('web_assets/img/logo.svg')}}" type="image/x-icon">

    <!-- Bootstrap CSS v5.0.2 -->
    @yield('p_style')
    <link rel="stylesheet" href="{{asset('cdn_files/bootstrap.rtl.min.css')}}">
    <link rel="stylesheet" href="{{asset('web_assets/css/custom.css')}}?v1">
    <link rel="stylesheet" href="{{asset('web_assets/css/resposive.css')}}">
    @yield('style')

</head>
<body @if(!Request::is('')) class="student-page" @endif>
<!-- Start Navbar -->
<nav class="navbar navbar-expand-xl navbar-light">
    <div class="container">
        <div class="navbar-container">
            <div class="navbar-header d-flex justify-content-between aling-items-center w-100" >
                <a class="logo" href="/">
                    <img src="{{asset('web_assets/img/logo.svg')}}" alt="">
                </a>
                <div class="btn-collapse ms-3 d-xl-none" data-bs-toggle="collapse" data-bs-target="#main-menu">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="45" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </div>
            </div>

            <div class="collapse navbar-collapse justify-content-end" id="main-menu">
                <ul class="navbar-nav">
                    <li class="nav-item d-xl-none">
                        <div class="navbar-logo text-end">
                            <a href="#main-menu" class="navbar-close" data-bs-toggle="collapse"> &times; </a>
                        </div>
                    </li>
{{--                    <li class="nav-item active">--}}
{{--                        <a class="nav-link"--}}
{{--                           @if(Request::is(''))--}}
{{--                           href="#home"--}}
{{--                           @else--}}
{{--                           href="/#home"--}}
{{--                            @endif--}}
{{--                        >الرئيسة</a>--}}
{{--                    </li>--}}

{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link"--}}
{{--                           @if(Request::is(''))--}}
{{--                           href="#"--}}
{{--                           @else--}}
{{--                           href="/#price"--}}
{{--                            @endif--}}
{{--                        >الأسعار</a>--}}
{{--                    </li>--}}
                    {{--                    <li class="nav-item">--}}
                    {{--                        <a class="nav-link" href="{{asset('User_Guide.pdf')}}">{{w('User Guide')}}</a>--}}
                    {{--                    </li>--}}
                    <li class="nav-item">
                        <a class="nav-link" href="/school/login">دخول المدرسة</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/teacher/login">دخول المعلم</a>
                    </li>

                    {{--                    <li class="nav-item">--}}
                    {{--                        <a class="nav-link" href="contact.html">{{w('Contact Us')}}</a>--}}
                    {{--                    </li>--}}

                    <li class="nav-item">
                        <a class="nav-link" href="/register">
                                    <span class="icon">
                                        <svg id="profile-add" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <g id="Group_644" data-name="Group 644" opacity="0.4">
                                                <path id="Vector" d="M4,0H0" transform="translate(14.5 19.5)" fill="none" stroke="#223f99" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                                                <path id="Vector-2" data-name="Vector" d="M0,4V0" transform="translate(16.5 17.5)" fill="none" stroke="#223f99" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                                            </g>
                                            <path id="Vector-3" data-name="Vector" d="M4.6,8.87a1.818,1.818,0,0,0-.33,0,4.435,4.435,0,1,1,.33,0Z" transform="translate(7.56 2)" fill="none" stroke="#223f99" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" opacity="0.4"/>
                                            <path id="Vector-4" data-name="Vector" d="M6.825,8.63a9.15,9.15,0,0,1-5.01-1.38c-2.42-1.62-2.42-4.26,0-5.87a9.766,9.766,0,0,1,10.01,0" transform="translate(5.165 13.18)" fill="none" stroke="#223f99" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                                            <path id="Vector-5" data-name="Vector" d="M0,0H24V24H0Z" transform="translate(24 24) rotate(180)" fill="none" opacity="0"/>
                                        </svg>
                                    </span>
                            <span>تسجيل جديد</span>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link btn btn-theme" href="/login">
                           تسجيل الدخول
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<!-- End Navbar -->
<!-- Start Main -->
<main class="wrapper" id="home">
    <!-- Start Header -->
    @yield('header')
    @yield('content')
</main>
<!-- End Main -->
<!-- Start Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-navbar">
                    <div class="logo">
                        <a href="#!">
                            <img src="{{asset('web_assets/img/logo.svg')}}" alt="">
                        </a>
                    </div>
                    <ul class="nav">
                        <li class="nav-item active">
                            <a class="nav-link"
                               @if(Request::is(''))
                               href="#home"
                               @else
                               href="/#home"
                                @endif
                            >الرئيسة</a>
                        </li>

{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link"--}}
{{--                               @if(Request::is(''))--}}
{{--                               href="#"--}}
{{--                               @else--}}
{{--                               href="/#price"--}}
{{--                                @endif--}}
{{--                            >الأسعار</a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link"--}}
{{--                               href="#guide"--}}
{{--                            >دليل المستخدم</a>--}}
{{--                        </li>--}}
                        {{--                        <li class="nav-item">--}}
                        {{--                            <a class="nav-link" href="contact.html">{{w('Contact Us')}}</a>--}}
                        {{--                        </li>--}}

                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-card">
                    <div class="footer-box">
                        <a href="tel:+971503842666">
                            <img src="{{asset('web_assets/img/number.svg')}}" class="me-3" alt="">
                            <span dir="ltr"> 00971503842666</span>
                        </a>
                    </div>
                    <div class="footer-box">
                        <img src="{{asset('web_assets/img/email.svg')}}" class="me-3" alt="">
                        <a href="mailto:Support@abt-assessments.com">
                            <span dir="ltr"> Support@abt-assessments.com</span>
                        </a>
{{--                        <span class="mx-4"> - </span>--}}
{{--                        <a href="mailto:Support@Non-Arabs.com">--}}
{{--                            <span dir="ltr"> Support@Non-Arabs.com</span>--}}
{{--                        </a>--}}
                    </div>
                    <div class="footer-box">
                        <ul class="nav nav-social-media">
                            <li class="nav-item">
                                <a href="#!" target="_blank" rel="noopener noreferrer">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#!" target="_blank" rel="noopener noreferrer">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#!" target="_blank" rel="noopener noreferrer">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#!" target="_blank" rel="noopener noreferrer">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#!" target="_blank" rel="noopener noreferrer">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-copyright">
                    <div class="text">2017 - 2023 © <a href="#!">ABT assessments</a>, جميع الحقوق محفوظة</div>

                    <ul class="nav links">
                        <li class="nav-itme">
                            {{--                            <a href="{{route('page', 'privacy_policy')}}" class="nav-link"> {{w('Privacy policy')}} </a>--}}
                            <a href="{{asset('Privacy Policy.pdf')}}" class="nav-link"> سياسة الخصوصية </a>
                        </li>
                        {{--                        <li class="nav-itme">--}}
                        {{--                            <a href="{{route('page', 'terms&conditions')}}" class="nav-link"> {{w('Terms and conditions')}} </a>--}}
                        {{--                        </li>--}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- End Footer -->

<!-- Bootstrap JavaScript Libraries -->
<script src="{{asset('cdn_files/jquery.min.js')}}"></script>
<script src="{{asset('cdn_files/popper.min.js')}}"></script>
<script src="{{asset('cdn_files/bootstrap_5_0.min.js')}}"></script>
<script src="{{asset('cdn_files/toastify-js.net_npm_toastify-js')}}"></script>
<script src="{{asset('cdn_files/fancybox.umd.js')}}"></script>
<script src="{{ asset("assets/vendors/general/toastr/build/toastr.min.js") }}" type="text/javascript"></script>
<script src="{{asset('web_assets/intlTelInput/intlTelInput.min.js')}}"></script>
<script src="{{asset('web_assets/js/jquery.countdown.min.js')}}"></script>
<script src="{{asset('web_assets/js/green-audio-player.min.js')}}"></script>
<script src="{{asset('web_assets/js/recorder.js')}}"></script>
<script src="{{asset('web_assets/js/recorder-app.js')}}"></script>
<script src="{{asset('web_assets/js/custom.js')}}"></script>
@yield('script')
</body>
</html>
