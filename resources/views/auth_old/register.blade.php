@extends('layouts.container')
@section('style')
    <link href="{{asset('intl-tel-input-master/build/css/intlTelInput.min.css')}}" rel="stylesheet">
    <style>
        .hide{
            display: none;
        }
    </style>
        <style>
            .iti * {
                direction: ltr;
            }
        </style>
        <script src="{{asset('intl-tel-input-master/build/js/intlTelInput.min.js')}}"></script>



@endsection
@section('content')
    <!-- START SLIDER -->
    <div id="slider" class="aos-item slider-bg theme-bg-secondary-light" data-aos="fade-in">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="section-warp">
                        <div class="section-header">
                            <h1 class="section-title"> تسجيل حساب جديد  </h1>
                            <p>يرجى إدخال كافة البيانات بصورة صحيحة</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="contact-item">
                        <div class="card">
                            <div class="card-body">
                                <form class="col-form" id="form_information" action="/register" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="name" placeholder="الاسم">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="email" placeholder="البريد الإلكتروني">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input class="form-control" id="Phone" name="phone" type="text" value="{{old('mobile')}}">
                                                <input type="hidden" id="country-code" name="country_code" value="{{old('country_code')}}">
                                                <input type="hidden" id="short-country" name="short_country" value="{{old('short_country')}}">
                                                <input type="hidden" placeholder="الموبايل" name="mobile" id="mobileHidden" value="{{old('mobile')}}" class="form-control form-control-lg">
                                                <span id="valid-msg" class="hide">✓ فعال</span>
                                                <span id="error-msg" class="hide"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="password" placeholder="كلمة المرور">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="password_confirmation" placeholder="تأكيد كلمة المرور">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <select class="form-control" name="school_id">
                                                    <option selected disabled value="">اختر مدرسة</option>
                                                    @foreach($schools as $school)
                                                    <option value="{{$school->id}}">{{$school->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <select class="form-control" name="grade_id">
                                                    <option selected disabled value="">اختر صف</option>
                                                    @foreach($grades as $grade)
                                                    <option value="{{$grade->id}}">{{$grade->name}}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-20px">
                                            <div class="form-group">
                                                <p class="mt-3">سنوات تعلم اللغة العربية</p>
                                                <div class="range-wrap">
                                                    <input name="year_learning" type="range" class="range" step="1" min="1" max="12" value="1">
                                                    <output class="bubble" style="right: calc(41% + 1.85px);" >41</output>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <select class="form-control" name="package_id">
                                                    <option selected disabled value="">اختر باقة</option>
                                                    @foreach($packages as $package)
                                                        <option value="{{$package->id}}" {{request()->get('package_id', false) && request()->get('package_id', false) == $package->id ? 'selected':''}}>{{$package->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mt-30px">
                                            <button type="submit" class="theme-btn theme-btn-default btn-block theme-btn-ss">
                                                تسجيل  <i class="flaticon-double-right-arrows-angles"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- END SLIDER -->
@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator->selector('#form_information') !!}
    <script src="{{asset('intl-tel-input-master/build/js/intlTelInput.min.js')}}"></script>
    <script type="text/javascript">
        var input = document.querySelector("#Phone");
        window.intlTelInput(input, {
            formatOnDisplay:false,
            initialCountry: "auto",
            geoIpLookup: function(callback) {
                $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "ae";
                    callback(countryCode);
                });
            },
            utilsScript: "{{ asset('intl-tel-input-master/build/js/utils.js?1562189064761')}}",
        });
        errorMsg = document.querySelector("#error-msg"),
            validMsg = document.querySelector("#valid-msg");
        countryCode = document.querySelector("#country-code");
        shortCountry = document.querySelector("#short-country");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["رقم خاطئ", "رمز الدولة خاطئ", "قصير للغاية", "طويل للغاية", "رقم خاطئ"];

        // initialise plugin
        var iti = window.intlTelInput(input, {
            initialCountry: "auto",
            geoIpLookup: function(callback) {
                $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            utilsScript: "{{ asset('intl-tel-input-master/build/js/utils.js?1562189064761')}}",
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('#Phone').keyup(function () {
                $('#mobileHidden').val(iti.getNumber());
                console.log(iti.getNumber());
            });
        });
    </script>

@endsection
