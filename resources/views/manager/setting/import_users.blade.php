@extends('manager.layout.container')

@section('style')
    <link href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}" rel="stylesheet" type="text/css"/>
    @endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">استيراد طلاب</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{ route('manager.import.users_import') }}"
                      method="post">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">الملف </label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="import_file" type="file">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('School') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control select2L" name="school_id">
                                            <option value="" selected disabled>{{t('Select School')}}</option>
                                            @foreach($schools as $school)
                                                <option
                                                    value="{{$school->id}}">{{$school->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">{{t('Subscription package')}}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="package_id">
                                            <option value="" selected disabled>{{t('Select package')}}</option>
                                            @foreach($packages as $package)
                                                <option
                                                    value="{{$package->id}}">{{$package->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Active To') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control date" name="active_to" type="text"
                                               value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Last Of Email') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="last_of_email" type="text"
                                               value="@arabic-arabs.com">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Default mobile') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="default_mobile" type="text"
                                               value="+971500000000">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('country code') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="country_code" type="text"
                                               value="971">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('short country') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="short_country" type="text"
                                               value="ae">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit"
                                            class="btn btn-danger">استيراد</button>&nbsp;
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
    <script>
        $(document).ready(function () {
            $('.date').datepicker({
                autoclose: true,
                rtl: true,
                todayHighlight: true,
                orientation: "bottom left",
                format: 'yyyy-mm-dd'
            });
        });
    </script>
@endsection
