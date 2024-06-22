<!doctype html>
<html lang="ar" dir="rtl">
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
    <link rel="shortcut icon" href="{{asset('logo_min.svg')}}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS v5.0.2 -->
    <link rel="stylesheet" href="{{asset('cdn_files/bootstrap.rtl.min.css')}}">
    @yield('pre_style')
    <link rel="stylesheet" href="{{asset('web_assets/css/custom.css')}}?v2">
    <link rel="stylesheet" href="{{asset('web_assets/css/resposive.css')}}">
    <link href="{{ asset('assets/vendors/general/toastr/build/toastr.css') }}" rel="stylesheet" />
    @yield('style')

</head>
<body class="student-page user-page">
<div class="loader"></div>

<nav class="user-page-navbar">
    <div class="container">
        <div class="user-navbar">

        </div>
    </div>
</nav>
<!-- Start Navbar -->
<nav class="navbar navbar-expand-xl navbar-light">
    <div class="container">
        <div class="navbar-container">
            <div class="navbar-header d-flex justify-content-between aling-items-center w-100" >
                <a class="logo" href="/home">
                    <img src="{{asset('web_assets/img/logo.svg')}}" alt="">
                </a>
                <div class="btn-collapse ms-3 d-xl-none" data-bs-toggle="collapse" data-bs-target="#main-menu">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="45" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </div>
            </div>

            <div class="collapse navbar-collapse justify-content-end" id="main-menu">
            </div>
        </div>
    </div>
</nav>
<!-- End Navbar -->
<!-- Start Main -->
<main class="wrapper" id="user-home">
    <!-- Start user-home -->
    <div class="container">
        <div class="row">
            @if (count($errors) > 0)
                <div class="alert alert-warning">
                    <ul style="width: 100%;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@yield('content')
<!-- End user-home -->
</main>
<!-- End Main -->
<!-- Start Footer -->
<footer>
    <div class="container">
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

<script src="{{asset('s_website/js/script.js')}}"></script>
{{--<script src="{{ asset("js/push.min.js") }}" type="text/javascript"></script>--}}
{{--<script src="https://js.pusher.com/7.0/pusher.min.js"></script>--}}
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "100",
        "hideDuration": "2000",
        "timeOut": "10000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    @if(Session::has('message'))
    toastr.{{Session::get('m-class') ? Session::get('m-class'):'success'}}("{{Session::get('message')}}");
    @endif
    $(document).keydown(function (event) {
        if (event.ctrlKey == true && (event.which == '67' /*|| event.which == '86'*/)) {
            event.preventDefault();
        }
    });
    document.addEventListener('contextmenu', function (e) {
        e.preventDefault();
    });

</script>

@yield('script')

<script>
    $(document).on("click", ".btn-logout", function(){
        $("#logout-modal").modal("show");
    });
</script>
</body>
</html>
