{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $teacher->name }}</title>
    <meta name="description" content="{{ isset(cached()->name) ? cached()->name:'Non-Arabs student report' }}">
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
    <title>{{ $teacher->name }}</title>

</head>
<body>


<div class="page">
    <div class="subpage-w" style="padding-bottom: 0">
        <div class="row" style="margin-top:20px;">
            <div class="col-xs-12">
                <h1 class="text-center text-red" style="font-weight: bold; font-size: 40px">Teacher Report</h1>
            </div>
            <div class="col-xs-8 col-xs-offset-2">
                <div class="text-center">
                    <img width="80%" src="{{ asset('logo.svg') }}"/>
                </div>

                {{--                <div class="logo-name">--}}
                {{--                    <h1 class="logo-name">Non-Arabs</h1>--}}
                {{--                    <span class="small" style="color: #000000; font-size: 14px; font-weight: bold">Learn, Practice, Enjoy, achieve and play</span>--}}
                {{--                </div>--}}
            </div>
        </div>
        <br/>
        <br/>
        <div class="row">
            <div class="col-xs-12 text-center">
                <table class="table table-bordered text-center">
                    <tbody>
                    <tr>
                        <td width="40%">Teacher Name</td>
                        <td>{{$teacher->name}}</td>
                    </tr>
                    <tr>
                        <td width="40%">Email</td>
                        <td>{{$teacher->email}}</td>
                    </tr>
                    <tr>
                        <td width="40%">Mobile</td>
                        <td>{{$teacher->mobile}}</td>
                    </tr>
                    <tr>
                        <td width="40%">175</td>
                        <td>{{$teacher->175->name}}</td>
                    </tr>
                    <tr>
                        <td width="40%">Total students</td>
                        <td>{{$teacher->teacher_students()->count()}}</td>
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
@foreach($grades_info as $grade)
    <div class="page">
        <div class="subpage-w" style="padding-bottom: 0;padding-top: 0; ">
                <br />
                <div class="row">
                    <div class="col-xs-12">
                        <h3 class="text-center h3" style="font-weight: bold; margin: 0">Grade {{$grade['grade']}} / Year {{$grade['grade'] + 1}}</h3>
                    </div>
                    <div class="col-xs-12">
                        <div id="grade_tracker_{{$grade['grade']}}"
                             style="height: 380px"></div>
                    </div>
                    <hr />
                    <div class="col-md-12">
                        <div id="students_tasks_{{ $grade['grade'] }}"
                             style="height: 270px"></div>
                    </div>
                    <div class="col-md-12">
                        <div id="students_tests_{{ $grade['grade'] }}"
                             style="height: 270px"></div>
                    </div>

                </div>
        </div>
    </div>
@endforeach


<script src="{{ asset('assets/vendors/general/jquery/dist/jquery.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
<!--begin:: Global Optional Vendors -->
<!--begin::Global Theme Bundle(used by all pages) -->
<script src="{{ asset('assets/js/demo2/scripts.bundle.js') }}" type="text/javascript"></script>
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
    @foreach($grades_info as $grade)
            Highcharts.setOptions({
                colors: ['#f0f303', '#00a5ee', '#ec4102', '#47e51c',  '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4']
            });
                Highcharts.chart("grade_tracker_{{ $grade['grade'] }}", {
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
                        ['Learn', {{ $grade['learnings'] }}],
                        ['Practise', {{ $grade['trainings'] }}],
                        ['Assess your self', {{ $grade['tests'] }}],
                        ['Play', {{ $grade['games'] }}],
                    ]
                }]
            });

    var students_tasks = Highcharts.chart('students_tasks_{{ $grade['grade'] }}', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 0,
            plotShadow: false
        },
        title: {
            text: 'Students Tasks',
            align: 'center',
            verticalAlign: 'middle',
            y: 65
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: false,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    distance: -25,
                    format: '<br>{point.percentage:.1f} %',
                    style: {
                        font: 'bold 11px "Trebuchet MS", Verdana, sans-serif',
                        color: "#000",
                    }
                },
                showInLegend: true,
                startAngle: -90,
                endAngle: 90,
                center: ['50%', '75%'],
                size: '110%'
            },
            showInLegend: true
        },
        series: [{
            type: 'pie',
            name: 'Rate',
            innerSize: '50%',
            data: [
                {name: 'Completed tasks', y:{{ $grade['total_tasks'] > 0 ? round(($grade['completed_tasks']/$grade['total_tasks']) * 100,2):0 }}, color: '#47e51c'},
                {name: 'Returned tasks', y:{{ $grade['total_tasks'] > 0 ? round(($grade['returned_tasks']/$grade['total_tasks']) * 100,2):0 }}, color: '#00a5ee'},
                {name: 'Pending teaks', y:{{ $grade['total_tasks'] > 0 ? round(($grade['pending_tasks']/$grade['total_tasks']) * 100,2):0 }}, color: '#ec4102'},

            ]
        }]
    });

    var students_tasks = Highcharts.chart('students_tests_{{ $grade['grade'] }}', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 0,
            plotShadow: false
        },
        title: {
            text: 'Students assessments',
            align: 'center',
            verticalAlign: 'middle',
            y: 65
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: false,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    distance: -25,
                    format: '<br>{point.percentage:.1f} %',
                    style: {
                        font: 'bold 11px "Trebuchet MS", Verdana, sans-serif',
                        color: "#000",

                    }
                },
                showInLegend: true,
                startAngle: -90,
                endAngle: 90,
                center: ['50%', '75%'],
                size: '110%'
            },
            showInLegend: true
        },


        series: [{
            type: 'pie',
            name: 'Rate',
            innerSize: '50%',
            data: [
                {name: 'Passed tests', y:{{ $grade['total_tests'] > 0 ? round(($grade['passed_tests']/$grade['total_tests']) * 100,2):0 }}, color: '#47e51c'},
                {name: 'Failed tests', y:{{ $grade['total_tests'] > 0 ? round(($grade['failed_tests']/$grade['total_tests']) * 100,2):0 }}, color: '#ec4102'},
            ]
        }]
    });

    @endforeach


</script>
</body>
