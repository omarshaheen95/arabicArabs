{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('school.layout.container')
@section('style')
    <link href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}"
          rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <!--begin:: Widgets/Applications/User/Profile3-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__body">
                    <div class="kt-widget kt-widget--user-profile-3">
                        <div class="kt-widget__top">

                            {{--                            User info--}}
                            @if($user->image)
                            <div class="kt-widget__media kt-hidden-">
                                <img src="{{ $user->image }}" alt="image">
                            </div>
                            @endif
                            <div class="kt-widget__content">
                                <div class="kt-widget__head">
                                    <label href="#" class="kt-widget__username">
                                        {{t('Student')}} : {{ $user->name }}

                                    </label>

                                </div>
                                <div class="kt-widget__subhead">
                                    <a href="mailTo:{{ $user->email }}"><i
                                            class="flaticon2-new-email"></i> {{ $user->email }}</a>
                                    <br/>
                                    <a href="#"><i class="flaticon2-phone"></i> {{ $user->mobile }}</a>
                                </div>
                                <div class="kt-widget__subhead">
                                    <a href="#"> <i
                                            class="flaticon2-calendar-7"></i> {{ t('member since : ') .' '. $user->created_at->format('d-m-Y') }}
                                    </a>
                                </div>
                            </div>


                            @if($teacher)
                                {{--                            Teacher info--}}
                            @if($teacher->image)
                                <div class="kt-widget__media kt-hidden-">
                                    <img src="{{ $teacher->image }}" alt="image">
                                </div>
                                @endif
                                <div class="kt-widget__content">
                                    <div class="kt-widget__head">
                                        <label href="#" class="kt-widget__username">
                                            {{t('Teacher')}} : {{ $teacher->name }}
                                            @if($teacher->active)
                                                <i class="flaticon2-correct"></i>
                                            @endif
                                        </label>

                                    </div>
                                    <div class="kt-widget__subhead">
                                        <a href="mailTo:{{ $teacher->email }}"><i
                                                class="flaticon2-new-email"></i> {{ $teacher->email }}</a>
                                        <br/>
                                        <a href="#"><i class="flaticon2-phone"></i> {{ $teacher->mobile }}</a>
                                    </div>
                                    <div class="kt-widget__subhead">
                                        <a href="#"> <i
                                                class="flaticon2-calendar-7"></i> {{ t('member since : ') .' '. $teacher->created_at->format('d-m-Y') }}
                                        </a>
                                    </div>
                                </div>
                            @endif

                        </div>
                        <div class="kt-widget__bottom">

                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-interface-6"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">{{ t('Grade') }}</span>
                                    <span class="kt-widget__value">Grade {{$user->grade}}</span>
                                </div>
                            </div>

                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-layers"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">{{ t('Package') }}</span>
                                    <span
                                        class="kt-widget__value">{{optional($user->pakage)->name ?? t('not subscribed')}}</span>
                                </div>
                            </div>

                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-calendar-2"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">{{ t('Active to') }}</span>
                                    <span
                                        class="kt-widget__value">{{optional($user->active_to)->format('Y-m-d')}}</span>
                                </div>
                            </div>

                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-signs-2"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">{{ t('Tests') }}</span>
                                    <span class="kt-widget__value">{{$tests}} {{t('tests')}}</span>
                                </div>
                            </div>

                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-list-3"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">{{ t('Passed test') }}</span>
                                    <span class="kt-widget__value">{{$passed_tests}} {{t('tests')}}</span>
                                </div>
                            </div>

                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-logout"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">{{ t('Last login') }}</span>
                                    <span class="kt-widget__value">{{optional($user->last_login)->format('Y-m-d H:i')}} - {{optional($user->last_login)->diffForHumans()}}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Applications/User/Profile3-->
        </div>
    </div>
    <div class="row">
        <div class="kt-portlet">
            <div class="kt-portlet__body">
                <div class="kt-section kt-section--first row">
                    <div class="col-xl-12">

                        <div class="kt-section__body">
                            <form action="">
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label>{{t('Select date')}}:</label>
                                        <div class="input-daterange input-group" id="kt_datepicker_5">
                                            <input type="text" class="form-control date" value="{{$start_date}}"
                                                   name="start_date" placeholder="{{t('Start date')}}"/>
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                            </div>
                                            <input type="text" class="form-control date" value="{{$end_date}}"
                                                   name="end_date" placeholder="{{t('End date')}}"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="">Grade:</label>
                                        <select name="grade" class="form-control">
                                            @foreach($grades as $grade)
                                                @if($grades == 15)
                                                    <option
                                                        value="{{$grade}}" {{request()->get('grade', false) == $grade ? 'selected':''}}>
                                                        KG 2</option>
                                                    @else
                                                <option
                                                    value="{{$grade}}" {{request()->get('grade', false) == $grade ? 'selected':''}}>
                                                    Grade {{$grade}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="">{{t('Actions')}}:</label>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-danger"><i
                                                    class="la la-search"></i> {{t('Show result')}}</button>
                                            <a href="{{route('school.user.report',[$user->id])}}" class="btn btn-danger"><i
                                                    class="la la-bar-chart"></i> {{t('Report')}}</a>
                                        </div>
                                    </div>
                                    <hr/>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="kt-list-timeline">
                            <div class="kt-list-timeline__items">
                                @foreach($tracks as $track)
                                    <div class="kt-list-timeline__item">
                                        <span
                                            class="kt-list-timeline__badge kt-list-timeline__badge--{{$track->color}}"></span>
                                        <span class="kt-list-timeline__text">{!! $track->type_text !!}</span>
                                        <span
                                            class="kt-list-timeline__time">{{$track->created_at->format('Y-m-d H:i')}}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div id="container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"
            type="text/javascript"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        $(document).ready(function () {
            $('.date').datepicker({
                autoclose: true,
                rtl: true,
                todayHighlight: true,
                orientation: "bottom left",
                format: 'yyyy-mm-dd'
            });
            // Radialize the colors
            Highcharts.setOptions({
                colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
                    return {
                        radialGradient: {
                            cx: 0.5,
                            cy: 0.3,
                            r: 0.7
                        },
                        stops: [
                            [0, color],
                            [1, Highcharts.color(color).brighten(-0.3).get('rgb')] // darken
                        ]
                    };
                })
            });
            // Build the chart
            Highcharts.chart('container', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: '{{t("The percentage of student progress during to ", ['startDate' => $start_date, 'endDate' => $end_date])}}'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<br>{point.percentage:.1f} %',
                            distance: -50,
                            filter: {
                                property: 'percentage',
                                operator: '>',
                                value: 4
                            }
                        },
                        showInLegend: true
                    }
                },
                series: [{
                    name: 'Share',
                    innerSize: '30%',
                    data: [
                        { name: '{{t('learn')}}', y: {{$data['learn_avg']}}, color: "#ffb822" },
                        { name: '{{t('practise')}}', y: {{$data['practise_avg']}}, color: "#000000" },
                        { name: '{{t('test')}}', y: {{$data['test_avg']}}, color: "#3B47CB" },
                        { name: '{{t('play')}}', y: {{$data['play_avg']}}, color: "#0abb87" },
                    ]
                }]
            });

        });
    </script>
@endsection
