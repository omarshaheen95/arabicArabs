{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
 --}}
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
    <form action="{{ isset($lesson) ? route('manager.lesson.update', $lesson->id): route('manager.lesson.store') }}"
          method="post" class="form" id="form-data" enctype="multipart/form-data">
        @csrf
        @if(isset($lesson))
            @method('PATCH')
        @endif
        <div class="row">
            <!--begin::Image input-->
            <div class="col-12 d-flex flex-column align-items-center mb-5">
                <div>{{t('Image')}}</div>
                <div class="image-input image-input-outline" data-kt-image-input="true"
                     style="background-image: url(/manager_assets/media/svg/avatars/blank.svg)">

                    @if(isset($lesson) )
                        <div class="image-input-wrapper w-125px h-125px"
                             style="background-image: url({{$lesson->getFirstMediaUrl('imageLessons')}})"></div>

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
                        <input type="file" name="image" accept=".png, .jpg, .jpeg"/>
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
                            <input type="text" id="" name="name" class="form-control"
                                   placeholder="{{t('Name')}}"
                                   value="{{ isset($lesson) ? $lesson->name : old("name") }}"
                                   required>
                        </div>
                    </div>


                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="grade" class="form-label">{{t('Grade')}}</label>
                        <select name="grade_id" class="form-select"  data-control="select2" data-placeholder="{{t('Select Grade')}}" data-allow-clear="true">
                            <option></option>
                            @foreach($grades as $grade)
                                <option {{isset($lesson) && $lesson->grade_id == $grade->id ? 'selected':''}} value="{{$grade->id}}">{{$grade->name}}</option>
                            @endforeach
                        </select>

                    </div>
                </div>

                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="lesson_type" class="form-label">{{t('Skill')}}</label>
                        <select name="lesson_type" class="form-select"  data-control="select2" data-placeholder="{{t('Select Skill')}}" data-allow-clear="true">
                            <option></option>
                            @foreach(lessonTypes() as $type)
                                <option {{isset($lesson) && $lesson->lesson_type == $type ? 'selected':''}} value="{{$type}}">{{t($type)}}</option>
                            @endforeach
                        </select>

                    </div>
                </div>

                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="section_type" class="form-label">{{t('Section Type')}}</label>
                        <select name="section_type" class="form-select"  data-control="select2" data-placeholder="{{t('Select Section Type')}}" data-allow-clear="true">
                            <option value="" selected>{{t('General')}}</option>
                            @foreach(['informative','literary'] as $section_type)
                                <option {{isset($lesson) && $lesson->section_type == $section_type ? 'selected':''}} value="{{$section_type}}">{{t($section_type)}}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="col-4 mb-2" id="levels">
                    <div class="form-group">
                        <label for="level" class="form-label">{{t('Level')}}</label>
                        <select name="level" class="form-select"  data-control="select2" data-placeholder="{{t('Select Level')}}" data-allow-clear="true">
                            <option value=""></option>
                            @foreach(range(1,12) as $value)
                                <option {{isset($lesson) && !is_null($lesson->level) && $lesson->level == $value ? 'selected':''}} value="{{$value}}">{{$value}}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="form-group">
                        <label for="ordered" class="form-label">{{t('Lesson Order')}}</label>
                        <input type="number" id="" name="ordered" class="form-control"
                               value="{{ isset($lesson) ? $lesson->ordered : 1 }}">
                    </div>
                </div>

                <div class="col-4 mb-2">
                    <div class="form-group">
                        <label for="color" class="form-label">{{t('Lesson Color')}}</label>
                        <input type="color" id="" name="color" class="form-control" style="height: 43px"
                               value="{{ isset($lesson) ? $lesson->color : 1 }}">
                    </div>
                </div>



                <div class="col-3 mb-2 d-flex gap-2 mt-4">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="active"
                               id="flexCheckDefault" {{isset($lesson) && $lesson->active ? 'checked':''}}/>
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
                            class="btn btn-primary mr-2">{{isset($lesson)?t('Update'):t('Save')}}</button>
                </div>
            </div>
        </div>

    </form>


@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\Manager\LessonRequest::class, '#form-data'); !!}
    <script>
        let lessonTypes = ['grammar', 'dictation', 'rhetoric'];

        @if(isset($lesson))
        showOrHideLevels("{{$lesson->lesson_type}}") //on start
        @else
        $('#levels').addClass('d-none')
        @endif

        $('select[name="lesson_type"]').on('change',function () {
            let value = $(this).val()
            showOrHideLevels(value)
        })

        function showOrHideLevels(value) {
            if (lessonTypes.includes(value)){
                $('#levels').removeClass('d-none')
            }else {
                $('#levels').addClass('d-none')
            }
        }
    </script>
@endsection
