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
    <link rel="shortcut icon" href="{{asset('web_assets/img/logo.svg')}}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Bootstrap CSS v5.0.2 -->
    <link rel="stylesheet" href="{{asset('cdn_files/bootstrap.rtl.min.css')}}">
    @yield('pre_style')
    <link rel="stylesheet" type="text/css" href="{{asset('web_assets/css/green-audio-player.min.css')}}">

    <link rel="stylesheet" href="{{asset('web_assets/css/custom.css')}}?v1">
    <link rel="stylesheet" href="{{asset('web_assets/css/resposive.css')}}">
    <link href="{{ asset('assets/vendors/general/toastr/build/toastr.css') }}" rel="stylesheet" />


    @yield('style')

</head>
<body class="student-page user-page">
<div class="loader"></div>

<nav class="user-page-navbar">
    <div class="container">
        <div class="user-navbar">
            <div class="user-box">
                <div class="pic">
                    <svg xmlns="http://www.w3.org/2000/svg" width="23.589" height="31.195" viewBox="0 0 23.589 31.195">
                        <path id="Path_66479" data-name="Path 66479" d="M26.665,31.26l5.481,2.99a4.926,4.926,0,0,1,.88.626,18.359,18.359,0,0,1-23.589.061,4.863,4.863,0,0,1,.966-.647l5.87-2.934a2.238,2.238,0,0,0,1.237-2v-2.3a8.863,8.863,0,0,1-.555-.714,13.506,13.506,0,0,1-1.825-3.677,1.844,1.844,0,0,1-1.307-1.754V18.447a1.835,1.835,0,0,1,.614-1.362V13.531S13.707,8,21.2,8s6.759,5.53,6.759,5.53v3.554a1.833,1.833,0,0,1,.614,1.362v2.458a1.843,1.843,0,0,1-.85,1.547,12.2,12.2,0,0,1-2.223,4.6V29.3A2.241,2.241,0,0,0,26.665,31.26Z" transform="translate(-9.437 -8.001)" fill="#d9e3fd"/>
                    </svg>
                </div>
                <div class="content">
                    <h3 class="username"> {{auth()->user()->name}} </h3>
                </div>
            </div>
            <div class="user-box">
                <div class="content"  dir="ltr">
                    <p class="info"> آخر دخول: {{auth()->user()->last_login}} </p>
                </div>
            </div>
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
                <ul class="navbar-nav">
                    <li class="nav-item d-xl-none">
                        <div class="navbar-logo text-end">
                            <a href="#main-menu" class="navbar-close" data-bs-toggle="collapse"> &times; </a>
                        </div>
                    </li>

                    <li class="nav-item {{Request::is('/supervisor/home') || Request()->is('curriculum') ? 'active':''}}">
                        <a class="nav-link" href="{{route('supervisor.home', $grade)}}">الرئيسة</a>
                    </li>
                    @if(!Route::is('supervisor.*'))
                    <li class="nav-item {{Request::is('/teacher/curriculum') || Request()->is('home') ? 'active':''}}">
                        <a class="nav-link" href="{{route('teacher.levels', $grade)}}">المهارات والدروس</a>
                    </li>
                    <li class="nav-item {{Request::is('/teacher/curriculum') || Request()->is('home') ? 'active':''}}">
                        <a class="nav-link" href="{{route('teacher.levels.stories', $grade)}}">مكتبتي العربية</a>
                    </li>


                    {{--                    <li class="nav-item">--}}
                    {{--                        <a class="nav-link" href="{{ route('package_upgrade') }}"> {{w('Subscription')}} </a>--}}
                    {{--                    </li>--}}

                    <li class="nav-item">
                        <a class="nav-link btn btn-soft-danger btn-logout" href="#">
                           تسجيل الخروج

                        </a>
                    </li>
                    @endif
                </ul>
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
<!-- Logout Modal -->
<div class="modal fade" id="logout-modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="logout-box">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 16.522 16.643">
                            <path id="Path_98991" data-name="Path 98991" d="M7.06,7.737V1.29a1.29,1.29,0,0,1,2.579,0V7.737a1.29,1.29,0,1,1-2.579,0Zm6.4-5.846a.967.967,0,0,0-1.2,1.519,6.327,6.327,0,1,1-7.829,0,.967.967,0,0,0-1.2-1.519,8.261,8.261,0,1,0,10.225,0Z" transform="translate(-0.089)" fill="#dc3545"/>
                        </svg>
                    </div>
                    <h2 class="title">تسجيل خروج</h2>
                    <p class="info text-danger"> هل أنت متأكد من تسجيل الخروج</p>

                    <form id="logout-form" action="{{ url("/teacher/logout") }}" method="POST"
                          style="display: none;">
                        {{ csrf_field() }}
                    </form>
                    <div class="text-center">
                        <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                type="button" class="btn btn-soft-danger me-3"> تسجيل الخروج</button>
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal"> إلغاء </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
<script src="{{ asset("js/push.min.js") }}" type="text/javascript"></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
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


    Push.Permission.get();

    function notifyMe(notify) {

        toastr.success(notify.title);
        Push.create(notify.title, {
            icon: 'https://www.non-arabs.com/website/images/icons/icon.png',
            timeout: 4000,
            onClick: function () {
                window.focus();
                this.close();
            }
        });

        $('#userAssignmentsCount').text(notify.unread_notifications);
    }



    var pusher = new Pusher('dca3e1c68a7801d90df2', {
        cluster: 'mt1'
    });

    call_back = function (message) {
        notifyMe(message)
        console.log(message);
    };
    //Also remember to change channel and event name if your's are different.
    var channel = pusher.subscribe('users');
    channel.bind('user-notification', call_back);

    var user_channel = pusher.subscribe('user_{{auth()->user()->id}}');
    user_channel.bind('user-notification', call_back);



</script>

@yield('script')

<script>
    $(document).on("click", ".btn-logout", function(){
        $("#logout-modal").modal("show");
    });
</script>
</body>
</html>
