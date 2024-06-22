{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
 --}}
@extends('supervisor.layout.container')
@section('style')
    <link href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{asset('intl-tel-input-master/build/css/intlTelInput.min.css')}}" rel="stylesheet">
    <style>
        .hide{
            display: none;
        }
    </style>
    @if(app()->getLocale() == 'ar')
        <style>
            .iti * {
                direction: ltr;
            }
        </style>
    @endif

@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('supervisor.student.index') }}">{{ t('Students') }}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($user) ? t('Edit Student') : t('Add Student') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-10 offset-1">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($user) ? t('Edit Student') : t('Add Student') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right" action="{{ isset($user) ? route('supervisor.student.update', $user->id): route('supervisor.student.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($user))
                        <input type="hidden" name="_method" value="patch">
                    @endif
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Image') }}</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="upload-btn-wrapper">
                                                    <button class="btn btn-danger">{{ t('Upload Image') }}</button>
                                                    <input name="image" class="imgInp" id="imgInp" type="file" />
                                                </div>
                                                <img id="blah" @if(!isset($user) || is_null($user->image)) style="display:none" @endif src="{{ isset($user) && !is_null($user->image)  ? $user->image:'' }}" width="150" alt="No file chosen" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Name') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="name" type="text" value="{{ isset($user->name) ? $user->name : old("name") }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Email') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="email" type="text" value="{{ isset($user->email) ? $user->email : old("email") }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Mobile') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" id="Phone" name="phone" type="text" value="{{ isset($user) ? $user->mobile : old('mobile') }}">
                                        <input type="hidden" id="country-code" name="country_code" value="{{ isset($user) ? $user->country_code : old('country_code') }}">
                                        <input type="hidden" id="short-country" name="short_country" value="{{ isset($user) ? $user->short_country : old('short_country') }}">
                                        <input type="hidden" placeholder="{{t('Mobile')}}" name="mobile" id="mobileHidden" value="{{ isset($user) ? $user->mobile : old('mobile') }}" class="form-control form-control-lg">
                                        <span id="valid-msg" class="hide">✓ {{ t('Valid') }}</span>
                                        <span id="error-msg" class="hide"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Password') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="password" type="password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Teacher') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="teacher_id">
                                            <option value="" selected>{{t('Select teacher')}}</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{$teacher->id}}" {{isset($user) && optional($user->teacher_student)->teacher_id == $teacher->id ? 'selected':''}}>{{$teacher->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Years of learning') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="year_learning">
                                            <option value="" selected>{{t('Select years')}}</option>
                                            <option value="0" {{isset($user) && $user->year_learning == 0 ? 'selected':''}}>-</option>
                                            <option value="1" {{isset($user) && $user->year_learning == 1 ? 'selected':''}}>1</option>
                                            <option value="2" {{isset($user) && $user->year_learning == 2 ? 'selected':''}}>2</option>
                                            <option value="3" {{isset($user) && $user->year_learning == 3 ? 'selected':''}}>3</option>
                                            <option value="4" {{isset($user) && $user->year_learning == 4 ? 'selected':''}}>4</option>
                                            <option value="5" {{isset($user) && $user->year_learning == 5 ? 'selected':''}}>5</option>
                                            <option value="6" {{isset($user) && $user->year_learning == 6 ? 'selected':''}}>6</option>
                                            <option value="7" {{isset($user) && $user->year_learning == 7 ? 'selected':''}}>7</option>
                                            <option value="8" {{isset($user) && $user->year_learning == 8 ? 'selected':''}}>8</option>
                                            <option value="9" {{isset($user) && $user->year_learning == 9 ? 'selected':''}}>9</option>
                                            <option value="10" {{isset($user) && $user->year_learning == 10 ? 'selected':''}}>10</option>
                                            <option value="11" {{isset($user) && $user->year_learning == 11 ? 'selected':''}}>11</option>
                                            <option value="12" {{isset($user) && $user->year_learning == 12 ? 'selected':''}}>12</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" class="btn btn-danger">{{ isset($user) ? t('update'):t('create') }}</button>&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\175\UserRequest::class, '#form_information'); !!}
    <script src="{{asset('intl-tel-input-master/build/js/intlTelInput.min.js')}}"></script>
    <script type="text/javascript">
        var input = document.querySelector("#Phone");
        window.intlTelInput(input, {
            formatOnDisplay:false,
        });
        errorMsg = document.querySelector("#error-msg"),
            validMsg = document.querySelector("#valid-msg");
        countryCode = document.querySelector("#country-code");
        shortCountry = document.querySelector("#short-country");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["{{ t('Invalid number') }}", "{{ t('Invalid country code') }}", "{{ t('Too short') }}", "{{ t('Too long') }}", "{{ t('Invalid number') }}"];

        // initialise plugin
        var iti = window.intlTelInput(input, {
            utilsScript: "{{ asset('intl-tel-input-master/build/js/utils.js?1562189064761')}}"
        });



        var reset = function() {
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
        $(document).ready(function() {
            $('.date').datepicker({
                autoclose: true,
                rtl: true,
                todayHighlight: true,
                orientation: "bottom left",
                format: 'yyyy-mm-dd'
            });
            $('#Phone').keyup(function () {
                $('#mobileHidden').val(iti.getNumber());
                console.log(iti.getNumber());
            });
        });
    </script>

@endsection
