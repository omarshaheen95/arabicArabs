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
    <form action="{{ isset($supervisor) ? route('manager.supervisor.update', $supervisor->id): route('manager.supervisor.store') }}"
          method="post" class="form" id="form-data" enctype="multipart/form-data">
        @csrf
        @if(isset($supervisor))
            @method('PATCH')
        @endif
        <div class="row">
            <!--begin::Image input-->
            <div class="col-12 d-flex flex-column align-items-center mb-5">
                <div>{{t('Image')}}</div>
                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url(/manager_assets/media/svg/avatars/blank.svg)">

                    @if(isset($supervisor) && $supervisor->image )
                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{asset($supervisor->image)}})"></div>

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
                        <label class="form-label">{{t('Name')}}</label>
                        <input type="text" name="name" class="form-control" placeholder="{{t('Name')}}" value="{{ isset($supervisor) ? $supervisor->name : old("email") }}" required>
                    </div>
                </div>

                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="Email" class="form-label">{{t('Email')}}</label>
                        <input type="text" id="Email" name="email" class="form-control" placeholder="{{t('Email')}}" value="{{ isset($supervisor) ? $supervisor->email : old("email") }}" required>
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
                        <label class="mb-2">{{t('School')}} :</label>
                        <select name="school_id" class="form-select" data-control="select2" data-placeholder="{{t('Select School')}}" data-allow-clear="true">
                            <option></option>
                            @foreach($schools as $school)
                                <option value="{{$school->id}}" {{isset($supervisor) && $supervisor->school_id == $school->id ? 'selected':''}}>{{$school->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 mb-2">
                    <div class="form-group">
                        <label class="mb-2">{{t('Teachers')}} :</label>
                        <select name="teachers[]" class="form-select" data-control="select2" data-placeholder="{{t('Select Teachers')}}" data-allow-clear="true" multiple>
                            <option></option>
                            @foreach($teachers as $teacher)
                                <option value="{{$teacher->id}}" {{isset($supervisor) && in_array($teacher->id, $supervisor->supervisor_teachers->pluck('teacher_id')->toArray()) ? 'selected':''}}>{{$teacher->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-6 mb-2 d-flex gap-2 mt-4">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="approved"
                               id="flexCheckDefault" {{isset($supervisor) && $supervisor->approved ? 'checked':''}}/>
                        <label class="form-check-label text-dark" for="flexCheckDefault">
                            {{t('Approved')}}
                        </label>
                    </div>
                </div>

                <div class="col-6 mb-2 d-flex gap-2 mt-4">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="active" id="flexCheckDefault" {{isset($supervisor) && $supervisor->active ? 'checked':''}}/>
                        <label class="form-check-label text-dark" for="flexCheckDefault">
                            {{t('Active')}}
                        </label>
                    </div>

                </div>

            </div>
            <div class="row my-5">
                <div class="separator separator-content my-4"></div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mr-2">{{isset($supervisor)?t('Update'):t('Submit')}}</button>
                </div>
            </div>
        </div>

    </form>
@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\Manager\SupervisorRequest::class, '#form-data'); !!}
    <script>
        $('select[name="school_id"]').change(function () {
            var id = $(this).val();
            var url = '{{ route("manager.getTeacherBySchool", ":id") }}';
            url = url.replace(':id', id );
            $.ajax({
                type: "get",
                url: url,
            }).done(function (data) {
                $('select[name="teachers[]"]').html(data.html);
                console.log('data',data.html)
            });
        });
    </script>
@endsection
