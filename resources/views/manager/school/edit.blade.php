@extends('manager.layout.container')

@section('title')
    {{$title}}
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item b text-muted">
        {{$title}}
    </li>
@endpush

@section('content')
    <form action="{{ isset($school) ? route('manager.school.update', $school->id): route('manager.school.store') }}"
          method="post" class="form" id="form-data" enctype="multipart/form-data">
        @csrf
        @if(isset($school))
            @method('PATCH')
        @endif
        <div class="row">
            <!--begin::Image input-->
            <div class="col-12 d-flex flex-column align-items-center mb-5">
                <div>{{t('Image')}}</div>
                <div class="image-input image-input-outline" data-kt-image-input="true"
                     style="background-image: url(/assets_v1/media/svg/avatars/blank.svg)">

                    @if(isset($school) && $school->logo )
                        <div class="image-input-wrapper w-125px h-125px"
                             style="background-image: url({{asset($school->logo)}})"></div>

                    @else
                        <div class="image-input-wrapper w-125px h-125px"
                             style="background-image: url(/assets_v1/media/svg/avatars/blank.svg)"></div>
                    @endif

                    <!--begin::Edit button-->
                    <label
                        class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                        data-kt-image-input-action="change"
                        data-bs-toggle="tooltip"
                        data-bs-dismiss="click"
                        title="Change avatar">
                        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                        <!--begin::Inputs-->
                        <input type="file" name="logo" accept=".png, .jpg, .jpeg"/>
                        <input type="hidden" name="avatar_remove"/>
                        <!--end::Inputs-->
                    </label>
                    <!--end::Edit button-->

                    <!--begin::Cancel button-->
                    <span
                        class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                        data-kt-image-input-action="cancel"
                        data-bs-toggle="tooltip"
                        data-bs-dismiss="click"
                        title="Cancel avatar">
                <i class="ki-outline ki-cross fs-3"></i>
            </span>
                    <!--end::Cancel button-->

                    <!--begin::Remove button-->
                    <span
                        class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                        data-kt-image-input-action="remove"
                        data-bs-toggle="tooltip"
                        data-bs-dismiss="click"
                        title="Remove avatar">
                <i class="ki-outline ki-cross fs-3"></i>
            </span>
                    <!--end::Remove button-->
                </div>
            </div>
            <!--end::Image input-->

            <div class="row">



                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="name" class="form-label">{{t('Name')}}</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="{{t('Name')}}"
                               value="{{ isset($school) ? $school->name : old("name") }}" required>
                    </div>
                </div>
                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="Email" class="form-label">{{t('Email')}}</label>
                        <input type="email" id="Email" name="email" class="form-control" placeholder="{{t('Email')}}"
                               value="{{ isset($school) ? $school->email : old("email") }}" required>
                    </div>
                </div>

                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="password" class="form-label">{{t('Password')}}</label>
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="{{t('Password')}}" required>
                    </div>
                </div>

                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="mobile" class="form-label">{{t('Mobile')}}</label>
                        <input type="tel" id="mobile" name="mobile" class="form-control" placeholder="{{t('Mobile')}}"
                               value="{{ isset($school) ? $school->mobile : old("mobile") }}" required>
                    </div>
                </div>

                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="mobile" class="form-label">{{t('Website')}}</label>
                        <input type="tel" name="website" class="form-control" placeholder="{{t('Website')}}"
                               value="{{ isset($school) ? $school->website : old("website") }}" required>
                    </div>
                </div>

                <div class="col-6 mb-2 d-flex gap-2 mt-4">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="active"
                               id="flexCheckDefault" {{isset($school) && $school->active ? 'checked':''}}/>
                        <label class="form-check-label text-dark" for="flexCheckDefault">
                            {{t('Active')}}
                        </label>
                    </div>
                </div>


            </div>
            <div class="row my-5">
                <div class="separator separator-content my-4"></div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit"
                            class="btn btn-primary mr-2">{{isset($school)?t('Update'):t('Submit')}}</button>
                </div>
            </div>
        </div>

    </form>
@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\Manager\SchoolRequest::class, '#form-data'); !!}
@endsection
