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
    <form action="{{ isset($teacher) ? route('manager.teacher.update', $teacher->id): route('manager.teacher.store') }}"
          method="post" class="form" id="form-data" enctype="multipart/form-data">
        @csrf
        @if(isset($teacher))
            @method('PATCH')
        @endif
        <div class="row">
            <!--begin::Image input-->
            <div class="col-12 d-flex flex-column align-items-center mb-5">
                <div>{{t('Image')}}</div>
                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url(/manager_assets/media/svg/avatars/blank.svg)">

                    @if(isset($teacher) && $teacher->image )
                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{asset($teacher->image)}})"></div>

                    @else
                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url(/assets_v1/media/svg/avatars/blank.svg)"></div>
                    @endif

                    <!--begin::Edit button-->
                    <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                           data-kt-image-input-action="change"
                           data-bs-toggle="tooltip"
                           data-bs-dismiss="click"
                           title="Change avatar">
                        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                        <!--begin::Inputs-->
                        <input type="file" name="image" accept=".png, .jpg, .jpeg" />
                        <input type="hidden" name="avatar_remove" />
                        <!--end::Inputs-->
                    </label>
                    <!--end::Edit button-->

                    <!--begin::Cancel button-->
                    <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                          data-kt-image-input-action="cancel"
                          data-bs-toggle="tooltip"
                          data-bs-dismiss="click"
                          title="Cancel avatar">
                <i class="ki-outline ki-cross fs-3"></i>
            </span>
                    <!--end::Cancel button-->

                    <!--begin::Remove button-->
                    <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
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
                        <label for="School_Name" class="form-label">{{t('Name')}}</label>
                        <input type="url" id="School_Name" name="name" class="form-control" placeholder="{{t('Name')}}" value="{{ isset($teacher) ? $teacher->name : old("name") }}" required>
                    </div>
                </div>

                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="Email" class="form-label">{{t('Email')}}</label>
                        <input type="email" id="Email" name="email" class="form-control" placeholder="{{t('Email')}}" value="{{ isset($teacher) ? $teacher->email : old("email") }}" required>
                    </div>
                </div>

                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="password" class="form-label">{{t('Password')}}</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="{{t('Password')}}" required>
                    </div>
                </div>

                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="mobile" class="form-label">{{t('Mobile')}}</label>
                        <input type="tel" id="mobile" name="mobile" class="form-control" placeholder="{{t('Mobile')}}" value="{{ isset($teacher) ? $teacher->mobile : old("mobile") }}" required>
                    </div>
                </div>

                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label class="form-label">{{t('School')}}</label>
                        <select name="school_id" class="form-select" data-control="select2" data-placeholder="{{t('Select School')}}" data-allow-clear="true">
                            <option></option>
                            @foreach($schools as $school)
                                <option value="{{$school->id}}" {{isset($teacher) && $teacher->school_id==$school->id?'selected':''}}>{{$school->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label">{{t('Active To')}}</label>
                        <input class="form-control datePiker" name="active_to" type="text"
                               value="{{ isset($teacher) ? $teacher->active_to : old("active_to") }}" id="active_to" placeholder="{{t('Active To')}}">
                    </div>
                </div>
                <div class="col-6 mb-2 d-flex gap-2 mt-4">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="active" id="flexCheckDefault" {{isset($teacher) && $teacher->active ? 'checked':''}}/>
                        <label class="form-check-label text-dark" for="flexCheckDefault">
                            {{t('Active')}}
                        </label>
                    </div>

                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="approved" id="flexCheckDefault" {{isset($teacher) && $teacher->approved ? 'checked':''}}/>
                        <label class="form-check-label text-dark" for="flexCheckDefault">
                            {{t('Approved')}}
                        </label>
                    </div>
                </div>

            </div>
            <div class="row my-5">
                <div class="separator separator-content my-4"></div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mr-2">{{isset($teacher)?t('Update'):t('Submit')}}</button>
                </div>
            </div>
        </div>

    </form>
@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\Manager\TeacherRequest::class, '#form-data'); !!}
    <script>
        $(document).ready(function () {
            $('.datePiker').flatpickr();
        });
    </script>
@endsection
