{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
 --}}
@extends('manager.layout.container')
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.package.index') }}">الباقات</a>
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
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right" action="{{ isset($package) ? route('manager.package.update', $package->id): route('manager.package.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($package))
                        <input type="hidden" name="_method" value="patch">
                    @endif
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">الاسم</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <input class="form-control" name="name" type="text" value="{{ isset($package->name) ? $package->name : old("name") }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">الأيام</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <input class="form-control" name="days" type="number" value="{{ isset($package->days) ? $package->days : old("days") }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">السعر</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <input class="form-control" name="price" type="number" value="{{ isset($package->price) ? $package->price : old("price") }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label font-weight-bold">التفعيل</label>
                                        <div class="col-3">
                                        <span class="kt-switch">
                                            <label>
                                            <input type="checkbox" {{isset($package) && $package->active ? 'checked':''}} value="1" name="active">
                                            <span></span>
                                            </label>
                                        </span>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" class="btn btn-danger">حفظ</button>&nbsp;
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
    {!! JsValidator::formRequest(\App\Http\Requests\Manager\PackageRequest::class, '#form_information'); !!}

@endsection
