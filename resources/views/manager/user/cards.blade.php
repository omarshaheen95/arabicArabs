<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ isset(cached()->name) ? cached()->name:'Arabic Reading Benchmark Test' }}"><meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{ asset('print/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('print/css/blue.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('print/css/custom-rtl.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('print/css/print.css') }}" rel="stylesheet" type="text/css" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset(cached()->logo_min) }}" />

    <title>Students Cards {{$school->name}}</title>
    <style>
        .page{
            background-image: none;
        }
        .subpage-w{
            padding: 0;
            border: 0;
        }
        .student_card{
            height: 90mm;
            border: 2px solid #000;
        }
        .student_card ul{
            margin-top: 5px !important;
        }
        .student_card ul li{
            font-size: 12px;
        }
        .card_container{
            margin-bottom: 1mm;
        }
        .student_image{
            height: 26mm;
            width: 20mm;
        }
        .student_image img{
            height: 100%;
            width: 19mm;
            border: 2px solid #000;
            border-top: 0;
            border-left: 0;
        }
        .info{
            margin: 0;
            padding: 3px;
            padding-left: 13px;
            font-size: 12px;
        }
        .info:first-of-type{
            padding-top: 0;
            margin-top: 8px;
        }
        .info:last-of-type{
            padding-bottom: 0px;
        }
        .bar_mid{
            width: 100%;
            height: 2px;
            display: block;
            border: 1px dotted #000;
        }
        .student_card .logo_sim{
            width: 100%;
            margin-bottom: 3px;
            margin-top: 10px;
            margin-left: 10px;
        }
        .student_card h5{
            font-size: 12px;
        }
        .bolder{
            font-weight: bolder
        }
        div.header_card {
            position: relative;
            height: 100px;
        }

        div.inner_r {
            position: absolute;
            top: 50%; right: -28%;
            transform: translate(-50%,-50%);
        }

        div.inner_l {
            position: absolute;
            top: 50%; left: 25%;
            transform: translate(-50%,-50%);
        }
    </style>
</head>
<body>
@php
    $count = 0;

@endphp
@foreach($students as $student)
    <div class="page">
        <div class="subpage-w">
            <div class="row">
                @foreach($student as $std)
                    <div class="col-xs-6 card_container">
                        <div class="student_card">
                            <div class="row header_card">
                                @if($school)
                                <div class="col-xs-5 text-center inner_l">
                                    @if(optional($school)->logo)
                                    <img class="logo_sim" style="height: 80px;width: auto; " src="{{ asset(optional($school)->logo) }}" />
                                    @endif
                                </div>
                                @endif
                                <div class="col-xs-7  inner_r">
                                    <h5 class="text-center" style="font-weight:bold">A.B.T Education
                                        </h5>
                                    <h5 class="text-center" style="font-weight:bold">Non-Arabs Platform</h5>
                                </div>

                            </div>
                            <div class="bar_mid"></div>
                            <div class="row">
                                <div class="col-xs-12 studnet_info">
                                    <p class="info">School:
                                        <span class="bolder red-font" style="font-size: 12px;">
                                            {{$std->school->name}}
                                        </span>
                                    </p>
                                    <p class="info">Student Name:
                                        <span class="bolder red-font" style="font-size: 12px;">
                                            {{ $std->name }}
                                        </span>
                                    </p>
                                    <p class="info">Grade:
                                        <span class="bolder red-font" style="font-size: 12px;">
                                            {{ $std->grade }}
                                        </span>
                                    </p>
                                    <p class="info" >Teacher Name:
                                        <span class="bolder red-font" style="font-size: 12px;">
                                            {{ optional(optional($std->teacher_student)->teacher)->name }}
                                        </span>
                                    </p>
                                    <p class="info">Years of learning Arabic:
                                        <span class="bolder red-font" style="font-size: 12px;">
                                            {{ $std->year_learning }}
                                        </span>
                                    </p>
                                    <ul>
                                        <li>Step 1: www.Non-Arabs.com</li>
                                        <li>Step 2: Student login</li>
                                        <li>Step 3: Email: <span class="bolder red-font" style="font-size: 12px;">{{$std->email}}</span></li>
                                        <li>Step 4: Password: <span class="bolder red-font">123456</span></li>
                                        <li>Step 5: Login</li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>


        </div>
    </div>
@endforeach
<!-- BEGIN CORE PLUGINS -->
<script src="{{ asset('assets/vendors/general/jquery/dist/jquery.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/popper.js/dist/umd/popper.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/js-cookie/src/js.cookie.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/moment/min/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset("assets/vendors/general/sticky-js/dist/sticky.min.js") }}" type="text/javascript"></script>

<script src="{{ asset('assets/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js') }}" type="text/javascript"></script>


<!--begin:: Global Optional Vendors -->
<script src="{{ asset('assets/vendors/general/toastr/build/toastr.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/vendors/general/sweetalert2/dist/sweetalert2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/custom/js/vendors/sweetalert2.init.js') }}" type="text/javascript"></script>


<!--begin::Global Theme Bundle(used by all pages) -->
<script src="{{ asset('assets/js/demo2/scripts.bundle.js') }}" type="text/javascript"></script>
<!-- begin::Global Config(global config for global JS sciprts) -->
@yield('script')
<script>
    var KTAppOptions = {
        "colors": {
            "state": {
                "brand": "#374afb",
                "light": "#ffffff",
                "dark": "#282a3c",
                "primary": "#5867dd",
                "success": "#34bfa3",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995"
            },
            "base": {
                "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
            }
        }
    };
</script>
<!-- End -->
</body>
