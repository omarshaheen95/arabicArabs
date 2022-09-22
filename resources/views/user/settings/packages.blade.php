{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('user.layout.container_v2')
@section('style')

@endsection
@section('content')
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h1 class="title"> ترقية الإشتراك </h1>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="/home"> الرئيسة </a>
                            <span class="breadcrumb-item active" aria-current="page"> ترقية الإشتراك </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">

                <div class="col-lg-6">
                    <div class="profile-card form form-login">
                        <div class="profile-header">
                            <div class="title"> تغيير كلمة المرور </div>
                        </div>
                        <form id="form_password" action="{{ route('post_package_upgrade') }}" method="post" class="needs-validation" novalidate>
                            @csrf

                            <div class="form-group">
                                <label for="password" class="form-label"> الصف </label>
                                <div class="form-control-icon">
                                    <div class="icon">
                                        <img src="{{asset('web_assets/img/password.svg')}}" alt="">
                                    </div>
                                    <select class="form-control form-select" name="grade_id" required>
                                        <option value="" selected disabled>اختر صف</option>
                                        @foreach($grades as $grade)
                                            <option value="{{$grade->id}}" {{isset($user) && $user->grade_id == $grade->id ? 'selected':''}}>الصف {{$grade->grade_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">الباقة </label>
                                <div class="form-control-icon">
                                    <div class="icon">
                                        <img src="{{asset('web_assets/img/password.svg')}}" alt="">
                                    </div>
                                    <select class="form-control form-select" name="package_id" required>
                                        <option value="" selected disabled>اختر باقة</option>
                                        @foreach($packages as $package)
                                            <option value="{{$package->id}}" {{isset($user) && $user->package_id == $package->id ? 'selected':''}}>
                                                {{$package->name}}  - {{$package->price}}$ - {{$package->days}} يوم
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-theme btn-submit">
                                    <span class="spinner-border d-none"></span>
                                    <span class="text"> ترقية الإشتراك </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
{{--    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>--}}
{{--    {!! $validator->selector('#form_information') !!}--}}
@endsection
