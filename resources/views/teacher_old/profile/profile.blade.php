{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    2/4/2020 helpingHand--}}
@extends('teacher.layout.container')
@section('style')
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{$title}}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-10 offset-1">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{$title}}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right" action="{{ route('teacher.profile.update') }}" method="post">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">الاسم</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="name" type="text" value="{{ Auth::user()->name }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">البريد الإلكتروني</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="email" type="email" value="{{ Auth::user()->email }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">الموبايل</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="mobile" type="text" value="{{ Auth::user()->mobile }}">
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
                                    <button type="submit" class="btn btn-brand">حفظ</button>&nbsp;
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
