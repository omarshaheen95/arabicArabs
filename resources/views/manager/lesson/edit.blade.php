{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('manager.layout.container')
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.lesson.index') }}">الدروس</a>
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
                      action="{{ isset($lesson) ? route('manager.lesson.update', $lesson->id): route('manager.lesson.store') }}"
                      method="post">
                    {{ csrf_field() }}
                    @if(isset($lesson))
                        <input type="hidden" name="_method" value="patch">
                    @endif
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">صورة الدرس</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-brand">اختر صورة</button>
                                            <input name="image" class="imgInp" id="imgInp" type="file" />
                                        </div>
                                        <img id="blah" @if(!isset($lesson)) style="display:none" @endif src="{{ isset($lesson) ? $lesson->getFirstMediaUrl('imageLessons'):'' }}" width="150" alt="No file chosen" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">اسم الدرس</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="name" type="text"
                                               value="{{ isset($lesson) ? $lesson->name : old("name") }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">الصف</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select name="grade_id" class="form-control select2L" title="اختر صف">
                                            @foreach($grades as $grade)
                                                <option {{isset($lesson) && $lesson->grade_id == $grade->id ? 'selected':''}} value="{{$grade->id}}">{{$grade->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">المهارة</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select name="lesson_type" class="form-control select2L" title="اختر مهارة">
                                            <option {{isset($lesson) && $lesson->lesson_type == 'reading' ? 'selected':''}} value="reading">قراءة</option>
                                            <option {{isset($lesson) && $lesson->lesson_type == 'writing' ? 'selected':''}} value="writing">كتابة</option>
                                            <option {{isset($lesson) && $lesson->lesson_type == 'listening' ? 'selected':''}} value="listening">استماع</option>
                                            <option {{isset($lesson) && $lesson->lesson_type == 'speaking' ? 'selected':''}} value="speaking">تحدث</option>
                                            <option {{isset($lesson) && $lesson->lesson_type == 'grammar' ? 'selected':''}} value="grammar">القواعد النحوية</option>
                                            <option {{isset($lesson) && $lesson->lesson_type == 'dictation' ? 'selected':''}} value="dictation">الإملاء</option>
                                            <option {{isset($lesson) && $lesson->lesson_type == 'rhetoric' ? 'selected':''}} value="rhetoric">البلاغة</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">التصنيف</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select name="section_type" class="form-control select2L">
                                            <option value="" selected>عام</option>
                                            <option {{isset($lesson) && $lesson->section_type == 'informative' ? 'selected':''}} value="informative">معلوماتي</option>
                                            <option {{isset($lesson) && $lesson->section_type == 'literary' ? 'selected':''}} value="literary">أدبي</option>
                                        </select>
                                    </div>
                                </div>

{{--                                <div class="form-group row">--}}
{{--                                    <label class="col-xl-3 col-lg-3 col-form-label">علامة النجاح</label>--}}
{{--                                    <div class="col-lg-9 col-xl-6">--}}
{{--                                        <input class="form-control" name="success_mark" type="number"--}}
{{--                                               value="{{ isset($lesson) ? $lesson->success_mark : 1 }}">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">ترتيب الدرس</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="ordered" type="number"
                                               value="{{ isset($lesson) ? $lesson->ordered : 1 }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">لون الدرس</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="color" type="color"
                                               value="{{ isset($lesson) ? $lesson->color : 1 }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">تفعيل الدرس</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <span class="kt-switch">
                                            <label>
                                                <input type="checkbox" value="1"
                                                       {{ isset($lesson) && $lesson->active ? 'checked' :'' }} name="active">
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
                                            class="btn btn-danger">{{ isset($lesson) ? 'تعديل':'إنشاء' }}</button>&nbsp;
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
    {!! JsValidator::formRequest(\App\Http\Requests\Manager\LessonRequest::class, '#form_information') !!}
@endsection
