<!DOCTYPE html>
<html lang="ar" dir="rtl">

<!-- begin::Head -->
<head>
    <!--end::Base Path -->
    <meta charset="utf-8" />
    <title>استعادة كلمة المرور</title>
    <meta name="description" content="Dashboard">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--begin::Fonts -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!--end::Fonts -->

    <!--begin::Page Custom Styles(used by this page) -->

    <link href="{{ asset("assets/css/demo6/pages/general/login/login-6.rtl.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("assets/css/demo6/style.bundle.rtl.css") }}" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->
    <!--end::Layout Skins -->
</head>

<!-- end::Head -->

<!-- begin::Body -->
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

<!-- begin:: Page -->
<div class="kt-grid kt-grid--ver kt-grid--root">
    <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v6 kt-login--signin" id="kt_login">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">
            <div class="kt-grid__item  kt-grid__item--order-tablet-and-mobile-2  kt-grid kt-grid--hor kt-login__aside">
                <div class="kt-login__wrapper">
                    <div class="kt-login__container">
                        <div class="kt-login__body">
                            <div class="kt-login__logo">
                                <a href="{{ url('/') }}">
                                    <img src="{{asset('logo.png')}}" width="" alt="logo" class="img-fluid"/>
                                </a>
                            </div>
                            <div class="kt-login__signin">
                                <div class="kt-login__form">
                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    <form class="kt-form" action="{{ url('teacher/password/email') }}" method="post">
                                        {{ csrf_field() }}

                                        <div class="form-group">
                                            <input class="form-control {{ $errors->has('email') ? ' has-error' : '' }}" type="text" placeholder="البريد الإلكتروني" name="email">
                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                            @endif
                                        </div>

                                        <div class="kt-login__extra">

                                            <a href="/teacher/login">تسجيل الدخول ؟</a>
                                        </div>
                                        <div class="kt-login__actions">

                                            <input type="submit" id="" class="btn btn-danger btn-pill btn-elevate" value="ارسال رابط استعادة كلمة المرور">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-login__account">
                                    <span class="kt-login__account-msg">
                                        {{ date('Y') }}&nbsp;&copy;&nbsp;<a href="https://www.abt-assessments.com/" target="_blank" class="kt-link kt-font-danger">ABT assessment</a>
                                    </span>&nbsp;&nbsp;

                    </div>
                </div>
            </div>
            <div class="kt-grid__item kt-grid__item--fluid kt-grid__item--center kt-grid kt-grid--ver kt-login__content" style="background: linear-gradient(to right,#39B448,#31ab3f);">
                <div class="kt-login__section">
                    <div class="kt-login__block text-center">

                        <div class=" mb-5">
                            {{--                                <img src="{{ asset('assets/media/logos/white_logo.svg') }}" width="40%" alt="logo" class="img-fluid"/>--}}
                        </div>

                        <h3 class="kt-login__title" style="font-size: 2.2rem">التدريس</h3>
                        <h4 class="kt-login__desc " style="font-size: 28px">
                            استعادة كلمة المرور .
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- end:: Page -->

<!-- begin::Global Config(global config for global JS sciprts) -->
<script>
    var KTAppOptions = {
        "colors": {
            "state": {
                "brand": "#5d78ff",
                "dark": "#282a3c",
                "light": "#ffffff",
                "primary": "#5867dd",
                "success": "#34bfa3",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995"
            },
            "base": {
                "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#000000"]
            }
        }
    };
</script>

<!-- end::Global Config -->

<!--begin:: Global Mandatory Vendors -->
<script src="{{ asset('assets/vendors/general/jquery/dist/jquery.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/popper.js/dist/umd/popper.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/js-cookie/src/js.cookie.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/moment/min/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/tooltip.js/dist/umd/tooltip.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/sticky-js/dist/sticky.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/wnumb/wNumb.js') }}" type="text/javascript"></script>

<!--end:: Global Mandatory Vendors -->

<!--begin:: Global Optional Vendors -->

<!--end:: Global Optional Vendors -->

<!--begin::Global Theme Bundle(used by all pages) -->

<!--end::Global Theme Bundle -->

<!--begin::Page Scripts(used by this page) -->
<script src="{{ asset('assets/js/demo1/pages/login/login-general.js') }}" type="text/javascript"></script>

<!--end::Page Scripts -->
</body>

<!-- end::Body -->
</html>
