@extends('layouts.container')
@section('style')
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
        <script src="{{asset('intl-tel-input-master/build/js/intlTelInput.min.js')}}"></script>

    @else


    @endif

@endsection
@section('content')
    <!-- START SLIDER -->
    <div id="slider" class="aos-item slider-bg theme-bg-secondary-light" data-aos="fade-in">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="section-warp">
                        <div class="section-header">
                            <h1 class="section-title"> {{w('Register New Account')}}  </h1>
                            <p>{{w('Fill in your personal information and start with the demo account')}}</p>
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
                                                <input type="text" class="form-control" name="name" placeholder="{{w('Name')}}">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="email" placeholder="{{w('Email')}}">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input class="form-control" id="Phone" name="phone" type="text" value="{{old('mobile')}}">
                                                <input type="hidden" id="country-code" name="country_code" value="{{old('country_code')}}">
                                                <input type="hidden" id="short-country" name="short_country" value="{{old('short_country')}}">
                                                <input type="hidden" placeholder="{{t('Mobile')}}" name="mobile" id="mobileHidden" value="{{old('mobile')}}" class="form-control form-control-lg">
                                                <span id="valid-msg" class="hide">✓ {{ t('Valid') }}</span>
                                                <span id="error-msg" class="hide"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="password" placeholder="{{w('Password')}}">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="password_confirmation" placeholder="{{w('Password Confirmation')}} ">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <select class="form-control" name="school_id">
                                                    <option selected disabled value="">{{w('Select School')}}</option>
                                                    @foreach($schools as $school)
                                                    <option value="{{$school->id}}">{{$school->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <select class="form-control" name="grade">
                                                    <option selected disabled value="">{{w('Select Grade')}}</option>
                                                    <option value="15">KG 2 / Year 1</option>
                                                    <option value="1">Grade 1 / Year 2</option>
                                                    <option value="2">Grade 2 / Year 3</option>
                                                    <option value="3">Grade 3 / Year 4</option>
                                                    <option value="4">Grade 4 / Year 5</option>
                                                    <option value="5">Grade 5 / Year 6</option>
                                                    <option value="6">Grade 6 / Year 7</option>
                                                    <option value="7">Grade 7 / Year 8</option>
                                                    <option value="8">Grade 8 / Year 9</option>
                                                    <option value="9">Grade 9 / Year 10</option>
                                                    <option value="10">Grade 10 / Year 11</option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <select class="form-control" name="package_id">
                                                    <option selected disabled value="">{{w('Select package')}}</option>
                                                    @foreach($packages as $package)
                                                        <option value="{{$package->id}}" {{request()->get('package_id', false) && request()->get('package_id', false) == $package->id ? 'selected':''}}>{{$package->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mt-30px">
                                            <button type="submit" class="theme-btn theme-btn-default btn-block theme-btn-ss">
                                                {{w('Register')}} <i class="flaticon-double-right-arrows-angles"></i></button>
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
        var errorMap = ["{{ t('Invalid number') }}", "{{ t('Invalid country code') }}", "{{ t('Too short') }}", "{{ t('Too long') }}", "{{ t('Invalid number') }}"];

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
