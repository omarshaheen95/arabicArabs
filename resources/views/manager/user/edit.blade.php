@extends('manager.layout.container')

@section('title')
    {{$title}}
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item b text-muted">
        {{$title}}
    </li>
@endpush

@section('style')
    <link href="{{asset('intl-tel-input-master/build/css/intlTelInput.min.css')}}" rel="stylesheet">
    @if(app()->getLocale() == 'ar')
        <style>
            .iti * {
                direction: ltr;
            }
        </style>
    @endif
@endsection
{{--@dd($user->toArray())--}}

@section('content')
    <form action="{{ isset($user) ? route('manager.user.update', $user->id): route('manager.user.store') }}"
          method="post" class="form" id="form-profile-save" enctype="multipart/form-data">
        @csrf

        @if(isset($user))
            @method('PATCH')
        @endif

        <div class="row">
            <!--begin::Image input-->
            <div class="col-12 d-flex flex-column align-items-center mb-5">
                <div>{{t('Image')}}</div>
                <div class="image-input image-input-outline" data-kt-image-input="true"
                     style="background-image: url(/assets_v1/media/svg/avatars/blank.svg)">

                    @if(isset($user) && $user->image )
                        <div class="image-input-wrapper w-125px h-125px"
                             style="background-image: url({{asset($user->image)}})"></div>

                    @else
                        <div class="image-input-wrapper w-125px h-125px"
                             style="background-image: url(/assets_v1/media/svg/avatars/blank.svg)"></div>
                    @endif

                    <!--begin::Edit button-->
                    <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                           data-kt-image-input-action="change"
                           data-bs-toggle="tooltip"
                           data-bs-dismiss="click"
                           title="Change avatar">
                        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                        <!--begin::Inputs-->
                        <input type="file" name="image" accept=".png, .jpg, .jpeg"/>
                        <input type="hidden" name="avatar_remove"/>
                        <!--end::Inputs-->
                    </label>
                    <!--end::Edit button-->

                    <!--begin::Cancel button-->
                    <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                          data-kt-image-input-action="cancel"
                          data-bs-toggle="tooltip"
                          data-bs-dismiss="click"
                          title="Cancel avatar">
                <i class="ki-outline ki-cross fs-3"></i>
            </span>
                    <!--end::Cancel button-->

                    <!--begin::Remove button-->
                    <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                          data-kt-image-input-action="remove"
                          data-bs-toggle="tooltip"
                          data-bs-dismiss="click"
                          title="Remove avatar">
                <i class="ki-outline ki-cross fs-3"></i>
            </span>
                    <!--end::Remove button-->
                </div>
            </div>
            <!--end::Image input-->

            <div class="row">
                <div class="col-4 mb-2">
                    <div class="form-group">
                        <label class="form-label">{{t('Name')}}</label>
                        <input type="text" name="name" class="form-control name" placeholder="{{t('Name')}}"
                               value="{{ isset($user->name) ? $user->name : old("name") }}" required>
                    </div>
                </div>
                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label class="form-label mb-1">{{t('Email')}}:</label>
                        <div class="input-group mb-5">
                            <input dir="ltr" name="email" type="text" placeholder="{{t('Email')}}"
                                   value="{{ isset($user->email) ? $user->email : old("email") }}"
                                   class="form-control username" aria-describedby="basic-addon1"/>
                            <span class="input-group-text" id="basic-addon1">
                         <a class="p-0 cursor-pointer" id="generateUserName"><i class="fas fa-refresh"></i></a>
                     </span>
                        </div>
                    </div>
                </div>
                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label class="form-label">{{t('Mobile')}}</label>
                        <input type="text" class="form-control" id="Phone" placeholder="{{t('Mobile')}}" name="phone"
                               value="{{ isset($user) ? $user->mobile : old('mobile') }}">
                        <input type="hidden" id="country-code" name="country_code"
                               value="{{ isset($user) ? $user->country_code : old('country_code') }}">
                        <input type="hidden" id="short-country" name="short_country"
                               value="{{ isset($user) ? $user->short_country : old('short_country') }}">
                        <input type="hidden" placeholder="الموبايل" name="mobile"
                               id="mobileHidden"
                               value="{{ isset($user) ? $user->mobile : old('mobile') }}"
                               class="form-control form-control-lg">
                        <span id="valid-msg" class="hide">✓ فعال</span>
                        <span id="error-msg" class="hide"></span>
                    </div>
                </div>
                <div class="col-2 mb-2">
                    <div class="form-group">
                        <label for="Email" class="form-label">{{t('Password')}}</label>
                        <input type="password" name="password" class="form-control" placeholder="{{t('Password')}}">
                    </div>
                </div>

                <div class="col-4 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label">{{t('School')}}</label>
                        <select class="form-select" name="school_id" data-control="select2" data-allow-clear="true"
                                data-placeholder="{{t('Select School')}}">
                            <option></option>
                            @foreach($schools as $school)
                                <option value="{{$school->id}}" {{isset($user) && $user->school_id == $school->id ? 'selected':''}}>{{$school->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label">{{t('Teacher')}}</label>
                        <select class="form-select" name="teacher_id" data-control="select2" data-allow-clear="true"
                                data-placeholder="{{t('Select Teacher')}}">
                            <option></option>
                            @isset($teachers)
                                @foreach($teachers as $teacher)
                                    <option value="{{$teacher->id}}" {{isset($user) && optional($user->teacherUser)->teacher_id == $teacher->id ? 'selected':''}}>{{$teacher->name}}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                </div>

                <div class="col-4 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label">{{t('Grade')}}</label>
                        <select class="form-select" name="grade_id" data-control="select2" data-allow-clear="true"
                                data-placeholder="{{t('Select Grade')}}">
                            <option value="" selected disabled>{{t('Select Grade')}}</option>
                            @foreach($grades as $grade)
                                <option
                                    value="{{$grade->id}}" {{isset($user) && $user->grade_id == $grade->id ? 'selected':''}}>
                                    {{$grade->name}}</option>

                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label">{{t('Alternative Grade')}}</label>
                        <select class="form-select" name="alternate_grade_id" data-control="select2"
                                data-allow-clear="true" data-placeholder="{{t('Select Alternative Grade')}}">
                            <option value="" selected>{{t('Select Grade')}}</option>
                            @foreach($grades as $grade)
                                <option
                                    value="{{$grade->id}}" {{isset($user) && $user->alternate_grade_id == $grade->id ? 'selected':''}}>
                                    {{$grade->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label">{{t('Year')}}</label>
                        <select class="form-select" name="year_id" data-control="select2" data-allow-clear="true"
                                data-placeholder="{{t('Select Year')}}">
                            <option></option>
                            @foreach($years as $year)
                                <option value="{{$year->id}}" {{isset($user) && $user->year_id == $year->id ? 'selected':''}}>{{$year->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label">{{t('Years of learning')}}</label>
                        <select class="form-select" name="year_learning" data-control="select2" data-allow-clear="true"
                                data-placeholder="{{t('Select Years')}}">
                            @foreach($years_learning as $year)
                                <option
                                    value="{{$year}}" {{isset($user) && $user->year_learning == $year ? 'selected':''}}>
                                    {{$year}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label">{{t('Section')}}</label>
                        <input class="form-control" name="section" type="text"
                               value="{{ isset($user) ? $user->section : old("section") }}" placeholder="{{t('Section')}}">
                    </div>
                </div>


                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label">{{t('Package')}}</label>
                        <select class="form-select" name="package_id" data-control="select2" data-allow-clear="true"
                                data-placeholder="{{t('Select Package')}}">
                            <option></option>
                            @foreach($packages as $package)
                                <option data-days="{{$package->days}}" value="{{$package->id}}" {{isset($user) && $user->package_id == $package->id ? 'selected':''}}>{{$package->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label">{{t('Active To')}}</label>
                        <input class="form-control" name="active_to" type="text"
                               value="{{ isset($user) ? $user->active_to : old("active_to") }}" id="active_to_date" placeholder="{{t('Active To')}}">
                    </div>
                </div>

                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label">{{t('SID')}}</label>
                        <input class="form-control" name="id_number" type="text"
                               value="{{ isset($user) ? $user->id_number : old("id_number") }}" placeholder="{{t('SID')}}">
                    </div>
                </div>

                <div class="col-12 row">
                    <div class="col-1 mb-2 d-flex gap-2 mt-4">
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" value="1" name="active" id="flexCheckDefault" {{isset($user) && $user->active ? 'checked':''}}/>
                            <label class="form-check-label text-dark" for="flexCheckDefault">
                                {{t('Active')}}
                            </label>
                        </div>
                    </div>

                    <div class="col-1 mb-2 d-flex gap-2 mt-4">
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" value="1" name="demo" id="flexCheckDefault" {{isset($user) && $user->demo ? 'checked':''}}/>
                            <label class="form-check-label text-dark" for="flexCheckDefault">
                                {{t('Demo')}}
                            </label>
                        </div>
                    </div>
                </div>


                <div id="demo_data" class="col-12 row justify-content-center d-none" dir="ltr">
                    <div class="col-5">
                        <label>{{t('Grades')}}</label>
                        <select name="from[]" id="multiselect" class="form-control" size="8" multiple="multiple">
                            @foreach($grades as $grade)
                                <option value="{{$grade->id}}">{{$grade->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-2 d-flex flex-column justify-content-center align-items-center mt-5" >
                        <button type="button" id="multiselect_rightAll" class="btn btn-secondary btn-sm mb-1"><i class="fa fa-forward"></i></button>
                        <button type="button" id="multiselect_rightSelected" class="btn btn-secondary btn-sm mb-1"><i class="fa fa-chevron-right"></i></button>
                        <button type="button" id="multiselect_leftSelected" class="btn btn-secondary btn-sm mb-1"><i class="fa fa-chevron-left"></i></button>
                        <button type="button" id="multiselect_leftAll" class="btn btn-secondary btn-sm mb-1"><i class="fa fa-backward"></i></button>
                    </div>

                    <div class="col-5">
                        <label>{{t('Selected Grades')}}</label>
                        <select name="demo_grades[]" id="multiselect_to" class="form-control" size="8" multiple="multiple">

                            @if(isset($user->demo_grades)&&count($user->demo_grades)>0)
                                @php
                                    $demo_grades = $grades->whereIn('id',$user->demo_grades);
                                @endphp
                                @foreach($demo_grades as $grade)
                                    <option value="{{$grade->id}}">{{$grade->name}}</option>
                                @endforeach
                            @endif

                        </select>
                    </div>
                </div>


            </div>
            <div class="row my-5">
                <div class="separator separator-content my-4"></div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mr-2">{{isset($user)?t('Update'):t('Save')}}</button>
                </div>
            </div>
        </div>

    </form>
@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\Manager\UserRequest::class, '#form-profile-save'); !!}
    <script src="{{asset('intl-tel-input-master/build/js/intlTelInput.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets_v1/lib/multiselect/multiselect.min.js')}}"></script>

    <script type="text/javascript">

        var input = document.querySelector("#Phone");
        window.intlTelInput(input, {
            formatOnDisplay: false,
        });
        errorMsg = document.querySelector("#error-msg"),
            validMsg = document.querySelector("#valid-msg");
        countryCode = document.querySelector("#country-code");
        shortCountry = document.querySelector("#short-country");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["غير فعال", "رمز دولة خاطئ", "قصير جدا", "طويل جدا", "رقم خاطئ"];

        // initialise plugin
        var iti = window.intlTelInput(input, {
            utilsScript: "{{ asset('intl-tel-input-master/build/js/utils.js?1562189064761')}}"
        });


        var reset = function () {
            input.classList.remove("error");
            errorMsg.innerHTML = "";
            errorMsg.classList.add("hide");
            validMsg.classList.add("hide");
            if (input.value.trim()) {
                if (iti.isValidNumber()) {
                    countryCode.value = iti.getSelectedCountryData().dialCode;
                    shortCountry.value = iti.getSelectedCountryData().iso2;
                    validMsg.classList.remove("hidden");
                } else {
                    input.classList.add("error");
                    var errorCode = iti.getValidationError();
                    errorMsg.innerHTML = errorMap[errorCode];
                    errorMsg.classList.remove("hidden");
                }
            }
        };

        // on blur: validate
        input.addEventListener('blur', reset);

        // on keyup / change flag: reset
        input.addEventListener('change', reset);
        input.addEventListener('keyup', reset);
    </script>
    <script>
        $(document).ready(function () {
            $('#generateUserName').click(function () {
                generateUserName();
            });
            $('#Phone').keyup(function () {
                $('#mobileHidden').val(iti.getNumber());
                console.log(iti.getNumber());
            });
            $('#active_to_date').flatpickr();
            //on change package get days and create date from today + days with format Y-m-d and add to flatpickr active to
            $('select[name="package_id"]').change(function () {
                let days = $(this).find(':selected').data('days');
                let date = new Date();
                date.setDate(date.getDate() + days);
                let formattedDate = date.toISOString().substr(0, 10);
                $('input[name="active_to"]').val(formattedDate);
                $('#active_to_date').flatpickr();
            });
            $('select[name="school_id"]').change(function () {
                var id = $(this).val();
                var url = '{{ route("manager.getTeacherBySchool", ":id") }}';
                url = url.replace(':id', id);
                $.ajax({
                    type: "get",
                    url: url,
                }).done(function (data) {
                    $('select[name="teacher_id"]').html(data.html);
                    console.log('data', data.html)
                });
            });
        });


        //Demo -------------------------------------------------------------------
        if (parseInt($('input[name="demo"]:checked').val()) === 1) {
            $('#demo_data').removeClass('d-none');
        }

        //show and hide Demo data
        $('input[name="demo"]').change(function () {
            var demo = $('input[name="demo"]:checked').val();
            if (parseInt(demo) === 1) {
                $('#demo_data').removeClass('d-none');
            } else {
                $('#demo_data').addClass('d-none');
            }
        });

        //Init multiselecet For Levels
        $('#multiselect').multiselect({
            sort:false
        });
    </script>

@endsection



