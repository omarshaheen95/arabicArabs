{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $student->name }}</title>
    <meta name="description" content="{{ isset(cached()->name) ? cached()->name:config('app.name') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="{{ asset('print/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('print/css/blue.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('print/css/custom-rtl.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('print/css/print.css') }}" rel="stylesheet" type="text/css"/>

    <!-- Favicon -->
    @if(isset(cached()->logo_min))
        <link rel="shortcut icon" href="{{ asset(cached()->logo_min) }}"/>
    @endif
    <script src="{{ asset('print/js/highcharts.js') }}"></script>
    <style>
        tspan {
            font-weight: bold;
        }

        .highcharts-plot-line-label-s {
            font-weight: bold !important;
        }

        .table thead tr td, .table tbody tr td {
            font-size: 16px !important;
            font-weight: bold !important;
            border: 3px solid #00FF00 !important;
        }

        .logo-name {
            font-weight: bolder !important;
            font-size: 45px !important;
            color: #223F99 !important;
            text-align: center !important
        }

        .table.lesson_table tr td {
            font-size: 12px !important;
            font-weight: bold;
            border: 1px solid #000 !important;

        }

        .table.lesson_table:first-child thead tr {
            background-color: #ffd75d !important;
        }

        .table.lesson_table thead tr td {
            background-color: #ffd75d !important;
        }

        @media print {
            tspan {
                font-weight: bold !important;
            }

            .highcharts-plot-line-label-s {
                font-weight: bold !important;
            }

            .table thead tr td, .table tbody tr td {
                font-size: 16px !important;
                font-weight: bold !important;
                border: 3px solid #00FF00 !important;
            }

            .table.lesson_table tr td {
                font-size: 12px !important;
                font-weight: bold;
                border: 1px solid #000 !important;
            }

            .table.lesson_table:first-child thead tr {
                background-color: #ffd75d !important;
            }

            .table.lesson_table thead tr td {
                background-color: #ffd75d !important;
            }


            .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
                float: left !important;
            }

            .col-sm-12 {
                width: 100% !important;
            }

            .col-sm-11 {
                width: 91.66666667% !important;
            }

            .col-sm-10 {
                width: 83.33333333% !important;
            }

            .col-sm-9 {
                width: 75% !important;
            }

            .col-sm-8 {
                width: 66.66666667% !important;
            }

            .col-sm-7 {
                width: 58.33333333% !important;
            }

            .col-sm-6 {
                width: 50% !important;
            }

            .col-sm-5 {
                width: 41.66666667% !important;
            }

            .col-sm-4 {
                width: 33.33333333% !important;
            }

            .col-sm-3 {
                width: 25% !important;
            }

            .col-sm-2 {
                width: 16.66666667% !important;
            }

            .col-sm-1 {
                width: 8.33333333% !important;
            }
        }
    </style>
    <title>{{ $student->name }}</title>
    <style>
        body{
            width: 100%;
            /* height: 100%; */
            margin: 0;
            padding: 0;
            justify-content: center;
            margin: 0 auto;
            background: transparent;
        }
    </style>

</head>
<body id="capture">


<div class="page">
    <div class="subpage-w" style="padding-bottom: 0">
        <div class="row" style="margin-top:20px;">
            <div class="col-xs-12">
                <h1 class="text-center text-red" style="font-weight: bold; font-size: 40px">Student Report</h1>
            </div>
            <div class="col-xs-8 col-xs-offset-2">
                <div class="text-center">
                    <img width="80%" src="{{ asset('logo.svg') }}"/>
                </div>
            </div>
        </div>
        <br/>
        <br/>
        <br/>
        <br/>
        <div class="row">
            <div class="col-xs-12 text-center">
                <table class="table table-bordered text-center">
                    <tbody>
                    <tr>
                        <td width="40%">Student Name</td>
                        <td>{{$student->name}}</td>
                    </tr>
                    <tr>
                        <td width="40%">Email</td>
                        <td>{{$student->email}}</td>
                    </tr>
                    <tr>
                        <td width="40%">Teacher Name</td>
                        <td>{{$teacher->name ?? ''}}</td>
                    </tr>
                    <tr>
                        <td width="40%">School</td>
                        <td>{{$student->school->name}}</td>
                    </tr>
                    <tr>
                        <td width="40%">Grade</td>
                        <td>{{$student->grade}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <br/>
        <div class="row text-center">
            <img src="{{asset('website/images/features.bmp')}}" width="80%">
        </div>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <div class="row text-center">
            <h5 style="font-weight: bold">Powered by A.B.T Education.</h5>
        </div>
        <br/>
        <br/>
        <br/>
        <div class="row text-center">
            <img src="{{asset('website/images/logos_banner.bmp')}}" width="100%">
        </div>
    </div>
</div>
@foreach($lessons_info as $lessons)
    <div class="page">
        <div class="subpage-w" style="padding-bottom: 0;padding-top: 0; ">
            @foreach($lessons as $lesson)
                @if($loop->last && !$loop->first)
                    <hr style="border-top: 2px solid #c5c5c5;"/>
                @endif
                <div class="row">
                    <div class="col-xs-12">
                        <h4 class="text-center" style="font-weight: bold">{{$lesson['lesson']->getTranslation('name', 'en')}} - {{$lesson['lesson']->level->getTranslation('name', 'en')}} </h4>
                        <table class="table table-bordered text-center lesson_table">
                            <thead>
                            <td>Assessment Score</td>
                            <td>Time Consumed</td>
                            <td>Assessment Date</td>
                            <td>Reading Mark</td>
                            <td>Speaking Mark</td>
                            <td>Tasks Date</td>
                            </thead>
                            <tbody>
                            <tr>
                                @if(isset($lesson['user_test']) && !is_null($lesson['user_test']))
                                    <td>{{$lesson['user_test']->total_per}}</td>
                                    <td>{{$lesson['time_consumed']}}</td>
                                    <td>{{optional($lesson['user_test']->created_at)->format('d M Y')}}</td>

                                @else

                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @endif
                                @if(isset($lesson['user_test']) && !is_null($lesson['user_lesson']))
                                    <td>{{$lesson['user_lesson']->writing_mark}}</td>
                                    <td>{{$lesson['user_lesson']->reading_mark}}</td>
                                    <td>{{optional($lesson['user_lesson']->created_at)->format('d M Y')}}</td>

                                @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @endif
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-5">
                        <div id="container_lesson_bar_{{ optional($lesson['user_test'])->id }}"
                             style="height: 330px"></div>
                    </div>
                    <div class="col-xs-7">
                        <div id="lesson_tracker_{{ optional($lesson['lesson'])->id }}"
                             style="height: 330px"></div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
@endforeach


<script src="{{ asset('assets/vendors/general/jquery/dist/jquery.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
<!--begin:: Global Optional Vendors -->
<!--begin::Global Theme Bundle(used by all pages) -->
<!-- begin::Global Config(global config for global JS sciprts) -->


<script type="text/javascript">
    $(document).ready(function () {
        //window.print();
    });
</script>
<!-- End -->
<script type='text/javascript'>//<![CDATA[
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
    var colors = ['#2525ef', '#0fee00', '#f16e31', '#c8018b',];
    @foreach($lessons_info as $lessons)
    @foreach($lessons as $lesson)
    @if($lesson['user_test'])
    new Highcharts.Chart({

        chart: {
            renderTo: 'container_lesson_bar_{{ $lesson['user_test']->id }}',
            type: 'column'
        },

        xAxis: {
            categories: [
                '',
            ],
            labels: {
                style: {
                    color: "#F00",
                    fontSize: "6px",
                }
            },

        },

        yAxis: {
            title: {
                text: ''
            },
            tickInterval: 10,
            min: 0,
            max: 100,

        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0,
                events: {
                    legendItemClick: function () {
                        return false;
                    }
                }
            },
            allowPointSelect: false,
            series: {
                animation: false,
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y} %',
                    color: '#000'
                }
            }
        },

        title: {
            text: ' ',
            useHTML: Highcharts.hasBidiBug
        },

        legend: {
            useHTML: Highcharts.hasBidiBug
        },

        tooltip: {
            useHTML: true
        },

        series: [
            {
                name: "{{$lesson['lesson']->name}}",
                colorByPoint: true,
                data: [
                    {
                        name: "{{$lesson['lesson']->name}}", y: {{$lesson['user_test']->total * 2}}, color: '#C50FF7'
                    }
                ]
            }
            ]



            });
    @endif
    Highcharts.setOptions({
        colors: ['#f0f303', '#00a5ee', '#ec4102', '#47e51c',  '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4']
    });
    @if($lesson['tracker'])
        Highcharts.chart("lesson_tracker_{{ $lesson['lesson']->id }}", {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            color: "#F00",
            text: null,
            style: {
                font: 'bold 15px "Trebuchet MS", Verdana, sans-serif',
                color: '#000',
            }
        },
        legend: {
            align:'center',
            itemStyle: {
                fontSize:'14px',
                color: '#000',
                align:'center',
            },
        },
        tooltip: {
            pointFormat: '<b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: false,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<br>{point.percentage:.1f} %',
                    distance: -50,
                    style: {
                        font: 'bold 11px "Trebuchet MS", Verdana, sans-serif',
                        color: "#000",
                        textOutline: 0
                    }
                },
                showInLegend: true,
            },
            series: {
                animation: false
            }
        },



        series: [{
            type: 'pie',
            name: 'Rate',
            data: [
                ['Learn', {{ $lesson['learnings'] }}],
                ['Practise', {{ $lesson['trainings'] }}],
                ['Assess your self', {{ $lesson['tests'] }}],
                ['Play', {{ $lesson['games'] }}],
            ]
        }]
    });
    @endif
    @endforeach
    @endforeach


</script>
{{--<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>--}}
{{--<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>--}}
{{--<script src="//cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min.js"></script>--}}
{{--<script>--}}
{{--    html2canvas(document.querySelector("#capture")).then(canvas => {--}}
{{--        document.body.appendChild(canvas);--}}
{{--        console.log('test');--}}
{{--        html2canvas(canvas, {--}}
{{--            onrendered: function(canvas) {--}}
{{--                var imgData = canvas.toDataURL(--}}
{{--                    'image/png');--}}
{{--                var doc = new jsPDF('p', 'mm');--}}
{{--                doc.addImage(imgData, 'PNG', 10, 10);--}}
{{--                doc.save('sample-file.pdf');--}}
{{--            }--}}
{{--        });--}}
{{--    });--}}
{{--    // html2canvas(document.querySelector("#capture")).then(canvas => {--}}
{{--    //     document.body.appendChild(canvas)--}}
{{--    // });--}}

{{--</script>--}}

</body>
