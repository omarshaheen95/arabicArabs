{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
    <!DOCTYPE html>
<html lang="en" translate="no">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ isset($title) ? $title. " | ":''  }}منصة لغتي الأولى</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{asset('website/images/icons/icon.png')}}">
    <!-- Bootstrap -->
    <link href="{{asset('website/css/bootstrap/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/general/toastr/build/toastr.css') }}" rel="stylesheet">

    <!-- Custom style -->
    <link href="{{asset('website/css/style.css')}}" rel="stylesheet">
        <link href="{{asset('website/css/style-ar.css')}}" rel="stylesheet">
<!-- Animated -->
    <link href="{{asset('website/css/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('website/css/animated-headline.css')}}" rel="stylesheet">
    <!-- Shuffle -->
    <link href="{{asset('website/css/custom-shuffle.css')}}" rel="stylesheet">
    <!-- Slick -->
    <link href="{{asset('website/css/slick.css')}}" rel="stylesheet">
    <link href="{{asset('website/css/slick-theme.css')}}" rel="stylesheet">
    <link href="{{asset('website/css/custom-slick.css')}}" rel="stylesheet">
    <!-- Section animation -->
    <link href="{{asset('website/css/aos.css')}}" rel="stylesheet">
    <!-- Responsive -->
    <link href="{{asset('website/css/responsive.css')}}" rel="stylesheet">

    <link href="{{asset('website/fonts/materialdesign-web/css/materialdesignicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('website/fonts/flaticon/flaticon.css')}}" rel="stylesheet">
    @yield('style')
</head>
<body id="page-top">

<!-- START LOADER -->
<div class="loader-fix theme-bg-secondary-light">
    <div class="loader-center">
        <div class="loader-dots"></div>
    </div>
</div>
<!-- END LOADER -->

<!-- START NAVIGATION -->
<div id="navigation">
    <nav class="navbar navbar-expand-lg p-0" id="mainNav">
        <div class="container-fluid">
            <a class="logo h3 js-scroll-trigger d-flex align-items-center" href="#page-top">
                <div>
                    <img src="{{asset('logo.png')}}" alt="" width="50%">
                </div>
            </a>
            <button class="navbar-toggler navbar-toggler-right theme-btn theme-btn-default" type="button"
                    data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive"
                    aria-expanded="false" aria-label="Toggle navigation">
                        <span>
                            <i class="mdi mdi-menu mdi-24px"></i>
                        </span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li><a class="nav-link js-scroll-trigger"
                           @if(Request::is(''))
                           href="#page-top"
                           @else
                           href="/#page-top"
                            @endif
                        >الرئيسة</a></li>
                    <li><a class="nav-link js-scroll-trigger"
                           @if(Request::is(''))
                           href="#pricing"
                           @else
                           href="/#price_table"
                            @endif
                        >التكلفة</a></li>

                    <li><a class="nav-link js-scroll-trigger"
                           href="{{asset('User_Guide.pdf')}}"
                        >دليل المستخدم</a></li>
                    <li><a class="nav-link js-scroll-trigger" href="/school/login"> دخول المدرسة </a></li>
                    <li><a class="nav-link js-scroll-trigger" href="/login"> دخول الطالب </a></li>
                    <li><a class="nav-link js-scroll-trigger" href="/teacher/login"> دخول المعلم </a></li>

                    <li><a class="nav-link js-scroll-trigger"
                           @if(Request::is(''))
                           href="#contact"
                           @else
                           href="/#contact"
                            @endif
                        >اتصل بنا</a></li>
                </ul>
            </div>
        </div>
    </nav>
</div>
<!-- END NAVIGATION -->

@yield('content')
<!-- START FOOTER -->
<div id="footer" class="theme-bg-secondary aos-item" data-aos="fade-in">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="row d-flex align-items-center">
                    <div class="col-lg-3">
                        <div class="footer-item">
                            <a href="{{route('page', 'privacy_policy')}}" class="mr-3">سياسة الخصوصية</a>
                            <a href="{{route('page', 'terms&conditions')}}"
                               class="mr-3">الشروط والأحكام </a>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="footer-item">
                            <p>جميع الحقوق محفوظة © 2020</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="footer-item">
                            <ul class="social-media-icons">

                                    <li>
                                        <a href="https://www.facebook.com/ArabicIslamiconline/" class="icon-bg icon-bg-sm theme-btn-default">
                                            <i class="mdi mdi-facebook"></i>
                                        </a>
                                    </li>


                                    <li>
                                        <a href="https://twitter.com/ArabicAdmin" class="icon-bg icon-bg-sm theme-btn-default">
                                            <i class="mdi mdi-twitter"></i>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="https://www.linkedin.com/company/a-b-t-assessments/" class="icon-bg icon-bg-sm theme-btn-default">
                                            <i class="mdi mdi-linkedin"></i>
                                        </a>
                                    </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END FOOTER -->

<!-- START BACK TO TOP -->
<div id="backtotop">
    <a href="#page-top" class="js-scroll-trigger theme-btn-default icon-bg icon-bg-md">
        <i class="flaticon-double-right-arrows-angles"></i>
    </a>
</div>
<!-- END BACK TO TOP -->

<!-- Bootstrap core JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
{{--<script src="{{asset('website/js/jquery.min.js')}}"></script>--}}
<script src="{{asset('website/js/bootstrap.bundle.min.js')}}"></script>
<!-- Third party plugin JS-->
<script src="{{asset('website/js/jquery.easing.min.js')}}"></script>
<!-- Animated headline -->
<script src="{{asset('website/js/main.js')}}"></script>
<script src="{{asset('website/js/modernizr.js')}}"></script>
<!-- Shuffle -->
{{--<script src='{{asset('website/js/shuffle.min.js')}}'></script>--}}
{{--<script src='{{asset('website/js/custom-shuffle.js')}}'></script>--}}
<!-- Slick -->
<script src="{{asset('website/js/slick.min.js')}}"></script>
<!-- Section animation -->
<script src="{{asset('website/js/aos.js')}}"></script>
<script src="{{ asset("assets/vendors/general/toastr/build/toastr.min.js") }}" type="text/javascript"></script>

<!-- Core theme JS-->
@if(app()->getLocale() == 'ar')
    <script src="{{asset('website/js/custom-script.'.direction('.').'js')}}"></script>
@else
    <script src="{{asset('website/js/custom-script.js')}}"></script>
@endif

<script type="text/javascript">
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
</script>
@yield('script')

</body>
</html>
