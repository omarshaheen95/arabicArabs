@extends('supervisor.layout.container')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-2">
            <div class="kt-portlet">
                <div class="kt-portlet__body">
                    <!--begin::Form-->
                    <div class="kt-form">
                        <div class="row">
                            <div class="col-4 justify-content-center text-center">
                                <i class="flaticon2-user-outline-symbol" style="font-size: 3.5rem"></i>
                            </div>
                            <label class="col-7 text-center">
                                <span style="font-size: 2rem">
                                    {{ $teachers }}
                                </span>
                                <br>
                                المدرسيين
                            </label>

                        </div>
                    </div>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="kt-portlet">
                <div class="kt-portlet__body">
                    <!--begin::Form-->
                    <div class="kt-form">
                        <div class="row">
                            <div class="col-4 justify-content-center text-center">
                                <i class="flaticon2-group" style="font-size: 3.5rem"></i>
                            </div>
                            <label class="col-7 text-center">
                                <span style="font-size: 2rem">
                                    {{ $students }}
                                </span>
                                <br>
                                الطلاب
                            </label>

                        </div>
                    </div>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="kt-portlet">
                <div class="kt-portlet__body">
                    <!--begin::Form-->
                    <div class="kt-form">
                        <div class="row">
                            <div class="col-4 justify-content-center text-center">
                                <i class="flaticon2-writing" style="font-size: 3.5rem"></i>
                            </div>
                            <label class="col-8 text-center">
                                <span style="font-size: 2rem">
                                    {{ $tests }}
                                </span>
                                <br>
                                اختبارات الطلاب
                            </label>

                        </div>
                    </div>
                    <!--end::Form-->
                </div>
            </div>
        </div>
    </div>
@endsection
