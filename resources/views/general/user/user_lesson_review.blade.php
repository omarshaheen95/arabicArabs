{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends($guard.'.layout.container')
@section('title')
    {{$title}}
@endsection
@section('pre-content')
    <div class="row">
        <div class="card mb-5 mb-xl-10">
            <div class="card-body pt-9 pb-0">
                <!--begin::Details-->
                <div class="d-flex flex-wrap flex-sm-nowrap">
                    <!--begin: Pic-->
                    <div class="me-7 mb-4">
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <img src="{{asset($user->image)}}" alt="image">
                            <div
                                class="position-absolute translate-middle bottom-0 start-100 mb-6 @if($user->active_to > now()) bg-success @else bg-danger @endif rounded-circle border border-4 border-body h-20px w-20px"></div>
                        </div>
                    </div>
                    <!--end::Pic-->

                    <!--begin::Info-->
                    <div class="flex-grow-1">
                        <!--begin::Title-->
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <!--begin::User-->
                            <div class="d-flex flex-column">
                                <!--begin::Name-->
                                <div class="d-flex align-items-center mb-2">
                                    <a href="#"
                                       class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{$user->name}}</a>
                                    @if($user->active_to > now())
                                        <a href="#"><i class="ki-duotone ki-verify fs-1 text-success"><span
                                                    class="path1"></span><span class="path2"></span></i></a>
                                    @else
                                        <a href="#"><i class="ki-duotone ki-verify fs-1 text-danger"><span
                                                    class="path1"></span><span class="path2"></span></i></a>
                                    @endif
                                </div>
                                <!--end::Name-->

                                <!--begin::Info-->
                                <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                    <a href="#"
                                       class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                        <span class="text-primary">{{t('Teacher')}} : </span>
                                        <label>{{optional($user->teacher)->name ?? t('Unsigned')}}</label>
                                    </a>
                                    <a href="#"
                                       class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                        <span class="text-primary">{{t('Grade')}} : </span>
                                        <label>{{$user->grade_name}}</label>
                                    </a>
                                    <a href="#"
                                       class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                        <span class="text-primary">{{t('Alternative Grade')}} : </span>
                                        <label>{{$user->alternateGrade ? $user->alternateGrade->name:'-'}}</label>
                                    </a>
                                </div>
                                <!--end::Info-->

                                <!--begin::Info-->
                                <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                    <a href="#"
                                       class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                        <span class="text-primary">{{t('Register Date')}} : </span>
                                        <label>{{$user->created_at}}</label>
                                    </a>
                                    <a href="#"
                                       class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                        <span class="text-primary">{{t('Active From')}} : </span>
                                        <label>{{optional($user->active_from)->format('Y-m-d')}}</label>
                                    </a>
                                    <a href="#"
                                       class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                        <span class="text-primary">{{t('Active To')}} : </span>
                                        <label>{{optional($user->active_to)->format('Y-m-d')}}</label>
                                    </a>
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::User-->
                        </div>
                        <!--end::Title-->

                        <!--begin::Stats-->
                        <div class="d-flex flex-wrap flex-stack">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column flex-grow-1 pe-8">
                                <!--begin::Stats-->
                                <div class="d-flex flex-wrap text-center">
                                    <!--begin::Stat-->
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                 data-kt-countup-value="{{$data['learn_avg']}}"
                                                 data-kt-countup-prefix="%"
                                                 data-kt-initialized="1">{{$data['learn_avg']}}%
                                            </div>
                                        </div>
                                        <!--end::Number-->

                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-500">{{t('Leaning')}}</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                 data-kt-countup-value="{{$data['practise_avg']}}"
                                                 data-kt-countup-prefix="%"
                                                 data-kt-initialized="1">{{$data['practise_avg']}}%
                                            </div>
                                        </div>
                                        <!--end::Number-->

                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-500">{{t('Practise')}}</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                 data-kt-countup-value="{{$data['play_avg']}}"
                                                 data-kt-countup-prefix="%"
                                                 data-kt-initialized="1">{{$data['play_avg']}}%
                                            </div>
                                        </div>
                                        <!--end::Number-->

                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-500">{{t('Playing')}}</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                 data-kt-countup-value="{{$data['test_avg']}}"
                                                 data-kt-countup-prefix="%"
                                                 data-kt-initialized="1">{{$data['test_avg']}}%
                                            </div>
                                        </div>
                                        <!--end::Number-->

                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-500">{{t('Test')}}</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->

                                    <!--begin::Stat-->
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <i class="ki-duotone ki-arrow-up fs-3 text-success me-2"><span class="path1"></span><span class="path2"></span></i>
                                            <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                 data-kt-countup-value="{{$data['passed_tests']}}"
                                                 data-kt-countup-prefix="%"
                                                 data-kt-initialized="1">{{$data['passed_tests']}}
                                            </div>
                                        </div>
                                        <!--end::Number-->

                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-500">{{t('Passed Tests')}}</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <i class="ki-duotone ki-arrow-down fs-3 text-danger me-2"><span class="path1"></span><span class="path2"></span></i>
                                            <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                 data-kt-countup-value="{{$data['failed_tests']}}"
                                                 data-kt-countup-prefix="%"
                                                 data-kt-initialized="1">{{$data['failed_tests']}}
                                            </div>
                                        </div>
                                        <!--end::Number-->

                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-500">{{t('Failed Tests')}}</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->


                                </div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Wrapper-->

                        </div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Details-->
            </div>
        </div>
    </div>
@endsection
@section('filter')
    <div class="row">
        <div class="col-lg-3 mb-2">
            <label>{{t('Grade')}} :</label>
            <select name="grade_id" class="form-select get_levels" data-control="select2" data-placeholder="{{t('Select Grade')}}"
                    data-allow-clear="true">
                <option></option>
                @php
                    $grades = \App\Models\Grade::query()->get();
                @endphp
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                @endforeach
            </select>
        </div>


        <div class="col-lg-3 mb-2">
            <label>{{t('Lesson')}} :</label>
            <select  name="lesson_id" id="lesson_id" class="form-select" data-control="select2" data-placeholder="{{t('Select Lesson')}}" data-allow-clear="true">
                <option></option>
            </select>
        </div>
        <div class="col-lg-3 mb-2">
            <label>{{t('Type')}} :</label>
            <select name="type" id="type" class="form-select" data-control="select2" data-placeholder="{{t('Select Type')}}" data-allow-clear="true">
                <option></option>
                <option value="learn">{{t('Learn')}}</option>
                <option value="practise">{{t('Practise')}}</option>
                <option value="test">{{t('Test')}}</option>
                <option value="play">{{t('Play')}}</option>
            </select>
        </div>

        <div class="col-lg-3 mb-2">
            <label>{{t('Start Date')}} :</label>
            <input autocomplete="disabled" class="form-control form-control-solid" name="date_range" value="" placeholder="{{t('Pick date range')}}" id="date_range"/>
            <input type="hidden" name="start_date" id="start_date_range" />
            <input type="hidden" name="end_date" id="end_date_range" />
        </div>

    </div>
@endsection

@section('content')
    <div class="row">
        <table class="table table-row-bordered gy-5" id="datatable">
            <thead>
            <tr class="fw-semibold fs-6 text-gray-800">
                <th class="text-start"></th>
                <th class="text-start">{{ t('Lesson') }}</th>
                <th class="text-start">{{ t('Type') }}</th>
                <th class="text-start">{{ t('Start Date') }}</th>
                <th class="text-start">{{ t('Time Spent') }}</th>
            </tr>
            </thead>
        </table>
    </div>
@endsection

@section('script')
    <script src="{{asset('assets_v1/js/custom.js')}}"></script>

    <script>
        var TABLE_URL = "{{ route($guard.'.user.review', $user->id) }}";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'lesson', name: 'lesson'},
            {data: 'type', name: 'type'},
            {data: 'created_at', name: 'created_at'},
            {data: 'time_spent', name: 'time_spent'},
        ];
        initializeDateRangePicker();
        getLessonsByGrade()
    </script>

    <script src="{{asset('assets_v1/js/datatable.js')}}?v={{time()}}"></script>
@endsection
