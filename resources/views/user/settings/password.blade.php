{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('user.layout.container_v2')
@section('style')

@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{ t('Change password') }}
        </li>
    @endpush
    <section class="inner-page">
        <section>
            <div class="card mt-3 mb-4 border-0">
                <div class="card-header bg-white text-center">
                    <h4 style="font-weight: bold">
                        تغيير كلمة المرور - Change password
                    </h4>
                </div>
                <form enctype="multipart/form-data" id="form_information" action="{{ route('update_password') }}"
                      method="post">
                    @csrf
                    <div class="card-body pb-0">
{{--                        <div class="form-group row">--}}
{{--                            <label class="col-xl-1"></label>--}}
{{--                            <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Current password') }}</label>--}}
{{--                            <div class="col-xl-6">--}}
{{--                                <input class="form-control" name="current_password" type="password">--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="form-group row">
                            <label class="col-xl-1"></label>
                            <label class="col-xl-3 col-lg-3 col-form-label">{{ t('New Password') }}</label>
                            <div class="col-xl-6">
                                <input class="form-control" name="password" type="password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-1"></label>
                            <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Password confirmation') }}</label>
                            <div class="col-xl-6">
                                <input class="form-control" name="password_confirmation" type="password">
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 card-footer bg-white text-left">
                        <button type="submit" class="btn btn-danger">{{t('Complete & save')}}</button>
                    </div>
                </form>
            </div>
        </section>
    </section>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator->selector('#form_information') !!}
@endsection
