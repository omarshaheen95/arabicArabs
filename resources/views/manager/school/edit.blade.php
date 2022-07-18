{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
 --}}
@extends('manager.layout.container')
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.school.index') }}">المدارس</a>
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
                      action="{{ isset($school) ? route('manager.school.update', $school->id): route('manager.school.store') }}"
                      method="post">
                    {{ csrf_field() }}
                    @if(isset($school))
                        <input type="hidden" name="_method" value="patch">
                    @endif
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-xl-3 col-lg-3 col-form-label">الشعار</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="upload-btn-wrapper">
                                                    <button class="btn btn-danger">رفع الشعار</button>
                                                    <input name="logo" class="imgInp" id="imgInp" type="file"/>
                                                </div>
                                                <img id="blah"
                                                     @if(!isset($school) || is_null($school->logo)) style="display:none"
                                                     @endif src="{{ isset($school) && !is_null($school->logo)  ? $school->logo:'' }}"
                                                     width="150" alt="No file chosen"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">الاسم</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="name" type="text"
                                               value="{{ isset($school) ? $school->name : old("name") }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">البريد الإلكتروني</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="email" type="text"
                                               value="{{ isset($school) ? $school->email : old("email") }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">كلمة المرور</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="password" type="password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">الموبايل</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="mobile" type="text"
                                               value="{{ isset($school) ? $school->mobile : old("mobile") }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">الموقع الإلكتروني</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="website" type="text"
                                               value="{{ isset($school) ? $school->website : old("website") }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label font-weight-bold">التفعيل</label>
                                    <div class="col-3">
                                        <span class="kt-switch">
                                            <label>
                                            <input type="checkbox"
                                                   {{isset($school) && $school->active ? 'checked':''}} value="1"
                                                   name="active">
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
    {!! JsValidator::formRequest(\App\Http\Requests\Manager\SchoolRequest::class, '#form_information'); !!}
@endsection
