{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
 --}}
@extends('manager.layout.container')
@section('style')
    <link href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{asset('intl-tel-input-master/build/css/intlTelInput.min.css')}}" rel="stylesheet">
    <style>
        .hide {
            display: none;
        }
    </style>
        <style>
            .iti * {
                direction: ltr;
            }
        </style>

@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.user.index') }}">المستخدمين</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($title) ? $title:'' }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-10 offset-1">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($title) ? $title:'' }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{ isset($user) ? route('manager.user.update', $user->id): route('manager.user.store') }}"
                      method="post">
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
                                            <label class="col-xl-3 col-lg-3 col-form-label">الصورة</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="upload-btn-wrapper">
                                                    <button class="btn btn-danger">رفع صورة</button>
                                                    <input name="image" class="imgInp" id="imgInp" type="file"/>
                                                </div>
                                                <img id="blah"
                                                     @if(!isset($user) || is_null($user->image)) style="display:none"
                                                     @endif src="{{ isset($user) && !is_null($user->image)  ? $user->image:'' }}"
                                                     width="150" alt="No file chosen"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">الاسم</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="name" type="text"
                                               value="{{ isset($user->name) ? $user->name : old("name") }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">البريد الإلكتروني</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="email" type="text"
                                               value="{{ isset($user->email) ? $user->email : old("email") }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">الموبايل</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" id="Phone" name="phone" type="text"
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
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">كلمة المرور</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="password" type="password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">المدرسة</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control select2L" title="اختر مدرسة" name="school_id">
                                            @foreach($schools as $school)
                                                <option
                                                    value="{{$school->id}}" {{isset($user) && $user->school_id == $school->id ? 'selected':''}}>{{$school->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">المدرس</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control teacher_id select2L" title="اختر مدرس" name="teacher_id">
                                            @isset($user)
                                                @foreach($teachers as $teacher)
                                                    <option
                                                        value="{{$teacher->id}}" {{isset($user) && optional($user->teacher_student)->teacher_id == $teacher->id ? 'selected':''}}>{{$teacher->name}}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">الصف</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control select2L" title="اختر صف" name="grade_id">
                                            @foreach($grades as $grade)

                                                    <option
                                                        value="{{$grade->id}}" {{isset($user) && $user->grade_id == $grade->id ? 'selected':''}}>
                                                        {{$grade->name}}</option>

                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">الصف البديل</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control select2L" title="اختر صف بديل" name="alternate_grade_id">
                                            @foreach($grades as $grade)

                                                <option
                                                    value="{{$grade->id}}" {{isset($user) && $user->alternate_grade_id == $grade->id ? 'selected':''}}>
                                                    {{$grade->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">سنوات التعلم</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control select2L" title="اختر سنوات التعلم" name="year_learning">
                                            @foreach($years_learning as $year)
                                            <option
                                                value="{{$year}}" {{isset($user) && $user->year_learning == $year ? 'selected':''}}>
                                                {{$year}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">الشعبة</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="section" type="text"
                                               value="{{ isset($user) ? $user->section : old("section") }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">باقة الإشتراك</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control select2L" title="اختر باقة" name="package_id">
                                            @foreach($packages as $package)
                                                <option
                                                    value="{{$package->id}}" {{isset($user) && $user->package_id == $package->id ? 'selected':''}}>{{$package->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">فعال حتى</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control date" name="active_to" type="text"
                                               value="{{ isset($user->active_to) ? $user->active_to->format('Y-m-d') : old("active_to") }}">
                                    </div>
                                </div>
                                {{--                                <div class="form-group row">--}}
                                {{--                                    <label class="col-3 col-form-label font-weight-bold">{{t('Active')}}</label>--}}
                                {{--                                    <div class="col-3">--}}
                                {{--                                        <span class="kt-switch">--}}
                                {{--                                            <label>--}}
                                {{--                                            <input type="checkbox" {{isset($user) && $user->active ? 'checked':''}} value="1" name="active">--}}
                                {{--                                            <span></span>--}}
                                {{--                                            </label>--}}
                                {{--                                        </span>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit"
                                            class="btn btn-danger">حفظ</button>&nbsp;
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
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"
            type="text/javascript"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\Manager\UserRequest::class, '#form_information'); !!}
    <script src="{{asset('intl-tel-input-master/build/js/intlTelInput.min.js')}}"></script>
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

            $('select[name="school_id"]').change(function () {
                var id = $(this).val();
                var url = '{{ route("manager.getTeacherBySchool", ":id") }}';
                url = url.replace(':id', id);
                $.ajax({
                    type: "get",
                    url: url,
                }).done(function (data) {
                    $('select[name="teacher_id"]').html(data.html);
                    $('select[name="teacher_id"]').selectpicker('refresh');
                });
            });

        });
    </script>

@endsection
