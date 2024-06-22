@extends('manager.layout.container')
@section('title')
    {{ isset($manager) ? 'Edit Manager' : 'Add Manager' }}
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item text-muted"><a href="{{route('manager.manager.index')}}" class="">{{t('Managers')}}</a></li>

    <li class="breadcrumb-item text-muted">
        {{ isset($manager) ? t('Edit Manager' ) : t('Add Manager') }}
    </li>
@endpush

@section('content')
    <div class="row col-12">
            <form class="form" id="form_data"
                      action="{{isset($manager) ? route('manager.manager.update', $manager->id):route('manager.manager.store')}}"
                      method="post" autocomplete="off">
                    @csrf

                    @isset($manager)
                       @method('PATCH')
                    @endisset
                <div class="form-group row">
                    <div class="col-lg-3">
                        <label>{{t('Name')}} : </label>
                        <input name="name" type="text" placeholder="{{t('Name')}}"
                               class="form-control"
                               value="{{ isset($manager) ? $manager->name : old("name") }}"
                        />
                    </div>
                    <div class="col-lg-4">
                        <label>{{t('Email')}} :</label>
                        <input name="email" type="text" placeholder="{{t('Email')}}"
                               class="form-control" autocomplete="off"
                               value="{{ isset($manager) ? $manager->email : old("email") }}"/>
                    </div>
                    <div class="col-lg-3">
                        <label>{{t('Password')}} :</label>
                        <input name="password" type="password" placeholder="{{t('Password')}}"
                               class="form-control"/>
                    </div>
                    <div class="col-lg-2">
                        <label>{{t(' Status')}} :</label>

                        <div class="form-check form-switch form-check-custom form-check-solid mt-1">
                            <input class="form-check-input" type="checkbox" value="1" id="flexSwitchDefault"
                                   {{ isset($manager) && $manager->active == 1 ? 'checked' :'' }} name="active"
                            />
                        </div>
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
    {!! JsValidator::formRequest(\App\Http\Requests\Manager\ManagerRequest::class, '#form_data'); !!}
@endsection

