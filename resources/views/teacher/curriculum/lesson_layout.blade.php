{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
    <!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="اللغة العربية لغير الناطقين،  غير الناطقين،  دروس غير الناطقين بالعربية Non-Arabs, Non-arabs.com, Non Arabs">
    <meta name="keywords" content="اللغة العربية لغير الناطقين،  غير الناطقين،  دروس غير الناطقين بالعربية Non-Arabs, Non-arabs.com, Non Arabs">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? $title. " | ":''  }}{{ t('Non Arab') }}</title>
    <link href="{{asset('s_website/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/general/toastr/build/toastr.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('s_website/css/style.css')}}" rel="stylesheet">
    @yield('style')
    <link rel="shortcut icon" href="{{asset('website/images/logo.svg')}}" />
</head>
<body>

<div class="loader"></div>

<header class="position-fixed w-100">
    <div>
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-3 text-right">
                    <div class="logo py-1 px-1"><a href="#"><img src="{{asset('web_assets/img/logo.svg')}}" width="50%"></a></div>

                </div>
                <div class="col-md-9">
                    <nav class="navbar-expand-lg navbar-light">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container">
    <section class="main-inner-page">
        <section>
            <div class="container text-right">
                <div class="row">
                    <div class="col-12">
                        @if(!Request::is('home'))
                            <ul class="breadcrumb mt-5 " @if(isset($level) && !is_null($level->color)) style="background-color: {{$level->color}}; font-weight: bold" @endif>
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/home') }}" @if(isset($level) && !is_null($level->text_color)) style="color: {{$level->text_color}} !important; font-weight: bold" @else style="font-weight: bold" @endif>{{ t('Home') }}</a>
                                </li>
                                @stack('breadcrumb')
                            </ul>
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
                        @yield('content')
                    </div>
                </div>
            </div>
        </section>
    </section>
</div>

<script src="{{asset('s_website/js/jquery-3.4.1.min.js')}}" ></script>
<script src="{{asset('s_website/js/popper.min.js')}}" ></script>
<script src="{{asset('s_website/js/bootstrap.min.js')}}" ></script>
<script src="{{ asset("assets/vendors/general/toastr/build/toastr.min.js") }}" type="text/javascript"></script>

<script src="{{asset('s_website/js/script.js')}}" ></script>
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
</script>

@yield('script')
</body>
</html>
