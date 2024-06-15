@extends('manager.layout.container')
@section('style')
    <style>
        #chartdiv1, #chartdiv2, #chartdiv3, #chartdiv0 {
            width: 100%;
            height: 400px;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-2">
            <div class="kt-portlet">
                <div class="kt-portlet__body">
                    <!--begin::Form-->
                    <div class="kt-form">
                        <div class="row">
                            <div class="col-4 justify-content-center text-center">
                                <i class="flaticon2-architecture-and-city" style="font-size: 3.5rem"></i>
                            </div>
                            <div class="col-2">
                            </div>
                            <label class="col-6 text-center">
                                <span style="font-size: 2rem">
                                    {{ $schools }}
                                </span>
                                <br>
                                المدارس
                            </label>

                        </div>
                    </div>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="kt-portlet">
                <div class="kt-portlet__body">
                    <!--begin::Form-->
                    <div class="kt-form">
                        <div class="row">
                            <div class="col-4 justify-content-center text-center">
                                <i class="flaticon2-user-outline-symbol" style="font-size: 3.5rem"></i>
                            </div>
                            <div class="col-2">
                            </div>
                            <label class="col-6 text-center">
                                <span style="font-size: 2rem">
                                    {{ $supervisors }}
                                </span>
                                <br>
                                المشرفون
                            </label>

                        </div>
                    </div>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="kt-portlet">
                <div class="kt-portlet__body">
                    <!--begin::Form-->
                    <div class="kt-form">
                        <div class="row">
                            <div class="col-4 justify-content-center text-center">
                                <i class="flaticon2-user-outline-symbol" style="font-size: 3.5rem"></i>
                            </div>
                            <div class="col-2">
                            </div>
                            <label class="col-6 text-center">
                                <span style="font-size: 2rem">
                                    {{ $teachers }}
                                </span>
                                <br>
                                المعلمون
                            </label>

                        </div>
                    </div>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="kt-portlet">
                <div class="kt-portlet__body">
                    <!--begin::Form-->
                    <div class="kt-form">
                        <div class="row">
                            <div class="col-4 justify-content-center text-center">
                                <i class="flaticon2-group" style="font-size: 3.5rem"></i>
                            </div>
                            <div class="col-2">
                            </div>
                            <label class="col-6 text-center">
                                <span style="font-size: 2rem">
                                    {{ $students }}
                                </span>
                                <br>
                                الطلاب
                            </label>

                        </div>
                    </div>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="kt-portlet">
                <div class="kt-portlet__body">
                    <!--begin::Form-->
                    <div class="kt-form">
                        <div class="row">
                            <div class="col-4 justify-content-center text-center">
                                <i class="flaticon2-writing" style="font-size: 3.5rem"></i>
                            </div>
                            <div class="col-2">
                            </div>
                            <label class="col-6 text-center">
                                <span style="font-size: 2rem">
                                    {{ $tests }}
                                </span>
                                <br>
                                اختبارات مكتملة
                            </label>

                        </div>
                    </div>
                    <!--end::Form-->
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="kt-portlet">
                <div class="kt-portlet__body">
                    <!--begin::Form-->
                    <div class="kt-form">
                        <div class="row">
                            <div class="col-4 justify-content-center text-center">
                                <i class="flaticon2-open-text-book " style="font-size: 3.5rem"></i>
                            </div>
                            <div class="col-2">
                            </div>
                            <label class="col-6 text-center">
                                <span style="font-size: 2rem">
                                    {{ $lessons}}
                                </span>
                                <br>
                                الدروس
                            </label>

                        </div>
                    </div>
                    <!--end::Form-->
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            إجمالي الاختبارات خلال الشهر الحالي
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                    </div>
                </div>
                <div class="kt-portlet__body kt-portlet__body--fluid">
                    <div id="chartdiv0" style="direction: ltr">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            إجمالي الطلاب الجدد خلال الشهر الحالي
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                    </div>
                </div>
                <div class="kt-portlet__body kt-portlet__body--fluid">
                    <div id="chartdiv1" style="direction: ltr">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('core_ar.js')}}"></script>
    <script src="https://www.amcharts.com/lib/4/charts.js"></script>
    <script src="https://www.amcharts.com/lib/4/themes/material.js"></script>
    <script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
    <script src="https://www.amcharts.com/lib/4/lang/ar.js"></script>

    <script>
        am4core.ready(function () {

// Themes begin
            am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
            var chart = am4core.create("chartdiv0", am4charts.XYChart);
            @if(isRtlJS())
                chart.rtl = true;
            @endif
            // Add data
            chart.data = [
                    @foreach($tests_date as $test)
                {
                    "date": "{{ $test->date }}",
                    "value": {{ $test->counts }}
                },
                @endforeach
            ];

// Set input format for the dates
            chart.dateFormatter.inputDateFormat = "yyyy-MM-dd";

// Create axes
            var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

// Create series
            var series = chart.series.push(new am4charts.LineSeries());
            series.dataFields.valueY = "value";
            series.dataFields.dateX = "date";
            series.tooltipText = "{value}"
            series.strokeWidth = 2;
            series.minBulletDistance = 15;

// Drop-shaped tooltips
            series.tooltip.background.cornerRadius = 20;
            series.tooltip.background.strokeOpacity = 0;
            series.tooltip.pointerOrientation = "vertical";
            series.tooltip.label.minWidth = 40;
            series.tooltip.label.minHeight = 40;
            series.tooltip.label.rtl = true;
            series.tooltip.label.textAlign = "middle";
            series.tooltip.label.textValign = "middle";
//
// // Make bullets grow on hover
            var bullet = series.bullets.push(new am4charts.CircleBullet());
            bullet.circle.strokeWidth = 2;
            bullet.circle.radius = 4;
            bullet.circle.fill = am4core.color("#fff");

            var bullethover = bullet.states.create("hover");
            bullethover.properties.scale = 1.3;

// // Make a panning cursor
            chart.cursor = new am4charts.XYCursor();
            chart.cursor.rtl = true;
            chart.cursor.behavior = "panXY";
            chart.cursor.xAxis = dateAxis;
            chart.cursor.snapToSeries = series;
            chart.cursor.label.minWidth = 40;
            chart.cursor.label.minHeight = 40;
            chart.cursor.label.textAlign = "middle";
            chart.cursor.label.textValign = "middle";

// // Create vertical scrollbar and place it before the value axis
//             chart.scrollbarY = new am4core.Scrollbar();
//             chart.scrollbarY.parent = chart.leftAxesContainer;
//             chart.scrollbarY.toBack();
//
// // Create a horizontal scrollbar with previe and place it underneath the date axis
//             chart.scrollbarX = new am4charts.XYChartScrollbar();
//             chart.scrollbarX.series.push(series);
//             chart.scrollbarX.parent = chart.bottomAxesContainer;

            chart.events.on("ready", function () {
                //dateAxis.zoom({start:0.79, end:1});
            });

        }); // end am4core.ready()
        am4core.ready(function () {

// Themes begin
            am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
            var chart = am4core.create("chartdiv1", am4charts.XYChart);
            @if(isRtlJS())
            // chart.rtl = true;
            @endif
            // Add data
            chart.data = [
                    @foreach($users_date as $user)
                {
                    "date": "{{ $user->date }}",
                    "value": {{ $user->counts }}
                },
                @endforeach
            ];

// Set input format for the dates
            chart.dateFormatter.inputDateFormat = "yyyy-MM-dd";

// Create axes
            var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

// Create series
            var series = chart.series.push(new am4charts.LineSeries());
            series.dataFields.valueY = "value";
            series.dataFields.dateX = "date";
            series.tooltipText = "{value}"
            series.strokeWidth = 2;
            series.minBulletDistance = 15;

// Drop-shaped tooltips
            series.tooltip.background.cornerRadius = 20;
            series.tooltip.background.strokeOpacity = 0;
            series.tooltip.pointerOrientation = "vertical";
            series.tooltip.label.minWidth = 40;
            series.tooltip.label.minHeight = 40;
            series.tooltip.label.textAlign = "middle";
            series.tooltip.label.textValign = "middle";

// Make bullets grow on hover
            var bullet = series.bullets.push(new am4charts.CircleBullet());
            bullet.circle.strokeWidth = 2;
            bullet.circle.radius = 4;
            bullet.circle.fill = am4core.color("#fff");

            var bullethover = bullet.states.create("hover");
            bullethover.properties.scale = 1.3;

// Make a panning cursor
            chart.cursor = new am4charts.XYCursor();
            chart.cursor.behavior = "panXY";
            chart.cursor.xAxis = dateAxis;
            chart.cursor.snapToSeries = series;

// // Create vertical scrollbar and place it before the value axis
//             chart.scrollbarY = new am4core.Scrollbar();
//             chart.scrollbarY.parent = chart.leftAxesContainer;
//             chart.scrollbarY.toBack();
//
// // Create a horizontal scrollbar with previe and place it underneath the date axis
//             chart.scrollbarX = new am4charts.XYChartScrollbar();
//             chart.scrollbarX.series.push(series);
//             chart.scrollbarX.parent = chart.bottomAxesContainer;

            chart.events.on("ready", function () {
                //dateAxis.zoom({start:0.79, end:1});
            });

        }); // end am4core.ready()

    </script>
@endsection
