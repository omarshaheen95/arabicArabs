{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
 --}}
@extends('manager.layout.container')
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.supervisor.index') }}">المشرفون</a>
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
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right" action="{{ isset($supervisor) ? route('manager.supervisor.update', $supervisor->id): route('manager.supervisor.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($supervisor))
                        <input type="hidden" name="_method" value="patch">
                    @endif
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-xl-3 col-lg-3 col-form-label">الصورة</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="upload-btn-wrapper">
                                                    <button class="btn btn-danger">رفع صورة</button>
                                                    <input name="image" class="imgInp" id="imgInp" type="file" />
                                                </div>
                                                <img id="blah" @if(!isset($supervisor) || is_null($supervisor->image)) style="display:none" @endif src="{{ isset($supervisor) && !is_null($supervisor->image)  ? $supervisor->image:'' }}" width="150" alt="No file chosen" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">الاسم</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="name" type="text" value="{{ isset($supervisor) ? $supervisor->name : old("name") }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">البريد الإلكتروني</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="email" type="text" value="{{ isset($supervisor) ? $supervisor->email : old("email") }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">كلمة المرور</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="password" type="password" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">المدرسة</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select name="school_id" class="form-control select2L" title="اختر مدرسة">
                                            @foreach($schools as $school)
                                                <option value="{{$school->id}}" {{isset($supervisor) && $supervisor->school_id == $school->id ? 'selected':''}}>{{$school->name}}</option>
                                            @endforeach
                                        </select>                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">المدرسين</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select multiple name="teachers[]" class="form-control select2L" title="اختر مدرسين">
                                            @foreach($teachers as $teacher)
                                                <option value="{{$teacher->id}}" {{isset($supervisor) && in_array($teacher->id, $supervisor->supervisor_teachers->pluck('teacher_id')->toArray()) ? 'selected':''}}>{{$teacher->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label font-weight-bold">القبول</label>
                                    <div class="col-3">
                                        <span class="kt-switch">
                                            <label>
                                            <input type="checkbox" {{isset($supervisor) && $supervisor->approved ? 'checked':''}} value="1" name="approved">
                                            <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label font-weight-bold">التفعيل</label>
                                    <div class="col-3">
                                        <span class="kt-switch">
                                            <label>
                                            <input type="checkbox" {{isset($supervisor) && $supervisor->active ? 'checked':''}} value="1" name="active">
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
                                    <button type="submit" class="btn btn-danger">حفظ</button>&nbsp;
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
    {!! JsValidator::formRequest(\App\Http\Requests\Manager\SupervisorRequest::class, '#form_information'); !!}

    <script>
        $('select[name="school_id"]').change(function () {
            var id = $(this).val();
            var url = '{{ route("manager.getTeacherBySchool", ":id") }}';
            url = url.replace(':id', id );
            $.ajax({
                type: "get",
                url: url,
                data: {
                    selected: 1
                }
            }).done(function (data) {
                $('select[name="teachers[]"]').html(data.html);
                $('select[name="teachers[]"]').selectpicker('refresh');
            });
        });
    </script>
@endsection
