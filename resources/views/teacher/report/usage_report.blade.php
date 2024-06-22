{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Usage Report</title>
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
        .subpage-w {
            border: 8px #ffd500 double;
        }

        tspan {
            font-weight: bold;
        }

        .highcharts-plot-line-label-s {
            font-weight: bold !important;
        }

        .table thead tr td, .table tbody tr td {
            font-size: 16px !important;
            font-weight: bold !important;
            border: 2px solid #888888 !important;
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
                border: 2px solid #888888 !important;
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

</head>
<body>


<div class="page">
    <div class="subpage-w" style="padding-bottom: 0; border:none">
        <div class="row" style="margin-top:20px;">
            <div class="col-xs-10 col-xs-offset-1">
                <div class="text-center">
                    <img width="100%" src="{{ asset('web_assets/img/logo.svg') }}"/>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-xs-12">
                <h1 class="text-center F3C200" style="font-weight: bold; font-size: 90px; color:#f7bc38 ">Non-Arabs</h1>
                <h2 class="text-center F3C200" style="font-weight: bold; font-size: 40px; color:#f7bc38 ">Platform</h2>
            </div>

        </div>
        <br>
        <div class="row">
            <div class="col-xs-12">
                <h1 class="text-center m26C281" style="font-weight: bold; font-size: 60px; ">Usage Report</h1>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-xs-12">
                <h3 class="text-center" style="font-weight: bold; ">{{ \Carbon\Carbon::parse($start_date)->format('d M Y') }} to {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}</h3>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-xs-12">
                <h3 class="text-center" style="font-weight: bold; ">www.Non-Arabs.com</h3>
                <h3 class="text-center" style="font-weight: bold; ">Support@abt-assessments.com </h3>
                <h3 class="text-center" style="font-weight: bold; ">00971503842666</h3>
            </div>
        </div>
        <br>
        <br>
        <br>
        <br>
        <div class="row">
            <div class="col-xs-12">
                <img src="{{asset('web_assets/img/new_report_footer.png')}}" width="100%">
            </div>
        </div>

    </div>
</div>
<div class="page">
    <div class="subpage-w" style="padding-bottom: 0">
        <div class="row" style="margin-top:20px;">
            <div class="col-xs-8 col-xs-offset-2">
                <div class="text-center">
                    <img style="max-height: 300px" src="{{ $teacher->school->logo  }}"/>
                </div>
            </div>
        </div>
        <br/>
        <div class="row">
            <h1 class="text-center">Non-Arabs Platform</h1>
            <p><span style="font-size: 16px;font-weight: bold;text-decoration: underline">Non-Arabs</span> is an online
                platform for learning Arabic based on international standards for Arabic as an additional language.</p>
            <p><span style="font-size: 16px;font-weight: bold;text-decoration: underline">Non-Arabs</span> platform is a
                professional platform for learning Arabic for non-Arabic speakers based on years of learning Arabic.</p>
            <p><span style="font-size: 16px;font-weight: bold;text-decoration: underline">Non-Arabs</span> platform is
                the only platform that can make a difference and make huge progress quickly.</p>
        </div>
        <div class="row">
            <h4 style="font-weight: bold;text-decoration: underline">The platform contains:</h4>
            <ul style="font-size: 16px;">
                <li>Fun and attractive interactive lessons for students.</li>
                <li>The levels are based on the years of learning Arabic.</li>
                <li>Arabic Digital library.</li>
                <li>High-Quality content.</li>
                <li>Digital Dictionary.</li>
                <li>All the skills covered.</li>
                <li>Marking / Feedback can happen from the teacher.</li>
                <li>The students can respond to the teacher`s feedback.</li>
                <li>High-Quality reports showing the student`s progress.</li>
                <li>Can help the students at Home.</li>
                <li>Easy to use.</li>
                <li>Assignments and homework.</li>
            </ul>
        </div>

    </div>
</div>
<div class="page">
    <div class="subpage-w" style="padding-bottom: 0">
        <div class="row" style="margin-top:20px;">
            <div class="col-xs-12">
                <h1 class="text-center text-red" style="font-weight: bold; font-size: 40px">Usage report - Teacher
                    overall</h1>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-xs-12 text-center">
                <table class="table table-bordered text-center">
                    <tbody>
                    <tr>
                        <td width="40%" class="back-m">School Name</td>
                        <td>{{$teacher->school->name}}</td>
                    </tr>
                    <tr>
                        <td width="40%" class="back-m">Teacher Name</td>
                        <td>{{$teacher->name}}</td>
                    </tr>
                    <tr>
                        <td width="40%" class="back-m">Total Student</td>
                        <td>{{$data['total_students']}}</td>
                    </tr>
                    <tr>
                        <td width="40%" class="back-m">High student performance</td>
                        <td>{{$data['top_student'] ? $data['top_student']->name .' - '. $data['top_student']->grade_name .' - '. $data['top_student']->section:null}}</td>
                    </tr>
                    <tr>
                        <td width="40%" class="back-m">Submitted assessments</td>
                        <td>{{$data['total_tests']}}</td>
                    </tr>
                    <tr>
                        <td width="40%" class="back-m">Passed assessments</td>
                        <td>{{$data['total_pass_tests']}}</td>
                    </tr>
                    <tr>
                        <td width="40%" class="back-m">Failed assessments</td>
                        <td>{{$data['total_fail_tests']}}</td>
                    </tr>
                    <tr>
                        <td width="40%" class="back-m">Submitted assignments</td>
                        <td>{{$data['total_assignments']}}</td>
                    </tr>
                    <tr>
                        <td width="40%" class="back-m">Marked assignments</td>
                        <td>{{$data['total_corrected_assignments']}}</td>
                    </tr>
                    <tr>
                        <td width="40%" class="back-m">Unmarked assignments</td>
                        <td>{{$data['total_uncorrected_assignments']}}</td>
                    </tr>
                    <tr>
                        <td width="40%" class="back-m">Selected Sections</td>
                        <td>
                            @foreach($sections as $section)
                                @if($section != '' or !is_null($section))
                                    {{$section}}
                                    @if(!$loop->last)
                                        ,
                                    @endif
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-12">
                <div id="general_tracker"
                     style="height: 330px"></div>
            </div>
        </div>
        <br/>

    </div>
</div>



@foreach($grades_data as $key => $grade_data)
    @if($key >= 1 && $key <= 15)
    <div class="page">
        <div class="subpage-w" style="padding-bottom: 0;padding-top: 0; ">
            <br/>
            <div class="row">
                @if($key != 15 && is_int($key))
                    <div class="col-xs-12">
                        <h3 class="text-center h3" style="font-weight: bold; margin: 0">Grade {{$key}} / Year {{$key + 1}}</h3>
                    </div>
                @elseif($key == 15)
                    <div class="col-xs-12">
                        <h3 class="text-center h3" style="font-weight: bold; margin: 0">Grade KG / Year 1</h3>
                    </div>
                @endif
            </div>
            <br/>
            <div class="row">
                <div class="col-xs-12 text-center">
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td width="40%" class="back-m">Total Student</td>
                            <td>{{$grade_data['total_students']}}</td>
                        </tr>
                        <tr>
                            <td width="40%" class="back-m">High student performance</td>
                            <td>{{$grade_data['top_student'] ? $grade_data['top_student']->name .' - '. $grade_data['top_student']->grade_name .' - '. $grade_data['top_student']->section:null}}</td>
                        </tr>
                        <tr>
                            <td width="40%" class="back-m">Submitted assessments</td>
                            <td>{{$grade_data['total_tests']}}</td>
                        </tr>
                        <tr>
                            <td width="40%" class="back-m">Passed assessments</td>
                            <td>{{$grade_data['total_pass_tests']}}</td>
                        </tr>
                        <tr>
                            <td width="40%" class="back-m">Failed assessments</td>
                            <td>{{$grade_data['total_fail_tests']}}</td>
                        </tr>
                        <tr>
                            <td width="40%" class="back-m">Submitted assignments</td>
                            <td>{{$grade_data['total_assignments']}}</td>
                        </tr>
                        <tr>
                            <td width="40%" class="back-m">Marked assignments</td>
                            <td>{{$grade_data['total_corrected_assignments']}}</td>
                        </tr>
                        <tr>
                            <td width="40%" class="back-m">Unmarked assignments</td>
                            <td>{{$grade_data['total_uncorrected_assignments']}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-12">
                    <div id="grade_tracker_{{$key}}"
                         style="height: 330px"></div>
                </div>
            </div>
        </div>
    </div>
    @endif
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
    Highcharts.setOptions({
        colors: ['#ec4102', '#47e51c', '#24CBE5', '#FFF263', '#FF9655', '#FFF263', '#6AF9C4']
    });


    Highcharts.chart("general_tracker", {
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
            align: 'center',
            itemStyle: {
                fontSize: '14px',
                color: '#000',
                align: 'center',
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
                ['Learn', {{ $data['learn'] }}],
                ['Practise', {{ $data['practise'] }}],
                ['Assess your self', {{ $data['test'] }}],
                ['Play', {{ $data['play'] }}],
            ]
        }]
    });


    @foreach($grades_data as $key => $grade_data)
    Highcharts.chart("grade_tracker_{{$key}}", {
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
            align: 'center',
            itemStyle: {
                fontSize: '14px',
                color: '#000',
                align: 'center',
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
                ['Learn', {{ $grade_data['learn'] }}],
                ['Practise', {{ $grade_data['practise'] }}],
                ['Assess your self', {{ $grade_data['test'] }}],
                ['Play', {{ $grade_data['play'] }}],
            ]
        }]
    });

    @endforeach


</script>
</body>
