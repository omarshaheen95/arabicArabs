@extends('manager.layout.container')
@section('title')
    {{$title}}
@endsection
@push('breadcrumb')
    <li class="breadcrumb-item text-muted">
        {{$title}}
    </li>
@endpush
@section('content')
    <div class="row">
        <form class="form" id="form_data"
              action="{{route('manager.update-password')}}"
              method="post">
            @csrf
            @isset($manager)
                <input type="hidden" name="_method" value="PATCH"/>
            @endisset
            <div class="form-group row">

                <div class="col-lg-4">
                    <label class="mb-2">{{t('Old Password')}} :</label>
                    <input name="old_password" type="password" placeholder="{{t('Old Password')}}"
                           class="form-control"/>
                </div>
                <div class="col-lg-4">
                    <label class="mb-2">{{t('Password')}} :</label>
                    <input name="password" type="password" placeholder="{{t('Password')}}"
                           class="form-control"/>
                </div>
                <div class="col-lg-4">
                    <label class="mb-2">{{t('Confirmed Password')}} :</label>
                    <input name="password_confirmation" type="password" placeholder="{{t('Confirmed Password')}}"
                           class="form-control"/>
                </div>

            </div>

            <div class="row my-5">
                <div class="separator separator-content my-4"></div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mr-2">{{t('Submit')}}</button>
                </div>
            </div>
        </form>
    </div>

@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\Manager\ManagerPasswordRequest::class, '#form_data'); !!}
@endsection
