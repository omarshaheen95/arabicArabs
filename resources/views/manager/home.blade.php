@extends('manager.layout.container')
@section('title',$title)
@can('show statistics')
    @section('charts')
        <div class="row gy-5 g-xl-10 justify-content-center">
            <div class="col-sm-6 col-xl-2 mb-xl-10">
                <div class="card h-lg-100">
                    <!--begin::Body-->
                    <div class="card-body d-flex justify-content-between align-items-start flex-column">
                        <!--begin::Icon-->
                        <div class="m-0">
                            <i class="ki-duotone ki-home-3 fs-2hx text-gray-600">
                                <i class="path1"></i>
                                <i class="path2"></i>
                            </i>
                        </div>
                        <!--end::Icon-->
                        <!--begin::Section-->
                        <div class="d-flex flex-column mt-5">
                            <!--begin::Number-->
                            <span class="fw-semibold fs-2x text-gray-800 lh-1 ls-n2">{{$data['schools']}} </span>
                            <!--end::Number-->

                            <!--begin::Follower-->
                            <div class="m-0">
                                <span class="fw-semibold fs-6 text-gray-400">{{t('Schools')}}</span>
                            </div>
                            <!--end::Follower-->
                        </div>
                        <!--end::Section-->
                    </div>
                    <!--end::Body-->
                </div>
            </div>
            <div class="col-sm-6 col-xl-2 mb-xl-10">
                <div class="card h-lg-100">
                    <!--begin::Body-->
                    <div class="card-body d-flex justify-content-between align-items-start flex-column">
                        <!--begin::Icon-->
                        <div class="m-0">
                            <i class="ki-duotone ki-people fs-2hx text-gray-600">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </div>
                        <!--end::Icon-->
                        <!--begin::Section-->
                        <div class="d-flex flex-column mt-5">
                            <!--begin::Number-->
                            <span class="fw-semibold fs-2x text-gray-800 lh-1 ls-n2">{{$data['supervisors']}} </span>
                            <!--end::Number-->

                            <!--begin::Follower-->
                            <div class="m-0">
                                <span class="fw-semibold fs-6 text-gray-400">{{t('Supervisors')}}</span>
                            </div>
                            <!--end::Follower-->
                        </div>
                        <!--end::Section-->
                    </div>
                    <!--end::Body-->
                </div>
            </div>
            <div class="col-sm-6 col-xl-2 mb-xl-10">
                <div class="card h-lg-100">
                    <!--begin::Body-->
                    <div class="card-body d-flex justify-content-between align-items-start flex-column">
                        <!--begin::Icon-->
                        <div class="m-0">
                            <i class="ki-duotone ki-user-tick fs-2hx text-gray-600">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </div>
                        <!--end::Icon-->
                        <!--begin::Section-->
                        <div class="d-flex flex-column mt-5">
                            <!--begin::Number-->
                            <span class="fw-semibold fs-2x text-gray-800 lh-1 ls-n2">{{$data['teachers']}} </span>
                            <!--end::Number-->

                            <!--begin::Follower-->
                            <div class="m-0">
                                <span class="fw-semibold fs-6 text-gray-400">{{t('Teachers')}}</span>
                            </div>
                            <!--end::Follower-->
                        </div>
                        <!--end::Section-->
                    </div>
                    <!--end::Body-->
                </div>
            </div>
            <div class="col-sm-6 col-xl-2 mb-xl-10">
                <div class="card h-lg-100">
                    <!--begin::Body-->
                    <div class="card-body d-flex justify-content-between align-items-start flex-column">
                        <!--begin::Icon-->
                        <div class="m-0">
                            <i class="ki-duotone ki-user fs-2hx text-gray-600">
                                <i class="path1"></i>
                                <i class="path2"></i>
                            </i>
                        </div>
                        <!--end::Icon-->
                        <!--begin::Section-->
                        <div class="d-flex flex-column mt-5">
                            <!--begin::Number-->
                            <span class="fw-semibold fs-2x text-gray-800 lh-1 ls-n2">{{$data['students']}} </span>
                            <!--end::Number-->

                            <!--begin::Follower-->
                            <div class="m-0">
                                <span class="fw-semibold fs-6 text-gray-400">{{t('Students')}}</span>
                            </div>
                            <!--end::Follower-->
                        </div>
                        <!--end::Section-->
                    </div>
                    <!--end::Body-->
                </div>
            </div>

            <div class="col-sm-6 col-xl-2 mb-xl-10">
                <div class="card h-lg-100">
                    <!--begin::Body-->
                    <div class="card-body d-flex justify-content-between align-items-start flex-column">
                        <!--begin::Icon-->
                        <div class="m-0">
                            <i class="ki-duotone ki-clipboard fs-2hx text-gray-600">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </div>
                        <!--end::Icon-->
                        <!--begin::Section-->
                        <div class="d-flex flex-column mt-5">
                            <!--begin::Number-->
                            <span class="fw-semibold fs-2x text-gray-800 lh-1 ls-n2">{{$data['lessons']}} </span>
                            <!--end::Number-->

                            <!--begin::Follower-->
                            <div class="m-0">
                                <span class="fw-semibold fs-6 text-gray-400">{{t('Lessons')}}</span>
                            </div>
                            <!--end::Follower-->
                        </div>
                        <!--end::Section-->
                    </div>
                    <!--end::Body-->
                </div>
            </div>
            <div class="col-sm-6 col-xl-2 mb-xl-10">
                <div class="card h-lg-100">
                    <!--begin::Body-->
                    <div class="card-body d-flex justify-content-between align-items-start flex-column">
                        <!--begin::Icon-->
                        <div class="m-0">

                            <i class="ki-duotone ki-book-open  fs-2hx text-gray-600">
                                <i class="path1"></i>
                                <i class="path2"></i>
                                <i class="path3"></i>
                                <i class="path4"></i>
                            </i>
                        </div>
                        <!--end::Icon-->
                        <!--begin::Section-->
                        <div class="d-flex flex-column mt-5">
                            <!--begin::Number-->
                            <span class="fw-semibold fs-2x text-gray-800 lh-1 ls-n2">{{$data['stories']}} </span>
                            <!--end::Number-->

                            <!--begin::Follower-->
                            <div class="m-0">
                                <span class="fw-semibold fs-6 text-gray-400">{{t('Stories')}}</span>
                            </div>
                            <!--end::Follower-->
                        </div>
                        <!--end::Section-->
                    </div>
                    <!--end::Body-->
                </div>
            </div>
        </div>
        <div class="row gy-5 g-xl-10 justify-content-center">
            <div class="col-xl-6">
                <!--begin::Chart widget 38-->
                <div class="card card-flush  mb-xl-10">
                    <!--begin::Header-->
                    <div class="card-header pt-7">
                        <!--begin::Title-->
                        <h5 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800" style="font-size: 1rem">{{t('Completed Lessons Tests')}} <span
                                    id="LessonsTests_total"
                                    class="fs-6 text-danger">{{$LessonsTests_data['total']}}</span></span>
                        </h5>
                        <!--end::Title-->
                        <!--begin::Toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
                            <div class=" mb-5 input-group input-group-solid">
                                <div class="position-relative d-flex align-items-center">
                                    <!--begin::Datepicker-->
                                    <input id="LessonsTestsPicker" class="form-control form-control-solid ps-12 fs-8"
                                           placeholder="Select a date" name="due_date" type="text" readonly="readonly">
                                    <input type="hidden" name="start_LessonsTestsPicker" id="start_LessonsTestsPicker"
                                           value="{{date('Y-m-d')}}"/>
                                    <input type="hidden" name="end_LessonsTestsPicker" id="end_LessonsTestsPicker"
                                           value="{{date('Y-m-d')}}"/>
                                    <!--end::Datepicker-->
                                    <!--begin::Icon-->
                                    <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                        <span class="path6"></span>
                                    </i>
                                    <!--end::Icon-->
                                </div>
                            </div>
                            <!--end::Daterangepicker-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body d-flex align-items-end px-0 pt-3 pb-5">
                        <!--begin::Chart-->
                        <div id="LessonsTests_chart" class="h-325px w-100 min-h-auto ps-4 pe-6"></div>
                        <!--end::Chart-->
                    </div>
                    <!--end: Card Body-->
                </div>
                <!--end::Chart widget 38-->
            </div>
            <div class="col-xl-6">
                <!--begin::Chart widget 38-->
                <div class="card card-flush  mb-xl-10">
                    <!--begin::Header-->
                    <div class="card-header pt-7">
                        <!--begin::Title-->
                        <h5 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800" style="font-size: 1rem">{{t('Completed Lessons Assignments')}} <span
                                    id="LessonsAssignments_total"
                                    class="fs-6 text-danger">{{$LessonsAssignments_data['total']}}</span></span>
                        </h5>
                        <!--end::Title-->
                        <!--begin::Toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
                            <div class=" mb-5 input-group input-group-solid">
                                <div class="position-relative d-flex align-items-center">
                                    <!--begin::Datepicker-->
                                    <input id="LessonsAssignmentsPicker" class="form-control form-control-solid ps-12 fs-8"
                                           placeholder="Select a date" name="due_date" type="text" readonly="readonly">
                                    <input type="hidden" name="start_LessonsAssignmentsPicker" id="start_LessonsAssignmentsPicker"
                                           value="{{date('Y-m-d')}}"/>
                                    <input type="hidden" name="end_LessonsAssignmentsPicker" id="end_LessonsAssignmentsPicker"
                                           value="{{date('Y-m-d')}}"/>
                                    <!--end::Datepicker-->
                                    <!--begin::Icon-->
                                    <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                        <span class="path6"></span>
                                    </i>
                                    <!--end::Icon-->
                                </div>
                            </div>
                            <!--end::Daterangepicker-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body d-flex align-items-end px-0 pt-3 pb-5">
                        <!--begin::Chart-->
                        <div id="LessonsAssignments_chart" class="h-325px w-100 min-h-auto ps-4 pe-6"></div>
                        <!--end::Chart-->
                    </div>
                    <!--end: Card Body-->
                </div>
                <!--end::Chart widget 38-->
            </div>
            <div class="col-xl-6">
                <!--begin::Chart widget 38-->
                <div class="card card-flush  mb-xl-10">
                    <!--begin::Header-->
                    <div class="card-header pt-7">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800" style="font-size: 1rem">{{t('Completed Stories Tests')}} <span
                                    id="StoriesTests_total"
                                    class="fs-6 text-danger">{{$StoriesTests_data['total']}}</span></span>
                        </h3>
                        <!--end::Title-->
                        <!--begin::Toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
                            <div class=" mb-5 input-group input-group-solid">
                                <div class="position-relative d-flex align-items-center">
                                    <!--begin::Datepicker-->
                                    <input id="StoriesTestsPicker" class="form-control form-control-solid ps-12 fs-8"
                                           placeholder="Select a date" name="due_date" type="text" readonly="readonly">
                                    <input type="hidden" name="PickerStoriesTestsPicker" id="start_StoriesTestsPicker"
                                           value="{{date('Y-m-d')}}"/>
                                    <input type="hidden" name="end_StoriesTestsPicker" id="end_StoriesTestsPicker"
                                           value="{{date('Y-m-d')}}"/>
                                    <!--end::Datepicker-->
                                    <!--begin::Icon-->
                                    <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                        <span class="path6"></span>
                                    </i>
                                    <!--end::Icon-->
                                </div>
                            </div>
                            <!--end::Daterangepicker-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body d-flex align-items-end px-0 pt-3 pb-5">
                        <!--begin::Chart-->
                        <div id="StoriesTests_chart" class="h-325px w-100 min-h-auto ps-4 pe-6"></div>
                        <!--end::Chart-->
                    </div>
                    <!--end: Card Body-->
                </div>
                <!--end::Chart widget 38-->
            </div>
            <div class="col-xl-6">
                <!--begin::Chart widget 38-->
                <div class="card card-flush  mb-xl-10">
                    <!--begin::Header-->
                    <div class="card-header pt-7">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800" style="font-size: 1rem">{{t('Completed Stories Assignments')}} <span
                                    id="StoriesAssignments_total"
                                    class="fs-6 text-danger">{{$StoriesAssignments_data['total']}}</span></span>
                        </h3>
                        <!--end::Title-->
                        <!--begin::Toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
                            <div class=" mb-5 input-group input-group-solid">
                                <div class="position-relative d-flex align-items-center">
                                    <!--begin::Datepicker-->
                                    <input id="StoriesAssignmentsPicker" class="form-control form-control-solid ps-12 fs-8"
                                           placeholder="Select a date" name="due_date" type="text" readonly="readonly">
                                    <input type="hidden" name="start_StoriesAssignmentsPicker" id="start_StoriesAssignmentsPicker"
                                           value="{{date('Y-m-d')}}"/>
                                    <input type="hidden" name="end_StoriesAssignmentsPicker" id="end_StoriesAssignmentsPicker"
                                           value="{{date('Y-m-d')}}"/>
                                    <!--end::Datepicker-->
                                    <!--begin::Icon-->
                                    <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                        <span class="path6"></span>
                                    </i>
                                    <!--end::Icon-->
                                </div>
                            </div>
                            <!--end::Daterangepicker-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body d-flex align-items-end px-0 pt-3 pb-5">
                        <!--begin::Chart-->
                        <div id="StoriesAssignments_chart" class="h-325px w-100 min-h-auto ps-4 pe-6"></div>
                        <!--end::Chart-->
                    </div>
                    <!--end: Card Body-->
                </div>
                <!--end::Chart widget 38-->
            </div>
            <div class="col-xl-12">
                <!--begin::Chart widget 38-->
                <div class="card card-flush  mb-xl-10">
                    <!--begin::Header-->
                    <div class="card-header pt-7">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800" style="font-size: 1rem">{{t('Students Login Statistics')}} <span
                                    id="StoriesAssignments_total"
                                    class="fs-6 text-danger">{{$StudentsLogin_data['total']}}</span></span>
                        </h3>
                        <!--end::Title-->
                        <!--begin::Toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
                            <div class=" mb-5 input-group input-group-solid">
                                <div class="position-relative d-flex align-items-center">
                                    <!--begin::Datepicker-->
                                    <input id="StudentsLoginPicker" class="form-control form-control-solid ps-12 fs-8"
                                           placeholder="Select a date" name="due_date" type="text" readonly="readonly">
                                    <input type="hidden" name="start_StudentsLoginPicker" id="start_StudentsLoginPicker"
                                           value="{{date('Y-m-d')}}"/>
                                    <input type="hidden" name="end_StudentsLoginPicker" id="end_StudentsLoginPicker"
                                           value="{{date('Y-m-d')}}"/>
                                    <!--end::Datepicker-->
                                    <!--begin::Icon-->
                                    <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                        <span class="path6"></span>
                                    </i>
                                    <!--end::Icon-->
                                </div>
                            </div>
                            <!--end::Daterangepicker-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body d-flex align-items-end px-0 pt-3 pb-5">
                        <!--begin::Chart-->
                        <div id="StudentsLogin_chart" class="h-325px w-100 min-h-auto ps-4 pe-6"></div>
                        <!--end::Chart-->
                    </div>
                    <!--end: Card Body-->
                </div>
                <!--end::Chart widget 38-->
            </div>

        </div>

    @endsection
    @section('script')

        <script>
            @foreach(['LessonsTests', 'LessonsAssignments', 'StoriesAssignments', 'StoriesTests', 'StudentsLogin'] as $type)
            var {{$type}}Data = {
                'categories': {!! json_encode(${$type.'_data'}['categories']) !!},
                'data': {!! json_encode(${$type.'_data'}['data']) !!},
            };

            initializeDateRangePicker('{{$type}}Picker', [$('#start_{{$type}}Picker').val(), $('#end_{{$type}}Picker').val()]);

            $('#{{$type}}Picker').on('apply.daterangepicker', function (ev, picker) {
                chartStatisticsData("{{$type}}");
            });


            var {{$type}}DataChart = function () {
                var e = {self: null, rendered: !1}, t = function () {
                    var t = document.getElementById("{{$type}}_chart");
                    if (t) {
                        var a = parseInt(KTUtil.css(t, "height")), l = KTUtil.getCssVariableValue("--bs-gray-900"),
                            r = KTUtil.getCssVariableValue("--bs-border-dashed-color"), o = {
                                series: [{name: "", data: {{$type}}Data.data}],
                                chart: {fontFamily: "inherit", type: "bar", height: a, toolbar: {show: !1}},
                                plotOptions: {
                                    bar: {
                                        horizontal: !1,
                                        columnWidth: ["28%"],
                                        borderRadius: 5,
                                        dataLabels: {position: "top"},
                                        startingShape: "flat"
                                    }
                                },
                                legend: {show: !1},
                                dataLabels: {
                                    enabled: !0,
                                    offsetY: -28,
                                    style: {fontSize: "13px", colors: [l]},
                                    formatter: function (e) {
                                        return e
                                    }
                                },
                                stroke: {show: !0, width: 2, colors: ["transparent"]},
                                xaxis: {
                                    categories: {{$type}}Data.categories,
                                    axisBorder: {show: !1},
                                    axisTicks: {show: !1},
                                    labels: {
                                        style: {
                                            colors: KTUtil.getCssVariableValue("--bs-gray-500"),
                                            fontSize: "13px"
                                        }
                                    },
                                    crosshairs: {fill: {gradient: {opacityFrom: 0, opacityTo: 0}}}
                                },
                                yaxis: {
                                    labels: {
                                        style: {colors: KTUtil.getCssVariableValue("--bs-gray-500"), fontSize: "13px"},

                                    }
                                },
                                fill: {opacity: 1},
                                states: {
                                    normal: {filter: {type: "none", value: 0}},
                                    hover: {filter: {type: "none", value: 0}},
                                    active: {allowMultipleDataPointsSelection: !1, filter: {type: "none", value: 0}}
                                },
                                tooltip: {
                                    style: {fontSize: "12px"},
                                },
                                colors: [KTUtil.getCssVariableValue("--bs-primary"), KTUtil.getCssVariableValue("--bs-primary-light")],
                                grid: {borderColor: r, strokeDashArray: 4, yaxis: {lines: {show: !0}}}
                            };
                        e.self = new ApexCharts(t, o), setTimeout((function () {
                            e.self.render(), e.rendered = !0
                        }), 200)
                    }
                };
                return {
                    init: function () {
                        t(), KTThemeMode.on("kt.thememode.change", (function () {
                            e.rendered && e.self.destroy(), t()
                        }))
                    },
                    refetch: function () {
                        e.self.destroy();
                        t();
                    }
                }
            }();
            {{$type}}DataChart.init();
            @endforeach

            //send start and end date to controller to get data through ajax
            function chartStatisticsData(chartType) {
                var start_date = $('#start_' + chartType+'Picker').val();
                var end_date = $('#end_' + chartType+'Picker').val();
                console.log(start_date);
                console.log(end_date);
                $.ajax({
                    url: "{{ route('manager.statistics.chart_statistics_data') }}",
                    type: "POST",
                    data: {
                        start_date: start_date,
                        end_date: end_date,
                        model: chartType,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        $('#' +  chartType + '_total').text(data.data.total);
                        //access to variable name dynamically from chartType variable value and assign new data to it
                        window[chartType + 'Data'] = {
                            'categories': data.data.categories,
                            'data': data.data.data,
                        };
                        window[chartType + 'DataChart'].refetch();

                    }
                });
            }
        </script>
    @endsection
@endcan

