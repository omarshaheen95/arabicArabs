{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
    <!DOCTYPE html>
<html lang="ar" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? $title. " | ":''  }}منصة لغتي الأولى</title>
    <link href="{{asset('s_website/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/general/toastr/build/toastr.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('s_website/css/style.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('style')
    <link rel="shortcut icon" href="{{asset('website/images/logo.svg')}}"/>
</head>
<body>

<div class="loader"></div>

<header class="position-fixed w-100">
    <div>
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-3 text-right">
                    <div class="logo py-1 px-1"><a href="#"><img src="{{asset('logo.png')}}" width="50%"></a>
                    </div>

                </div>
                <div class="col-md-9">
                    <nav class="navbar-expand-lg navbar-light">
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="list-inline m-0 p-0">
                                {{--                                <li class="list-inline-item text-right font-weight-bold">--}}
                                {{--                                    <span>{{t('School')}}: {{optional(Auth::user()->school)->name}}</span>--}}
                                {{--                                </li>--}}
                                <li class="list-inline-item text-right font-weight-bold">
                                    <span>{{Auth::user()->grade->name}}</span>
                                </li>
                                @php
                                    $userAssignmentsCount = 0;
 /*\App\Models\UserAssignment::query()
    ->where('user_id', Auth::user()->id)
    ->where('completed', 0)
    ->count();*/
                                @endphp
                                <li class="list-inline-item text-right font-weight-bold mx-0">
                                    <a class="nav-link active" href="{{route('assignments')}}">
                                        <i class="fas fa-pencil"></i>
                                        {{--                                        @if($userAssignmentsCount)--}}
                                        <span id="userAssignmentsCount"
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-white">
                                            {{$userAssignmentsCount}}
                                        </span>
                                        {{--                                        @endif--}}
                                    </a>
                                </li>
                                <li class="list-inline-item text-right font-weight-bold">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre
                                       style="color: #000">
                                        <i class="fas fa-user"></i>
                                        <span>{{Auth::user()->name}}</span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                        <a class="dropdown-item" href="{{ route('certificates') }}">
                                            الشهادات
                                        </a>
                                        <a class="dropdown-item" href="{{ route('assignments') }}">
                                            الواجبات
                                        </a>
                                        <a class="dropdown-item" href="{{ route('profile') }}">
                                            الملف الشخصي
                                        </a>
                                        <a class="dropdown-item" href="{{ route('update_password_view') }}">
                                            تغيير كلمة المرور
                                        </a>
                                        <a class="dropdown-item" href="{{ route('package_upgrade') }}">
                                            ترقية الإشتراك
                                        </a>
                                        <form id="logout-form" action="{{ url("/logout") }}" method="POST"
                                              style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                        <a class="dropdown-item" href="{{ url("/logout") }}" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                            تسجيل الخروج
                                        </a>

                                    </div>
                                </li>
                            </ul>
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
                            <ul class="breadcrumb mt-5 "
                                @if(isset($level) && !is_null($level->color)) style="background-color: {{$level->color}}; font-weight: bold" @endif>
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/home') }}"
                                       @if(isset($level) && !is_null($level->text_color)) style="color: {{$level->text_color}} !important; font-weight: bold"
                                       @else style="font-weight: bold" @endif>الرئيسة</a>
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
<div class="modal fade" id="logout" tabindex="-1" aria-labelledby="logoutLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content px-4">
            <div class="modal-body text-center py-5"><h2
                    class="mb-0"> هل أنت متأكد من تسجيل الخروج ؟ </h2></div>
            <div class="modal-footer border-0 justify-content-center">
                <form action="/logout" method="post">
                    {{ csrf_field() }}
                    <button type="button" class="theme-btn btn-style-one btnSecondary" data-dismiss="modal"><span
                            class="txt"> البقاء في الصفحة الحالية </span></button>
                    <button type="submit" class="theme-btn btn-style-one btnSuccess"><span
                            class="txt"> تسجيل الخروج </span></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('s_website/js/jquery-3.4.1.min.js')}}"></script>
<script src="{{asset('s_website/js/popper.min.js')}}"></script>
<script src="{{asset('s_website/js/bootstrap.min.js')}}"></script>
<script src="{{ asset("assets/vendors/general/toastr/build/toastr.min.js") }}" type="text/javascript"></script>

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
</body>
</html>
