{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    2/4/2020 helpingHand--}}
@extends('school.layout.container')
@section('style')
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{ t('personal information') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-10 offset-1">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ t('personal information') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right" action="{{ route('school.profile.update') }}" method="post">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        @php
                                            $school = Auth::user();
                                        @endphp
                                        <div class="form-group">
                                            <label class="col-xl-3 col-lg-3 col-form-label">{{ t('logo') }}</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="upload-btn-wrapper">
                                                    <button class="btn btn-danger">{{ t('upload logo') }}</button>
                                                    <input name="logo" class="imgInp" id="imgInp" type="file" />
                                                </div>
                                                <img id="blah" @if(!isset($school) || is_null($school->logo)) style="display:none" @endif src="{{ isset($school) && !is_null($school->logo)  ? $school->logo:'' }}" width="150" alt="No file chosen" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('username') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="name" type="text" value="{{ Auth::user()->name }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('E-mail') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="email" type="email" value="{{ Auth::user()->email }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Mobile') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="mobile" type="text" value="{{ Auth::user()->mobile }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Website') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="website" type="text" value="{{ Auth::user()->website }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-3 col-xl-3">
                                </div>
                                <div class="col-lg-9 col-xl-9">
                                    <button type="submit" class="btn btn-brand">{{ t('update') }}</button>&nbsp;
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
    <script>
    </script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator->selector('#form_information') !!}
@endsection
