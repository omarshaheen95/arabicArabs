{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    2/4/2020 helpingHand--}}
@extends('manager.layout.container')
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{ t('General Settings') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            <i class="flaticon-responsive"></i>  {{ t('General Settings') }}</h3>
                    </div>
                </div>
                <form class="kt-form kt-form--label-right" id="form_information" enctype="multipart/form-data" action="{{ route('manager.settings.updateSettings') }}" method="post">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Logo') }}</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="upload-btn-wrapper">
                                                    <button class="btn btn-danger">{{ t('upload file') }}</button>
                                                    <input name="logo" class="imgInp" id="imgInp" type="file" />
                                                </div>
                                                <img id="blah" @if(!isset($setting) || is_null($setting->getOriginal('logo'))) style="display:none" @endif src="{{ isset($setting) && !is_null($setting->getOriginal('logo'))  ? url($setting->logo):'' }}" width="150" alt="No file chosen" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Min Logo') }}</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="upload-btn-wrapper">
                                                    <button class="btn btn-danger">{{ t('upload file') }}</button>
                                                    <input name="logo_min" class="imgInp" id="imgInp_min" type="file" />
                                                </div>
                                                <img id="blah_min" @if(!isset($setting) || is_null($setting->getOriginal('logo_min'))) style="display:none" @endif src="{{ isset($setting) && !is_null($setting->getOriginal('logo_min'))  ? url($setting->logo_min):'' }}" width="150" alt="No file chosen" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach(config('translatable.locales') as $local)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Name') }} <small>({{ $local }})</small></label>
                                            <input name="name:{{$local}}" type="text" value="{{ isset($setting) ? optional($setting->translate($local))->name : old("name:$local") }}" class="form-control" placeholder="">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
{{--                                <div class="row">--}}
{{--                                    @foreach(config('translatable.locales') as $local)--}}
{{--                                    <div class="col-md-6">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="exampleInputPassword1">{{ t('Title') }} <small>({{ $local }})</small></label>--}}
{{--                                            <textarea class="form-control" name="title:{{$local}}">{{ isset($setting) ? optional($setting->translate($local))->title : old("title:$local") }}</textarea>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    @endforeach--}}
{{--                                </div>--}}
{{--                                <div class="row">--}}
{{--                                    @foreach(config('translatable.locales') as $local)--}}
{{--                                    <div class="col-md-6">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="exampleInputPassword1">{{ t('Short Description') }} <small>({{ $local }})</small></label>--}}
{{--                                            <textarea class="form-control" name="bio:{{$local}}">{{ isset($setting) ? optional($setting->translate($local))->bio : old("bio:$local") }}</textarea>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    @endforeach--}}
{{--                                </div>--}}

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Email') }}</label>
                                            <input type="text" value="{{ isset($setting->email) ? $setting->email:old('email') }}" name="email" class="form-control" placeholder="{{ t('Email') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Mobile') }}</label>
                                            <input type="text" dir="ltr" value="{{ isset($setting->mobile) ? $setting->mobile:old('mobile') }}" name="mobile" class="form-control" placeholder="{{ t('Mobile') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('WhatsApp') }}</label>
                                            <input type="text" value="{{ isset($setting->whatsApp) ? $setting->whatsApp:old('whatsApp') }}" name="whatsApp" class="form-control" placeholder="{{ t('WhatsApp') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Facebook') }}</label>
                                            <input type="text" value="{{ isset($setting->facebook) ? $setting->facebook:old('facebook') }}" name="facebook" class="form-control" placeholder="{{ t('Facebook') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Twitter') }}</label>
                                            <input type="text" value="{{ isset($setting->twitter) ? $setting->twitter:old('twitter') }}" name="twitter" class="form-control" placeholder="{{ t('Twitter') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('LinkedIn') }}</label>
                                            <input type="text" value="{{ isset($setting->linkedIn) ? $setting->linkedIn:old('linkedIn') }}" name="linkedIn" class="form-control" placeholder="{{ t('LinkedIn') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Instagram') }}</label>
                                            <input type="text" value="{{ isset($setting->instagram) ? $setting->instagram:old('instagram') }}" name="instagram" class="form-control" placeholder="{{ t('Instagram') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Youtube') }}</label>
                                            <input type="text" value="{{ isset($setting->youtube) ? $setting->youtube:old('youtube') }}" name="youtube" class="form-control" placeholder="{{ t('Youtube') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Expire Date') }}</label>
                                            <input type="text" value="{{ isset($setting->expire_date) ? $setting->expire_date:old('expire_date') }}" name="expire_date" class="form-control" placeholder="{{ t('Expire Date') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Note Date') }}</label>
                                            <input type="text" value="{{ isset($setting->note_date) ? $setting->note_date:old('note_date') }}" name="note_date" class="form-control" placeholder="{{ t('Note Date') }}">
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" class="btn btn-danger">{{ t('Save') }}</button>&nbsp;
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
    <script src="{{ asset('assets/vendors/general/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    {!! $validator->selector('#form_information') !!}
@endsection
